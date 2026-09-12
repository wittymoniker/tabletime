<?php
require_once __DIR__.'/tag_engine.php';
require_once __DIR__.'/maintenance.php';
function tt_default_node_tier(): string {
    $env=strtolower(trim((string)(getenv('TT_NODE_TIER')?:'')));if(in_array($env,['host','admin'],true))return $env;
    $cfg=__DIR__.'/config/node_tier.php';if(is_file($cfg)){$v=require $cfg;if(is_string($v)&&in_array(strtolower($v),['host','admin'],true))return strtolower($v);}return 'host';
}
function tt_node_tier_level(): int {return tt_default_node_tier()==='admin'?2:1;}
function tt_public_base_url(): string {
    $e=rtrim((string)(getenv('TT_PUBLIC_BASE_URL')?:''),'/');if($e!=='')return $e;
    $https=(!empty($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!=='off')?'https':'http';$host=(string)($_SERVER['HTTP_HOST']??'');if($host==='')return '';
    $script=str_replace('\\','/',(string)($_SERVER['SCRIPT_NAME']??''));$dir=rtrim(str_replace('\\','/',dirname($script)),'/');if(str_ends_with($dir,'/api'))$dir=rtrim(str_replace('\\','/',dirname($dir)),'/');if($dir==='/'||$dir==='.')$dir='';return $https.'://'.$host.$dir;
}
function tt_validate_peer_url(string $url): ?string {
    $url=rtrim(trim($url),'/');$p=parse_url($url);if(!$p||empty($p['scheme'])||empty($p['host']))return null;
    $scheme=strtolower($p['scheme']);if($scheme!=='https' && !($scheme==='http' && getenv('TT_ALLOW_HTTP_PEERS')==='1'))return null;
    $host=strtolower($p['host']);if($host==='localhost'||str_ends_with($host,'.local'))return null;
    if(filter_var($host,FILTER_VALIDATE_IP)){
        if(!filter_var($host,FILTER_VALIDATE_IP,FILTER_FLAG_NO_PRIV_RANGE|FILTER_FLAG_NO_RES_RANGE))return null;
    }
    return $scheme.'://'.$host.(isset($p['port'])?':'.(int)$p['port']:'').(isset($p['path'])?rtrim($p['path'],'/'):'');
}
function tt_http_json(string $url,int $timeout=3): ?array {
    $ctx=stream_context_create(['http'=>['timeout'=>$timeout,'ignore_errors'=>true,'header'=>"Accept: application/json\r\nUser-Agent: Tabletime-Federation/1.0\r\n"],'ssl'=>['verify_peer'=>true,'verify_peer_name'=>true]]);
    $raw=@file_get_contents($url,false,$ctx);if($raw===false||strlen($raw)>1048576)return null;$x=json_decode($raw,true);return is_array($x)?$x:null;
}
function tt_import_federated_post(mysqli $db,array $p,string $peerBase): bool {
    $scope=trim((string)($p['scope']??''));if(!in_array($scope,['public','global'],true))return false;
    $originHost=rtrim((string)($p['origin_host']??$peerBase),'/');$originId=(string)($p['origin_post_id']??$p['id']??'');if($originHost===''||$originId==='')return false;
    $title=trim((string)($p['title']??''));$content=(string)($p['content']??'');$tags=tt_compact_tags((string)($p['tags']??''),4096);$author=substr(trim((string)($p['name']??'remote')),0,50);$type=substr(trim((string)($p['type']??'post')),0,32);$dt=(string)($p['dt']??gmdate('Y-m-d H:i:s'));
    if($title==='')$title='Federated post';$title=substr($title,0,255);if(strlen($content)>32768)$content=substr($content,0,32768);
    $file='';$remoteFile=(string)($p['file_url']??'');if(preg_match('~^https://~i',$remoteFile))$file=substr($remoteFile,0,1024);
    $comments='';$recipients='';$votes='';$federated=1;$originCreated=(string)($p['origin_created_at']??$dt);
    tt_preflight_write($db,strlen($title)+strlen($content)+strlen($tags)+12288,true);
    $q=$db->prepare('SELECT id FROM posts WHERE origin_host=? AND origin_post_id=? LIMIT 1');$q->bind_param('ss',$originHost,$originId);$q->execute();$q->bind_result($existing);$has=$q->fetch();$q->close();
    if($has){
        $id=(int)$existing;$u=$db->prepare('UPDATE posts SET title=?,content=?,dt=?,file=?,tags=?,name=?,scope=?,type=?,origin_created_at=? WHERE id=?');$u->bind_param('sssssssssi',$title,$content,$dt,$file,$tags,$author,$scope,$type,$originCreated,$id);$u->execute();$u->close();tt_index_post_tags($db,$id,$tags);return true;
    }
    $i=$db->prepare('INSERT INTO posts(title,content,dt,file,tags,name,comments,scope,recipients,type,votes,pinned,origin_host,origin_post_id,origin_created_at,federated) VALUES(?,?,?,?,?,?,?,?,?,?,?,0,?,?,?,?)');
    // Use explicit temporary variables to keep mysqli binding portable.
    $i->bind_param('ssssssssssssssi',$title,$content,$dt,$file,$tags,$author,$comments,$scope,$recipients,$type,$votes,$originHost,$originId,$originCreated,$federated);
    if(!$i->execute()){$i->close();return false;}$id=(int)$db->insert_id;$i->close();tt_index_post_tags($db,$id,$tags);return true;
}
function tt_sync_peer(mysqli $db,int $peerId): array {
    $q=$db->prepare('SELECT base_url,cursor_at FROM federation_peers WHERE id=? AND enabled=1');$q->bind_param('i',$peerId);$q->execute();$r=$q->get_result();$peer=$r->fetch_assoc();$q->close();if(!$peer)return ['ok'=>false,'message'=>'Peer not found or disabled'];
    $base=tt_validate_peer_url((string)$peer['base_url']);if(!$base)return ['ok'=>false,'message'=>'Peer URL rejected'];$since=(string)($peer['cursor_at']?:'1970-01-01 00:00:00');
    $url=$base.'/api/federation.php?action=pull&limit=50&since='.rawurlencode($since);$data=tt_http_json($url,4);if(!$data||!isset($data['posts'])||!is_array($data['posts'])){$msg='No valid federation response';$u=$db->prepare('UPDATE federation_peers SET last_error=?,last_sync_at=UTC_TIMESTAMP() WHERE id=?');$u->bind_param('si',$msg,$peerId);$u->execute();$u->close();return ['ok'=>false,'message'=>$msg];}
    $n=0;$latest=$since;foreach($data['posts'] as $p){try{if(tt_import_federated_post($db,$p,$base))$n++;$d=(string)($p['dt']??'');if($d>$latest)$latest=$d;}catch(Throwable $e){break;}}
    $err='';$u=$db->prepare('UPDATE federation_peers SET cursor_at=?,last_sync_at=UTC_TIMESTAMP(),last_error=? WHERE id=?');$u->bind_param('ssi',$latest,$err,$peerId);$u->execute();$u->close();return ['ok'=>true,'imported'=>$n,'cursor'=>$latest];
}
function tt_federation_tick(mysqli $db): void {
    // Opportunistic uplink: at most one due peer per request and no more often than every ~10 minutes per peer.
    $r=$db->query("SELECT id FROM federation_peers WHERE enabled=1 AND (last_sync_at IS NULL OR last_sync_at < UTC_TIMESTAMP()-INTERVAL 10 MINUTE) ORDER BY COALESCE(last_sync_at,'1970-01-01') ASC LIMIT 1");
    if($r&&($x=$r->fetch_assoc())){@tt_sync_peer($db,(int)$x['id']);}$r&&$r->free();
}
?>
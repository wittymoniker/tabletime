<?php
function tt_collection_date(array $r): array {
    $text=implode("\n",[(string)($r['tags']??''),(string)($r['title']??''),(string)($r['content']??''),(string)($r['attachment_filename']??'')]);
    $date='';$source='post date';
    foreach([
      ['/\b(?:date|event[-_ ]?date|when)\s*[:=]\s*(20\d{2})[-\/.](\d{1,2})[-\/.](\d{1,2})\b/i','tag/content'],
      ['/\b(20\d{2})[-_.](\d{1,2})[-_.](\d{1,2})\b/','content/filename'],
      ['/\b(\d{1,2})[\/.](\d{1,2})[\/.](20\d{2})\b/','content/filename']
    ] as [$rx,$src]){
        if(preg_match($rx,$text,$m)){
            if(strlen($m[1])===4){$y=(int)$m[1];$mo=(int)$m[2];$d=(int)$m[3];}
            else{$mo=(int)$m[1];$d=(int)$m[2];$y=(int)$m[3];}
            if(checkdate($mo,$d,$y)){$date=sprintf('%04d-%02d-%02d',$y,$mo,$d);$source=$src;break;}
        }
    }
    if($date===''){
      $months='January|February|March|April|May|June|July|August|September|October|November|December|Jan|Feb|Mar|Apr|Jun|Jul|Aug|Sep|Sept|Oct|Nov|Dec';
      if(preg_match('/\b('.$months.')\s+(\d{1,2})(?:st|nd|rd|th)?(?:,\s*|\s+)(20\d{2})\b/i',$text,$m)){
        $ts=strtotime($m[1].' '.$m[2].' '.$m[3]);if($ts!==false){$date=date('Y-m-d',$ts);$source='content/filename';}
      }
    }
    if($date===''){$ts=strtotime((string)($r['dt']??''))?:time();$date=date('Y-m-d',$ts);}
    $annual=(bool)preg_match('/(?:^|[;,\s])(?:annual|yearly|recurs?\s*[:=]\s*yearly|repeat\s*[:=]\s*yearly)(?:$|[;,\s])/i',(string)($r['tags']??'').' '.(string)($r['content']??''));
    $next='';if($annual){$md=substr($date,5);$year=(int)date('Y');$candidate=$year.'-'.$md;if($candidate<date('Y-m-d'))$candidate=($year+1).'-'.$md;$next=$candidate;}
    return ['date'=>$date,'source'=>$source,'annual'=>$annual,'next'=>$next];
}
function tt_collection_attachment_html(array $r): string {
    $pid=(int)($r['id']??0);$mime=(string)($r['attachment_mime']??'');$name=(string)($r['attachment_filename']??'');
    if($pid<=0||$name==='')return '';$u='attachment.php?post='.$pid;$safe=tt_h($u);$cap=tt_h($name);
    if(str_starts_with($mime,'image/'))return '<figure class="collection-media"><a href="'.$safe.'"><img src="'.$safe.'" alt="'.$cap.'" loading="lazy"></a><figcaption>'.$cap.'</figcaption></figure>';
    if(str_starts_with($mime,'video/'))return '<figure class="collection-media"><video src="'.$safe.'" controls playsinline preload="metadata"></video><figcaption>'.$cap.'</figcaption></figure>';
    if(str_starts_with($mime,'audio/'))return '<figure class="collection-media"><audio src="'.$safe.'" controls preload="metadata"></audio><figcaption>'.$cap.'</figcaption></figure>';
    return '<figure class="collection-media"><a href="'.$safe.'">'.$cap.'</a></figure>';
}
?>

<?php
/**
 * Tabletime tag engine.
 *
 * Supports the original two families of tags:
 *   - measureless/topic tags: table; -late; table+;
 *   - numeric tags: food=0.5; fork<=7500; late=-15000-7500;
 *                    5:00 + 7500; dinner=2;
 *
 * Numeric arithmetic is evaluated without eval(); only + - * / parentheses and
 * decimal/scientific numbers are accepted.  The raw post tag string is retained,
 * while a compact normalized index is written to tag_index.
 */

function tt_skip_ws(string $s, int &$i): void {
    $n = strlen($s);
    while ($i < $n && ctype_space($s[$i])) { $i++; }
}
function tt_parse_numeric_factor(string $s, int &$i, bool &$ok): float {
    tt_skip_ws($s,$i); $n=strlen($s); $sign=1.0;
    while($i<$n && ($s[$i]=='+' || $s[$i]=='-')) { if($s[$i]=='-')$sign*=-1.0; $i++; tt_skip_ws($s,$i); }
    if($i<$n && $s[$i]=='('){ $i++; $v=tt_parse_numeric_expr_inner($s,$i,$ok); tt_skip_ws($s,$i); if(!$ok || $i >= $n || $s[$i]!==')'){ $ok=false; return 0.0; } $i++; return $sign*$v; }
    if($i >= $n){ $ok=false; return 0.0; }
    $rest=substr($s,$i);
    if(!preg_match('/^(?:\d+(?:\.\d*)?|\.\d+)(?:[eE][+\-]?\d+)?/', $rest, $m)){ $ok=false; return 0.0; }
    $i += strlen($m[0]);
    return $sign*(float)$m[0];
}
function tt_parse_numeric_term(string $s, int &$i, bool &$ok): float {
    $v=tt_parse_numeric_factor($s,$i,$ok); if(!$ok)return 0.0;
    $n=strlen($s);
    while(true){ tt_skip_ws($s,$i); if($i >= $n || ($s[$i] !== '*' && $s[$i] !== '/')) break; $op=$s[$i++]; $rhs=tt_parse_numeric_factor($s,$i,$ok); if(!$ok)return 0.0; if($op==='*')$v*=$rhs; else { if(abs($rhs)<1e-300){$ok=false;return 0.0;} $v/=$rhs; } }
    return $v;
}
function tt_parse_numeric_expr_inner(string $s, int &$i, bool &$ok): float {
    $v=tt_parse_numeric_term($s,$i,$ok); if(!$ok)return 0.0;
    $n=strlen($s);
    while(true){ tt_skip_ws($s,$i); if($i >= $n || ($s[$i] !== '+' && $s[$i] !== '-')) break; $op=$s[$i++]; $rhs=tt_parse_numeric_term($s,$i,$ok); if(!$ok)return 0.0; $v = $op==='+' ? $v+$rhs : $v-$rhs; }
    return $v;
}
function tt_numeric_eval(string $expr): ?float {
    $expr=trim($expr); if($expr==='')return null;
    if(!preg_match('/^[0-9eE+\-*\/().\s]+$/',$expr))return null;
    $i=0;$ok=true;$v=tt_parse_numeric_expr_inner($expr,$i,$ok);tt_skip_ws($expr,$i);
    if(!$ok || $i!==strlen($expr) || !is_finite($v))return null;
    return $v;
}
function tt_tag_name_and_polarity(string $name): array {
    $name=trim($name); $polarity=1;
    if($name!=='' && ($name[0]=='+' || $name[0]=='-')){ $polarity=$name[0]=='-'?-1:1; $name=ltrim(substr($name,1)); }
    if($name!=='' && (substr($name,-1)=='+' || substr($name,-1)=='-')){ $polarity=substr($name,-1)=='-'?-1:1; $name=rtrim(substr($name,0,-1)); }
    $name=preg_replace('/\s+/u',' ',$name) ?? $name;
    if(function_exists('mb_strtolower'))$canon=mb_strtolower($name,'UTF-8'); else $canon=strtolower($name);
    if(function_exists('mb_substr')){$name=mb_substr($name,0,191,'UTF-8');$canon=mb_substr($canon,0,191,'UTF-8');}
    else {$name=substr($name,0,191);$canon=substr($canon,0,191);}
    return [$name,$canon,$polarity];
}
function tt_parse_tag_token(string $token): ?array {
    $raw=trim($token); if($raw==='')return null;
    $lhs=$raw;$op='';$expr='';$value=null;$numeric=false;
    if(preg_match('/^(.*?)\s*(<=|>=|=|<|>)\s*(.+)$/u',$raw,$m)){
        $candidate=tt_numeric_eval($m[3]);
        if($candidate!==null){$lhs=trim($m[1]);$op=$m[2];$expr=trim($m[3]);$value=$candidate;$numeric=true;}
    }
    // Original Tabletime also allowed an implicit numeric suffix: "5:00 + 7500".
    if(!$numeric && preg_match('/^(.*?)\s+([+\-]\s*(?:\d|\.)(?:[0-9eE+\-*\/().\s]*))$/u',$raw,$m)){
        $candidate=tt_numeric_eval($m[2]);
        if($candidate!==null && trim($m[1])!==''){$lhs=trim($m[1]);$op='=';$expr=trim($m[2]);$value=$candidate;$numeric=true;}
    }
    [$display,$canon,$polarity]=tt_tag_name_and_polarity($lhs);
    if($canon==='')return null;
    return [
        'raw'=>$raw,
        'tag'=>$display,
        'canon'=>$canon,
        'numeric'=>$numeric,
        'polarity'=>$polarity,
        'operator'=>$op,
        'value'=>$value,
        'expression'=>$expr,
    ];
}
function tt_parse_tags(string $raw): array {
    $tokens=preg_split('/[;,\r\n]+/u',$raw) ?: [];$out=[];
    foreach($tokens as $token){$p=tt_parse_tag_token($token);if($p)$out[]=$p;}
    return $out;
}
function tt_compact_tags(string $raw, int $maxBytes=4096): string {
    $parts=[];
    foreach(tt_parse_tags($raw) as $p){$parts[]=$p['raw'];}
    $s=implode('; ',$parts);
    if(strlen($s)>$maxBytes)$s=substr($s,0,$maxBytes);
    return $s;
}
function tt_index_post_tags(mysqli $db,int $postId,string $raw): void {
    $d=$db->prepare('DELETE FROM post_tags WHERE post_id=?');$d->bind_param('i',$postId);$d->execute();$d->close();
    $d=$db->prepare('DELETE FROM tag_index WHERE post_id=?');$d->bind_param('i',$postId);$d->execute();$d->close();
    $simple=$db->prepare('INSERT IGNORE INTO post_tags(tag,post_id) VALUES(?,?)');
    $detail=$db->prepare('INSERT INTO tag_index(post_id,tag,raw_token,is_numeric,polarity,comparator,numeric_value,expression_text) VALUES(?,?,?,?,?,?,?,?)');
    foreach(tt_parse_tags($raw) as $p){
        $tag=$p['canon'];$simple->bind_param('si',$tag,$postId);$simple->execute();
        $isnum=$p['numeric']?1:0;$pol=(int)$p['polarity'];$cmp=(string)$p['operator'];$tok=(string)$p['raw'];$expr=(string)$p['expression'];
        if($p['numeric']){$val=(float)$p['value'];$detail->bind_param('issiisds',$postId,$tag,$tok,$isnum,$pol,$cmp,$val,$expr);}else{$val=null;$detail->bind_param('issiisds',$postId,$tag,$tok,$isnum,$pol,$cmp,$val,$expr);}
        $detail->execute();
    }
    $simple->close();$detail->close();
}
function tt_numeric_constraint_match(float $postValue,string $cmp,float $queryValue): float {
    if($cmp==='<' ) return $postValue <  $queryValue ? 1.0 : -1.0;
    if($cmp==='<=' )return $postValue <= $queryValue ? 1.0 : -1.0;
    if($cmp==='>' ) return $postValue >  $queryValue ? 1.0 : -1.0;
    if($cmp==='>=' )return $postValue >= $queryValue ? 1.0 : -1.0;
    $den=max(1.0,abs($postValue),abs($queryValue));
    return max(-1.0,1.0-(abs($postValue-$queryValue)/$den));
}
/**
 * A compact implementation of the original one-to-one numeric vs measureless
 * relevance concept.  If both groups are present, each group contributes 50%.
 * Opposite +/- polarity repels. Numeric values use normalized closeness or the
 * supplied < <= > >= condition.
 */
function tt_tag_relevance(string $queryRaw,string $postRaw): float {
    $q=tt_parse_tags($queryRaw);$p=tt_parse_tags($postRaw);if(!$q)return 0.0;
    $pn=[];$pp=[];foreach($p as $x){$pp[$x['canon']][]=$x;}
    $numScores=[];$topicScores=[];
    foreach($q as $x){
        $matches=$pp[$x['canon']]??[];$best=null;
        foreach($matches as $y){
            if($x['numeric']){
                if(!$y['numeric'])continue;$s=tt_numeric_constraint_match((float)$y['value'],$x['operator']?:'=',(float)$x['value']);$s*=($x['polarity']*$y['polarity']);
            }else{
                if($y['numeric'])continue;$s=(float)($x['polarity']*$y['polarity']);
            }
            if($best===null || $s>$best)$best=$s;
        }
        $score=$best??0.0;
        if($x['numeric'])$numScores[]=$score;else$topicScores[]=$score;
    }
    $avg=function(array $a):float{return $a?array_sum($a)/count($a):0.0;};
    if($numScores && $topicScores)return 0.5*$avg($numScores)+0.5*$avg($topicScores);
    return $numScores?$avg($numScores):$avg($topicScores);
}
?>
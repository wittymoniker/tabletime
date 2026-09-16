<?php
define('TT_DB_ALLOW_UNCONFIGURED',true);
require_once __DIR__.'/db.php';require_once __DIR__.'/schema_compat.php';require_once __DIR__.'/tag_engine.php';require_once __DIR__.'/maintenance.php';
if(function_exists('mysqli_report'))mysqli_report(MYSQLI_REPORT_OFF);
$message='';$error='';$notes=[];$db=null;$tables=0;$size=0;
if(!$TT_DB_CONFIGURED){$error='No database credentials are visible to Tabletime.';}
elseif(!class_exists('mysqli')){$error='Database credentials are configured, but PHP mysqli is not loaded.';}
else{$db=@new mysqli($DATABASE_HOST,$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME);if($db->connect_errno){$error='MySQL connection failed.';}else{$db->set_charset('utf8mb4');
  if($_SERVER['REQUEST_METHOD']==='POST'){
    if(getenv('TABLETIME_ALLOW_RUNTIME_SCHEMA_UPGRADE')!=='1'){$error='Schema maintenance is disabled. Enable TABLETIME_ALLOW_RUNTIME_SCHEMA_UPGRADE=1 only for a controlled maintenance run.';}
    else{try{if(!tt_upgrade_runtime_schema($db,$notes))throw new RuntimeException('runtime schema migration returned false');$missing=tt_verify_accounts_runtime_columns($db);if($missing)throw new RuntimeException('required account columns are missing');$selfcheck=tt_runtime_sql_selfcheck($db);if($selfcheck)throw new RuntimeException('critical SQL prepare checks failed');$writecheck=tt_runtime_write_selfcheck($db);if($writecheck)throw new RuntimeException('critical write/read self-test failed');$message='Controlled setup/upgrade completed successfully.';}catch(Throwable $e){$error='Controlled setup failed.';}}
  }
  if($r=$db->query('SELECT COUNT(*) AS `c` FROM information_schema.tables WHERE table_schema=DATABASE()')){$tables=(int)$r->fetch_assoc()['c'];$r->free();}$size=tt_db_size_bytes($db);
}}
?><!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Tabletime · Database Maintenance</title><style>body{font:16px system-ui;background:#0d1117;color:#e6edf3;max-width:860px;margin:6vh auto;padding:24px}.ok{background:#123d2a;padding:12px}.err{background:#4d1717;padding:12px}a{color:#7ee7ff}button{padding:.7rem 1rem}</style></head><body><h1>Tabletime · database maintenance</h1><?php if($message):?><p class="ok"><?=htmlspecialchars($message,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8')?></p><?php endif;?><?php if($error):?><p class="err"><?=htmlspecialchars($error,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8')?></p><?php endif;?><p>Database: <strong><?=$db instanceof mysqli&&!$db->connect_errno?'connected':'not connected'?></strong></p><p>Credential source: <strong><?=htmlspecialchars($TT_DB_SOURCE,ENT_QUOTES)?></strong></p><p>Detected tables: <strong><?=$tables?></strong></p><p>Current database size: <strong><?=number_format($size/1000000,2)?> MB</strong></p><p>This page does not alter the schema on GET. Migration/self-test requires a POST, TABLETIME_MAINTENANCE_ENABLED=1, TABLETIME_ADMIN_TOKEN supplied as an HTTP header, and TABLETIME_ALLOW_RUNTIME_SCHEMA_UPGRADE=1.</p><form method="post"><button type="submit">Run controlled schema check/upgrade</button></form><p><a href="/">Eski home</a></p>
<!-- TABLETIME_NSFW_BLUR_20260915 -->
<script src="nsfw-blur.js?v=20260915c" defer></script>
</body></html>

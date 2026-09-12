(()=>{if(!('serviceWorker'in navigator))return;let last=Number(localStorage.getItem('ttNotificationId')||0);navigator.serviceWorker.register('sw.js').catch(()=>{});async function poll(){if(document.visibilityState==='hidden'||document.visibilityState==='visible'){try{const r=await fetch('api/notifications.php?after='+last,{credentials:'same-origin',cache:'no-store'});if(!r.ok)return;const d=await r.json();for(const n of d.notifications||[]){last=Math.max(last,Number(n.id)||0);if(Notification.permission==='granted'){const reg=await navigator.serviceWorker.ready;reg.showNotification(n.title||'Tabletime',{body:n.body||'',tag:'tt-'+n.id,data:{url:n.url||'home.php'}});}}localStorage.setItem('ttNotificationId',String(last));}catch(_){}}}window.ttEnableNotifications=()=>Notification.requestPermission();if('Notification'in window&&Notification.permission==='default'){const b=document.createElement('button');b.textContent='Enable notifications';b.className='tt-notify-enable';b.onclick=()=>Notification.requestPermission().then(()=>b.remove());document.addEventListener('DOMContentLoaded',()=>document.body.appendChild(b),{once:true});}setInterval(poll,10000);setTimeout(poll,1200);})();
;(()=>{
  if(window.__ttPostCardExpandBooted)return;
  window.__ttPostCardExpandBooted=true;
  const interactive='a,button,input,select,textarea,label,summary,details,form,video,audio,iframe,object,embed,[contenteditable="true"],[role="button"]';
  document.addEventListener('click',e=>{
    const card=e.target.closest('.post-card[data-post-expandable="1"]');
    if(!card||e.target.closest(interactive))return;
    const sel=window.getSelection&&window.getSelection();
    if(sel&&String(sel).trim()!=='')return;
    const expanded=card.classList.toggle('is-expanded');
    card.setAttribute('aria-expanded',expanded?'true':'false');
    if(expanded)card.scrollIntoView({behavior:'smooth',block:'nearest'});
  });
})();

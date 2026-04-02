<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DocuTranslate</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{
  --bg:#eef2ff;
  --card:#ffffff;
  --text:#0f172a;
  --muted:#64748b;
  --border:#dbe4ff;
  --primary:#4f46e5;
  --primary2:#a21caf;
  --shadow:0 16px 40px rgba(15,23,42,.08);
  --radius:22px;
}
*{box-sizing:border-box}
body{margin:0;font-family:Inter,sans-serif;background:linear-gradient(180deg,#f8faff 0,#eef2ff 100%);color:var(--text)}
button,input,select,textarea{font:inherit}
button{cursor:pointer}
.hidden{display:none!important}
.auth-shell{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px}
.auth-card{width:100%;max-width:460px;background:var(--card);border-radius:28px;box-shadow:var(--shadow);padding:32px}
.brand{display:flex;align-items:center;gap:14px;margin-bottom:18px}
.brand-icon,.mini-icon{width:52px;height:52px;border-radius:16px;background:linear-gradient(135deg,var(--primary),var(--primary2));display:flex;align-items:center;justify-content:center;color:#fff;font-size:22px;font-weight:800;box-shadow:0 10px 24px rgba(79,70,229,.28)}
.brand h1{margin:0;font-size:30px}
.brand p{margin:4px 0 0;color:var(--muted);font-size:14px}
.tabs{display:grid;grid-template-columns:1fr 1fr;background:#f1f5ff;border-radius:14px;padding:4px;margin:18px 0}
.tabs button{border:0;background:transparent;padding:12px;border-radius:10px;font-weight:700;color:var(--muted)}
.tabs button.active{background:#fff;color:var(--text);box-shadow:0 4px 12px rgba(15,23,42,.06)}
.form-group{margin-bottom:14px}.form-group label{display:block;font-size:14px;font-weight:700;margin-bottom:8px}
.input{width:100%;border:1px solid var(--border);background:#f8fbff;border-radius:14px;padding:14px 16px;outline:none}
.input:focus{border-color:#93c5fd;background:#fff}
.primary-btn,.ghost-btn,.danger-btn,.menu-btn,.mail-action{border:0;border-radius:14px;padding:14px 18px;font-weight:800;transition:.2s}
.primary-btn{background:linear-gradient(135deg,#3b82f6,var(--primary2));color:#fff;width:100%}
.ghost-btn{background:#fff;border:1px solid var(--border);color:var(--text)}
.danger-btn{background:#fee2e2;color:#b91c1c}
.error{display:none;background:#fef2f2;color:#b91c1c;padding:12px 14px;border-radius:12px;margin:10px 0 0;font-size:14px}
.app{display:none;min-height:100vh}
.nav{height:78px;display:flex;align-items:center;justify-content:space-between;padding:0 24px;background:rgba(255,255,255,.86);backdrop-filter:blur(10px);border-bottom:1px solid var(--border);position:sticky;top:0;z-index:5}
.nav-left{display:flex;align-items:center;gap:14px}.nav-right{display:flex;align-items:center;gap:12px}
.nav-title{font-size:28px;font-weight:900;background:linear-gradient(135deg,var(--primary),var(--primary2));-webkit-background-clip:text;color:transparent}
.user-menu{position:relative}.user-pill{display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:999px;background:#fff;border:1px solid var(--border);cursor:pointer}.user-dropdown{position:absolute;top:calc(100% + 10px);right:0;width:300px;background:#fff;border:1px solid var(--border);border-radius:20px;box-shadow:var(--shadow);padding:16px;display:none;z-index:20}.user-dropdown.show{display:block}.user-dropdown-title{font-size:14px;color:var(--muted);margin-bottom:6px}.user-dropdown-name{font-size:20px;font-weight:800;margin-bottom:16px;overflow-wrap:anywhere;word-break:break-word}.user-dropdown .input{margin-bottom:12px}.user-dropdown-actions{display:grid;grid-template-columns:1fr;gap:10px}.current-language{font-size:13px;color:var(--muted)}
.layout{max-width:1380px;margin:0 auto;padding:24px}
.view{display:none}
.view.active{display:block}
.hero{max-width:1000px;margin:10px auto 0;text-align:center}.hero h2{font-size:42px;margin:0 0 10px}.hero p{color:var(--muted);font-size:18px;margin:0 0 26px}
.grid3{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-top:28px}
.card{background:var(--card);border-radius:var(--radius);box-shadow:var(--shadow);padding:24px}
.drop{padding:40px;text-align:center;border:2px dashed #c7d2fe;background:#fff;border-radius:28px}.drop.drag{border-color:#6366f1;background:#f8faff}
.drop h3{margin:10px 0 8px;font-size:26px}.drop p{margin:0 0 18px;color:var(--muted)}
.feature h4{margin:12px 0 6px}.feature p{margin:0;color:var(--muted);font-size:14px;line-height:1.6}
.result-layout,.mail-layout,.mail-analysis-layout{display:grid;gap:22px;align-items:start}
.result-layout{grid-template-columns:300px 1fr}.mail-layout{grid-template-columns:260px 1fr 1.1fr}.mail-analysis-layout{grid-template-columns:360px 1fr}
.result-header,.mail-top{display:flex;align-items:center;gap:14px;margin-bottom:20px}
.back-btn{border:0;background:transparent;font-weight:800;color:#475569}
.doc-box,.sidebar,.mail-list,.mail-reader,.analysis-box{background:var(--card);border-radius:24px;box-shadow:var(--shadow)}
.doc-box,.analysis-box,.mail-reader{padding:22px}
.mail-reader{height:700px;min-height:700px;max-height:700px;overflow:hidden;display:flex;flex-direction:column}
.mail-reader-content{flex:1;min-height:0;overflow:auto;padding-right:6px}
.read-body{white-space:pre-wrap;line-height:1.8;color:#1e293b;overflow-wrap:anywhere;word-break:break-word}
.reader-header{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap}
.reader-actions{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.small-btn{border:1px solid var(--border);background:#fff;color:var(--text);border-radius:14px;padding:12px 16px;font-weight:800}
.sidebar{padding:18px;position:sticky;top:100px}.sidebar .menu-btn{width:100%;margin-bottom:12px;text-align:left;background:#f8fbff;border:1px solid var(--border)}
.sidebar .menu-btn.active,.sidebar .menu-btn:hover{background:linear-gradient(135deg,#dbeafe,#ede9fe)}
.mail-list{overflow:hidden}.mail-search{padding:16px;border-bottom:1px solid #eef2ff}.mail-search input{width:100%;border:1px solid var(--border);border-radius:14px;padding:14px 16px}
.message-list{max-height:700px;overflow:auto}.message-item{padding:18px 18px;border-bottom:1px solid #eef2ff;cursor:pointer}.message-item:hover,.message-item.active{background:#f8fbff}.message-item h4{margin:6px 0;font-size:18px}.message-item p{margin:0;color:var(--muted);font-size:14px;line-height:1.5}
.row{display:flex;align-items:center;justify-content:space-between;gap:12px}.muted{color:var(--muted)}
.big-actions{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px}.mail-action{color:#fff;padding:22px;border-radius:18px;text-align:left}.mail-action.translate{background:linear-gradient(135deg,#3b82f6,var(--primary2))}.mail-action.analyze{background:linear-gradient(135deg,#ec4899,#7c3aed)}
.mail-action strong{display:block;font-size:28px;margin-bottom:6px}
.attach{margin-top:18px;padding-top:16px;border-top:1px solid #eef2ff}.attach-item{display:flex;gap:12px;align-items:center;padding:14px;background:#f8fbff;border-radius:16px;margin-top:10px}
.empty{padding:70px 24px;text-align:center;color:var(--muted)}
.empty .icon{width:70px;height:70px;border-radius:50%;background:#eef2ff;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:30px}
.results-area .card{margin-bottom:16px}.summary{background:#fcf7ff;border-radius:16px;padding:18px;line-height:1.7}.point{display:flex;gap:12px;align-items:flex-start;background:#f8fbff;padding:14px 16px;border-radius:14px;margin-top:10px}.point-num{width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#a21caf);color:#fff;font-size:12px;font-weight:800;display:flex;align-items:center;justify-content:center;flex:0 0 auto}
.top-tools{display:flex;gap:12px;flex-wrap:wrap}.link-btn{display:inline-flex;align-items:center;gap:8px;padding:12px 16px;border-radius:14px;border:1px solid var(--border);background:#fff;text-decoration:none;color:var(--text);font-weight:700}
.status{font-size:14px;color:var(--muted)}
@media(max-width:1200px){.mail-layout,.mail-analysis-layout,.result-layout{grid-template-columns:1fr}.sidebar{position:static}}
@media(max-width:780px){.grid3,.big-actions{grid-template-columns:1fr}.nav{padding:0 14px}.layout{padding:14px}.hero h2{font-size:30px}.nav-title{font-size:22px}}
</style>
</head>
<body>
<div class="auth-shell" id="auth-page">
  <div class="auth-card">
    <div class="brand">
      <div class="brand-icon">✉</div>
      <div><h1>DocuTranslate</h1><p>Translate documents and analyze Gmail inbox with AI</p></div>
    </div>
    <div class="tabs">
      <button class="active" id="tab-login" onclick="switchTab('login')">Login</button>
      <button id="tab-register" onclick="switchTab('register')">Register</button>
    </div>
    <div class="error" id="auth-error"></div>
    <div id="login-form">
      <div class="form-group"><label>Email</label><input class="input" id="login-email" type="email"></div>
      <div class="form-group"><label>Password</label><input class="input" id="login-password" type="password"></div>
      <button class="primary-btn" onclick="doLogin()">Login</button>
    </div>
    <div id="register-form" class="hidden">
      <div class="form-group"><label>Name</label><input class="input" id="reg-name" type="text"></div>
      <div class="form-group"><label>Email</label><input class="input" id="reg-email" type="email"></div>
      <div class="form-group"><label>Password</label><input class="input" id="reg-password" type="password"></div>
      <div class="form-group"><label>Native language</label>
        <select class="input" id="reg-language">
          <option>Russian</option><option>Ukrainian</option><option>English</option><option>German</option><option>French</option><option>Spanish</option><option>Italian</option><option>Dutch</option><option>Polish</option><option>Portuguese</option><option>Chinese</option><option>Japanese</option><option>Bulgarian</option><option>Slovak</option>
        </select>
      </div>
      <button class="primary-btn" onclick="doRegister()">Create account</button>
    </div>
  </div>
</div>

<div class="app" id="app-page">
  <div class="nav">
    <div class="nav-left"><div class="mini-icon">✉</div><div class="nav-title">DocuTranslate</div></div>
    <div class="nav-right">
      <button class="ghost-btn" onclick="openView('upload-view')">Upload</button>
      <button class="ghost-btn" onclick="openMailInbox()">My Mail</button>
      <div class="user-menu">
        <div class="user-pill" onclick="toggleUserMenu(event)"><span>👤</span><div><strong id="nav-username">User</strong><div class="current-language" id="nav-language">English</div></div></div>
        <div class="user-dropdown" id="user-dropdown">
          <div class="user-dropdown-title">Account</div>
          <div class="user-dropdown-name" id="user-menu-name">User</div>
          <div class="form-group" style="margin-bottom:12px">
            <label style="margin-bottom:8px">Application language</label>
            <select class="input" id="user-language-select">
              <option>Russian</option><option>Ukrainian</option><option>English</option><option>German</option><option>French</option><option>Spanish</option><option>Italian</option><option>Dutch</option><option>Polish</option><option>Portuguese</option><option>Chinese</option><option>Japanese</option><option>Bulgarian</option><option>Slovak</option>
            </select>
          </div>
          <div class="user-dropdown-actions">
            <button class="primary-btn" type="button" onclick="saveUserLanguage()">Save language</button>
            <button class="ghost-btn" type="button" onclick="closeUserMenu()">Close</button>
          </div>
        </div>
      </div>
      <button class="danger-btn" onclick="doLogout()">Log out</button>
    </div>
  </div>

  <div class="layout">
    <div class="view active" id="upload-view">
      <div class="hero">
        <h2>Upload a document for AI translation and analysis</h2>
        <p>Now your users can also connect Gmail and read only Inbox emails inside the same service.</p>
      </div>
      <div class="card drop" id="drop-zone" onclick="document.getElementById('file-input').click()">
        <div style="font-size:56px">📄</div>
        <h3>Click or drag a file here</h3>
        <p>Supported formats: JPG, PNG, PDF. Maximum 20 MB.</p>
        <button class="ghost-btn" onclick="event.stopPropagation();document.getElementById('file-input').click()">Choose file</button>
        <div class="status" id="upload-status" style="margin-top:14px"></div>
      </div>
      <input id="file-input" type="file" accept=".jpg,.jpeg,.png,.pdf" class="hidden" onchange="handleFileSelect(this)">
      <div class="grid3">
        <div class="card feature"><div style="font-size:36px">📤</div><h4>Upload</h4><p>Quick document upload with a clean modern interface.</p></div>
        <div class="card feature"><div style="font-size:36px">🔤</div><h4>Translate</h4><p>Full translation into the saved user language.</p></div>
        <div class="card feature"><div style="font-size:36px">📬</div><h4>My Mail</h4><p>Each user can connect a personal Google account and browse only Inbox emails.</p></div>
      </div>
    </div>

    <div class="view" id="results-view">
      <div class="result-header"><button class="back-btn" onclick="openView('upload-view')">← Back to Upload</button><h2 style="margin:0">Document Analysis</h2></div>
      <div class="result-layout">
        <div class="doc-box">
          <h3 style="margin-top:0">Original File</h3>
          <div id="doc-thumb" style="height:200px;border-radius:18px;background:#f8fbff;display:flex;align-items:center;justify-content:center;font-size:60px">📄</div>
          <div style="margin-top:16px;font-weight:700" id="doc-name"></div>
        </div>
        <div>
          <div class="big-actions">
            <button class="mail-action translate" onclick="doTranslateDocument()"><strong>Translate</strong><span>Get full translation to the saved language</span></button>
            <button class="mail-action analyze" onclick="doAnalyzeDocument()"><strong>Analyze</strong><span>Summary and key points of the document</span></button>
          </div>
          <div class="results-area" id="doc-result-area">
            <div class="card empty"><div class="icon">📄</div><h3>Choose an action</h3><p>Click Translate or Analyze.</p></div>
          </div>
        </div>
      </div>
    </div>

    <div class="view" id="mail-view">
      <div class="mail-layout">
        <div class="sidebar">
          <button class="menu-btn" onclick="openView('upload-view')">← Back to Upload</button>
          <button class="menu-btn" onclick="syncInbox()">↻ Sync Gmail</button>
          <button class="menu-btn active">📥 Inbox</button>
          <button class="menu-btn" id="connect-gmail-btn" onclick="connectGmail()">🔗 Connect Google</button>
          <button class="menu-btn" id="disconnect-gmail-btn" onclick="disconnectGmail()">⛔ Disconnect Google</button>
          <div class="card" style="padding:16px;margin-top:12px;box-shadow:none;border:1px solid var(--border)">
            <div class="status">Connected account</div>
            <div style="font-weight:800;margin-top:8px;word-break:break-word" id="gmail-email-label">Not connected</div>
          </div>
        </div>
        <div class="mail-list">
          <div class="mail-search"><input id="mail-search" placeholder="Search inbox emails..." oninput="debouncedSearchInbox()"></div>
          <div class="message-list" id="message-list"><div class="empty"><div class="icon">📬</div><h3>Inbox is empty</h3><p>Connect Gmail and sync your Inbox.</p></div></div>
        </div>
        <div class="mail-reader" id="mail-reader">
          <div class="empty"><div class="icon">✉</div><h3>Select an email</h3><p>Click on an inbox email to open it.</p></div>
        </div>
      </div>
    </div>

    <div class="view" id="mail-analysis-view">
      <div class="mail-top"><button class="back-btn" onclick="openView('mail-view')">← Back to Inbox</button><h2 style="margin:0">Email Analysis</h2></div>
      <div class="mail-analysis-layout">
        <div class="doc-box" id="analysis-email-card"></div>
        <div>
          <div class="big-actions">
            <button class="mail-action translate" onclick="translateCurrentEmail()"><strong>Translate</strong><span>Full translation to user language</span></button>
            <button class="mail-action analyze" onclick="analyzeCurrentEmail()"><strong>Analyze</strong><span>Summary and key points of the email</span></button>
          </div>
          <div class="analysis-box" id="mail-analysis-area">
            <div class="empty"><div class="icon">✉</div><h3>Choose an action</h3><p>Click one of the buttons above to translate or analyze this email.</p></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
let currentUser=null;
let selectedLanguage='English';
let uploadedFile=null;
let docTranslateCache=null;
let docAnalyzeCache=null;
let inboxMessages=[];
let selectedMessage=null;
let emailTranslateCache={};
let emailAnalyzeCache={};
let searchTimer=null;

function esc(s){return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');}
function openView(id){document.querySelectorAll('.view').forEach(v=>v.classList.remove('active'));document.getElementById(id).classList.add('active');}
function showAuth(){document.getElementById('auth-page').style.display='flex';document.getElementById('app-page').style.display='none';}
function showApp(){document.getElementById('auth-page').style.display='none';document.getElementById('app-page').style.display='block';document.getElementById('nav-username').textContent=currentUser.name;document.getElementById('user-menu-name').textContent=currentUser.name;selectedLanguage=currentUser.language||'English';document.getElementById('nav-language').textContent=selectedLanguage;document.getElementById('user-language-select').value=selectedLanguage;openView('upload-view');handleOAuthMessage();}
function switchTab(tab){document.getElementById('tab-login').classList.toggle('active',tab==='login');document.getElementById('tab-register').classList.toggle('active',tab==='register');document.getElementById('login-form').classList.toggle('hidden',tab!=='login');document.getElementById('register-form').classList.toggle('hidden',tab!=='register');document.getElementById('auth-error').style.display='none';}
function showAuthError(msg){const el=document.getElementById('auth-error');el.textContent=msg;el.style.display='block';}
async function checkAuth(){try{const d=await (await fetch('api/auth.php?action=check')).json();if(d.loggedIn){currentUser={name:d.name,language:d.language};showApp();}else showAuth();}catch(e){showAuth();}}
async function doLogin(){const email=document.getElementById('login-email').value.trim();const password=document.getElementById('login-password').value;if(!email||!password){showAuthError('Enter email and password');return;}const r=await fetch('api/auth.php?action=login',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({email,password})});const d=await r.json();if(!d.success){showAuthError(d.error||'Login failed');return;}currentUser={name:d.name,language:d.language};showApp();}
async function doRegister(){const name=document.getElementById('reg-name').value.trim();const email=document.getElementById('reg-email').value.trim();const password=document.getElementById('reg-password').value;const language=document.getElementById('reg-language').value;if(!name||!email||!password){showAuthError('Fill all fields');return;}const r=await fetch('api/auth.php?action=register',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({name,email,password,language})});const d=await r.json();if(!d.success){showAuthError(d.error||'Register failed');return;}currentUser={name:d.name,language:d.language};showApp();}
async function doLogout(){await fetch('api/auth.php?action=logout');location.href='index.php';}
function toggleUserMenu(event){event.stopPropagation();document.getElementById('user-dropdown').classList.toggle('show');document.getElementById('user-language-select').value=selectedLanguage||'English';}
function closeUserMenu(){document.getElementById('user-dropdown').classList.remove('show');}
async function saveUserLanguage(){const language=document.getElementById('user-language-select').value;const r=await fetch('api/auth.php?action=update_language',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({language})});const d=await r.json();if(!d.success){alert(d.error||'Failed to update language');return;}currentUser={name:d.name,language:d.language};selectedLanguage=d.language||'English';docTranslateCache=null;docAnalyzeCache=null;emailTranslateCache={};emailAnalyzeCache={};document.getElementById('nav-language').textContent=selectedLanguage;document.getElementById('user-language-select').value=selectedLanguage;closeUserMenu();alert('Language saved: '+selectedLanguage);}

const dropZone=document.getElementById('drop-zone');
dropZone.addEventListener('dragover',e=>{e.preventDefault();dropZone.classList.add('drag')});
dropZone.addEventListener('dragleave',()=>dropZone.classList.remove('drag'));
dropZone.addEventListener('drop',e=>{e.preventDefault();dropZone.classList.remove('drag');if(e.dataTransfer.files[0])uploadFile(e.dataTransfer.files[0]);});
function handleFileSelect(input){if(input.files[0])uploadFile(input.files[0]);}
async function uploadFile(file){if(!['image/jpeg','image/png','image/jpg','application/pdf'].includes(file.type)){alert('Only JPG, PNG, PDF');return;}if(file.size>20*1024*1024){alert('Maximum size is 20 MB');return;}document.getElementById('upload-status').textContent='Uploading...';const fd=new FormData();fd.append('file',file);const r=await fetch('api/analyze.php?action=upload',{method:'POST',body:fd});const d=await r.json();document.getElementById('upload-status').textContent='';if(!d.success){alert(d.error||'Upload failed');return;}uploadedFile={filename:d.filename,original_name:d.original_name,mime_type:d.mime_type,file};docTranslateCache=null;docAnalyzeCache=null;prepareDocumentCard(file);openView('results-view');}
function prepareDocumentCard(file){document.getElementById('doc-name').textContent=file.name;const thumb=document.getElementById('doc-thumb');if(file.type.startsWith('image/')){const rd=new FileReader();rd.onload=e=>thumb.innerHTML=`<img src="${e.target.result}" style="max-width:100%;max-height:100%;border-radius:18px">`;rd.readAsDataURL(file);}else{thumb.innerHTML='📄';}document.getElementById('doc-result-area').innerHTML='<div class="card empty"><div class="icon">📄</div><h3>Choose an action</h3><p>Click Translate or Analyze.</p></div>';}
async function doTranslateDocument(){if(!uploadedFile)return;if(docTranslateCache){renderDocumentTranslate(docTranslateCache);return;}document.getElementById('doc-result-area').innerHTML='<div class="card empty"><div class="icon">⏳</div><h3>Translating</h3><p>Please wait...</p></div>';const r=await fetch('api/analyze.php?action=translate',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({filename:uploadedFile.filename,language:selectedLanguage})});const d=await r.json();if(!d.success){alert(d.error||'Translate failed');return;}docTranslateCache=d.result;renderDocumentTranslate(d.result);}
function renderDocumentTranslate(data){document.getElementById('doc-result-area').innerHTML=`<div class="card"><h3 style="margin-top:0">Short description</h3><div class="summary">${esc(data.short_description).replace(/\n/g,'<br>')}</div></div><div class="card"><div class="row"><h3 style="margin:0">Translation</h3><button class="ghost-btn" onclick="navigator.clipboard.writeText(${JSON.stringify((data.translation||''))})">Copy</button></div><div class="summary" style="margin-top:14px">${esc(data.translation).replace(/\n/g,'<br>')}</div></div>`;}
async function doAnalyzeDocument(){if(!uploadedFile)return;if(docAnalyzeCache){renderDocumentAnalyze(docAnalyzeCache);return;}document.getElementById('doc-result-area').innerHTML='<div class="card empty"><div class="icon">⏳</div><h3>Analyzing</h3><p>Please wait...</p></div>';const r=await fetch('api/analyze.php?action=analyze',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({filename:uploadedFile.filename,language:selectedLanguage})});const d=await r.json();if(!d.success){alert(d.error||'Analyze failed');return;}docAnalyzeCache=d.result;renderDocumentAnalyze(d.result);}
function renderDocumentAnalyze(data){const points=(data.key_points||[]).map((p,i)=>`<div class="point"><div class="point-num">${i+1}</div><div>${esc(p)}</div></div>`).join('');document.getElementById('doc-result-area').innerHTML=`<div class="card"><h3 style="margin-top:0">Summary</h3><div class="summary">${esc(data.summary).replace(/\n/g,'<br>')}</div></div><div class="card"><h3 style="margin-top:0">Key Points</h3>${points||'<div class="summary">No key points found.</div>'}</div>`;}

function connectGmail(){window.location.href='api/gmail_connect.php';}
async function disconnectGmail(){if(!confirm('Disconnect Gmail account?'))return;const r=await fetch('api/gmail_disconnect.php');const d=await r.json();if(d.success){document.getElementById('gmail-email-label').textContent='Not connected';selectedMessage=null;inboxMessages=[];renderInbox();renderMailReaderEmpty();alert('Gmail disconnected');}}
function openMailInbox(){openView('mail-view');syncInbox();}
function debouncedSearchInbox(){clearTimeout(searchTimer);searchTimer=setTimeout(syncInbox,350);}
async function syncInbox(){openView('mail-view');document.getElementById('message-list').innerHTML='<div class="empty"><div class="icon">⏳</div><h3>Syncing Inbox</h3><p>Please wait...</p></div>';const q=document.getElementById('mail-search').value.trim();try{const r=await fetch('api/gmail_inbox.php?maxResults=15&q='+encodeURIComponent(q));const d=await r.json();if(!d.success){throw new Error(d.error||'Failed to load inbox');}document.getElementById('gmail-email-label').textContent=d.connected_email||'Connected';inboxMessages=d.messages||[];renderInbox();if(selectedMessage){const found=inboxMessages.find(m=>m.id===selectedMessage.id);if(found)loadMessage(found.id);} }catch(e){document.getElementById('message-list').innerHTML=`<div class="empty"><div class="icon">📭</div><h3>Inbox not available</h3><p>${esc(e.message)}</p></div>`;document.getElementById('gmail-email-label').textContent='Not connected';renderMailReaderEmpty();}}
function renderInbox(){const box=document.getElementById('message-list');if(!inboxMessages.length){box.innerHTML='<div class="empty"><div class="icon">📬</div><h3>No inbox emails</h3><p>Connect Gmail and sync Inbox.</p></div>';return;}box.innerHTML=inboxMessages.map(m=>`<div class="message-item ${selectedMessage&&selectedMessage.id===m.id?'active':''}" onclick="loadMessage('${m.id}')"><div class="row"><strong>${esc(m.from)}</strong><span class="muted">${esc(m.date)}</span></div><h4>${esc(m.subject)}</h4><p>${esc(m.snippet)}</p>${m.has_attachments?'<div class="muted" style="margin-top:10px">📎 Attachment</div>':''}</div>`).join('');}
async function loadMessage(id){const r=await fetch('api/gmail_message.php?id='+encodeURIComponent(id));const d=await r.json();if(!d.success){alert(d.error||'Failed to load email');return;}selectedMessage=d.message;renderInbox();renderMailReader(d.message);renderAnalysisEmailCard(d.message);}
function closeMailReader(){selectedMessage=null;renderInbox();renderMailReaderEmpty();}
function renderMailReaderEmpty(){document.getElementById('mail-reader').innerHTML='<div class="empty"><div class="icon">✉</div><h3>Select an email</h3><p>Click on an inbox email to open it.</p></div>';}
function renderMailReader(message){const attachments=(message.attachments||[]).map(a=>`<div class="attach-item"><div style="font-size:28px">📎</div><div><div style="font-weight:700">${esc(a.filename)}</div><div class="muted">${esc(a.mime_type)}</div></div></div>`).join('');document.getElementById('mail-reader').innerHTML=`<div class="reader-header"><div><h2 style="margin:0 0 10px">${esc(message.subject)}</h2><div class="muted"><strong>${esc(message.from)}</strong> • ${esc(message.date)}</div></div><div class="reader-actions"><button class="mail-action translate" style="padding:14px 18px;border-radius:14px;font-size:16px" onclick="openMailAnalysisView()">Analyze Email</button><button class="small-btn" onclick="closeMailReader()">Close</button></div></div><div class="mail-reader-content"><div class="read-body" style="margin-top:28px">${esc(message.body).replace(/\n/g,'<br>')}</div><div class="attach"><h3 style="margin:0 0 8px">Attachments</h3>${attachments||'<div class="muted">No attachments</div>'}</div></div>`;}
function renderAnalysisEmailCard(message){const attachments=(message.attachments||[]).map(a=>`<div class="attach-item"><div style="font-size:28px">📎</div><div><div style="font-weight:700">${esc(a.filename)}</div><div class="muted">${esc(a.mime_type)}</div></div></div>`).join('');document.getElementById('analysis-email-card').innerHTML=`<h3 style="margin-top:0">Original Email</h3><div style="font-size:22px;font-weight:800;margin:18px 0 10px;overflow-wrap:anywhere;word-break:break-word">${esc(message.subject)}</div><div class="muted" style="overflow-wrap:anywhere;word-break:break-word"><strong>${esc(message.from)}</strong></div><div class="muted" style="margin-top:6px">${esc(message.date)}</div><div style="margin-top:18px;padding-top:18px;border-top:1px solid #eef2ff" class="read-body">${esc(message.body).replace(/\n/g,'<br>')}</div><div class="attach"><div class="muted">${(message.attachments||[]).length} Attachment${(message.attachments||[]).length===1?'':'s'}</div>${attachments||''}</div>`;document.getElementById('mail-analysis-area').innerHTML='<div class="empty"><div class="icon">✉</div><h3>Choose an action</h3><p>Click one of the buttons above to translate or analyze this email.</p></div>';}
function openMailAnalysisView(){if(!selectedMessage){alert('Select an email first');return;}renderAnalysisEmailCard(selectedMessage);openView('mail-analysis-view');}
async function translateCurrentEmail(){if(!selectedMessage)return;const key=selectedMessage.id;if(emailTranslateCache[key]){renderEmailTranslate(emailTranslateCache[key]);return;}document.getElementById('mail-analysis-area').innerHTML='<div class="empty"><div class="icon">⏳</div><h3>Translating email</h3><p>Please wait...</p></div>';const fd=new FormData();fd.append('mode','translate');fd.append('language',selectedLanguage);fd.append('text',selectedMessage.body||selectedMessage.snippet||'');const r=await fetch('api/gmail_analyze.php',{method:'POST',body:fd});const d=await r.json();if(!d.success){alert(d.error||'Translation failed');return;}emailTranslateCache[key]=d.result;renderEmailTranslate(d.result);}
function renderEmailTranslate(data){document.getElementById('mail-analysis-area').innerHTML=`<div class="card"><h3 style="margin-top:0">Short description</h3><div class="summary">${esc(data.short_description).replace(/\n/g,'<br>')}</div></div><div class="card"><div class="row"><h3 style="margin:0">Translation</h3><button class="ghost-btn" onclick="navigator.clipboard.writeText(${JSON.stringify((data.translation||''))})">Copy</button></div><div class="summary" style="margin-top:14px">${esc(data.translation).replace(/\n/g,'<br>')}</div></div>`;}
async function analyzeCurrentEmail(){if(!selectedMessage)return;const key=selectedMessage.id;if(emailAnalyzeCache[key]){renderEmailAnalyze(emailAnalyzeCache[key]);return;}document.getElementById('mail-analysis-area').innerHTML='<div class="empty"><div class="icon">⏳</div><h3>Analyzing email</h3><p>Please wait...</p></div>';const fd=new FormData();fd.append('mode','analyze');fd.append('language',selectedLanguage);fd.append('text',selectedMessage.body||selectedMessage.snippet||'');const r=await fetch('api/gmail_analyze.php',{method:'POST',body:fd});const d=await r.json();if(!d.success){alert(d.error||'Analysis failed');return;}emailAnalyzeCache[key]=d.result;renderEmailAnalyze(d.result);}
function renderEmailAnalyze(data){const points=(data.key_points||[]).map((p,i)=>`<div class="point"><div class="point-num">${i+1}</div><div>${esc(p)}</div></div>`).join('');document.getElementById('mail-analysis-area').innerHTML=`<div class="card"><h3 style="margin-top:0">Summary</h3><div class="summary">${esc(data.summary).replace(/\n/g,'<br>')}</div></div><div class="card"><h3 style="margin-top:0">Key Points</h3>${points||'<div class="summary">No key points found.</div>'}</div>`;}
function handleOAuthMessage(){const params=new URLSearchParams(location.search);const gmail=params.get('gmail');if(!gmail)return;const map={connected:'Gmail connected successfully',denied:'Google access was denied',state_error:'OAuth state error',code_error:'Google did not return the code',token_error:'Failed to get Google token',userinfo_error:'Failed to read Google account',login_required:'Login first'};alert(map[gmail]||gmail);history.replaceState({},document.title,'index.php');}

document.addEventListener('keydown',e=>{if(e.key==='Escape')closeUserMenu();if(e.key==='Enter'&&document.getElementById('auth-page').style.display!=='none'){if(!document.getElementById('login-form').classList.contains('hidden'))doLogin();else doRegister();}});
document.addEventListener('click',e=>{const menu=document.querySelector('.user-menu');if(menu&&!menu.contains(e.target))closeUserMenu();});
checkAuth();
</script>
</body>
</html>

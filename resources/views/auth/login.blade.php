@extends('layouts.app')

@section('title', 'Log in — NASA Zoom Explorer')

@section('content')
<style>
  :root{
    --bg:#050816;
    --text:#e6edf6;
    --muted:#a7b0c0;
    --accent-1:#6366f1;
    --accent-2:#38bdf8;
    --accent-3:#06b6d4;
    --danger:#ef4444;
    --surface:rgba(10,14,25,.6);
    --border:rgba(148,163,184,.22);
    --ring:rgba(56,189,248,.32);
    --radius:16px;
    --radius-sm:12px;
    --shadow:0 24px 80px rgba(0,0,0,.55);
    --blur:blur(14px) saturate(160%);
  }

  /* ====== Cosmic background (fixed) ====== */
  .space-bg{
    position: fixed; inset: 0; z-index: 0; pointer-events: none; overflow: hidden;
    background:
      radial-gradient(1200px 700px at 85% 10%, rgba(56,189,248,.08), transparent 60%),
      radial-gradient(900px 500px at 10% 90%, rgba(99,102,241,.08), transparent 60%),
      radial-gradient(1000px 600px at 50% 50%, rgba(6,182,212,.05), transparent 70%),
      linear-gradient(180deg, var(--bg), #030614 65%, #02040d);
  }
  .bg-nebula{
    position:absolute; inset:-35%;
    background:
      radial-gradient(700px 420px at 78% 18%, rgba(56,189,248,.22), transparent 60%),
      radial-gradient(620px 380px at 16% 84%, rgba(99,102,241,.22), transparent 62%),
      radial-gradient(820px 540px at 50% 52%, rgba(6,182,212,.16), transparent 70%);
    filter: blur(42px) saturate(120%); opacity:.55; animation: nebulaPulse 18s ease-in-out infinite alternate;
  }
  .bg-stars{ position:absolute; inset:-40% -40%; background-repeat:repeat; }
  .layer1{ background-image:
      radial-gradient(1.6px 1.6px at 40px 60px,#fff,transparent 38%),
      radial-gradient(1.4px 1.4px at 220px 120px,#fff,transparent 40%),
      radial-gradient(1.4px 1.4px at 340px 200px,#fff,transparent 40%),
      radial-gradient(1.2px 1.2px at 520px 50px,#fff,transparent 40%);
    background-size:600px 600px; animation: drift1 160s linear infinite; opacity:.75; }
  .layer2{ background-image:
      radial-gradient(1.2px 1.2px at 100px 80px,#fff,transparent 40%),
      radial-gradient(1.2px 1.2px at 300px 150px,#fff,transparent 40%),
      radial-gradient(1px 1px at 520px 40px,#fff,transparent 40%),
      radial-gradient(1px 1px at 420px 260px,#fff,transparent 40%);
    background-size:800px 800px; animation: drift2 220s linear infinite reverse; opacity:.55; }
  .layer3{ background-image:
      radial-gradient(1px 1px at 90px 140px,#fff,transparent 40%),
      radial-gradient(1px 1px at 260px 240px,#fff,transparent 40%),
      radial-gradient(1px 1px at 380px 120px,#fff,transparent 40%),
      radial-gradient(1px 1px at 560px 300px,#fff,transparent 40%);
    background-size:1100px 1100px; animation: drift3 300s linear infinite; opacity:.42; }
  @keyframes drift1{to{transform:translate3d(240px,190px,0)}}
  @keyframes drift2{to{transform:translate3d(-260px,-210px,0)}}
  @keyframes drift3{to{transform:translate3d(300px,240px,0)}}
  @keyframes nebulaPulse{from{transform:scale(1);opacity:.55}to{transform:scale(1.06);opacity:.7}}

  /* shooting stars */
  .meteor{
    position:absolute; width:2px; height:2px; opacity:0; transform: rotate(30deg);
    filter: drop-shadow(0 0 6px #fff);
    animation: shoot var(--dur,8s) ease-in-out var(--delay,0s) infinite;
  }
  .meteor::after{
    content:""; position:absolute; width:180px; height:2px; right:-8px; top:0;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.95), rgba(56,189,248,0));
  }
  .m1{ top:14%; left:-8%; --delay: 1.8s; --dur: 10s }
  .m2{ top:32%; left:-10%; --delay: 5s;   --dur: 11.2s }
  .m3{ top:62%; left:-12%; --delay: 8.2s; --dur: 12s }
  @keyframes shoot{0%,60%{opacity:0;transform:translate3d(0,0,0) rotate(30deg)}61%{opacity:1}75%{transform:translate3d(1200px,420px,0) rotate(30deg);opacity:0}100%{opacity:0}}

  /* ====== Foreground ====== */
  .page-fore{ position:relative; z-index:1; }
  .auth-wrap{ min-height: 100svh; display:grid; place-items:center; padding: 28px 14px; color: var(--text); }

  /* ====== Card (3D tilt + glass) ====== */
  .card{
    width:min(980px, 94vw); display:grid; grid-template-columns: 1.05fr .95fr; gap:22px;
    padding:26px; border-radius:var(--radius); border:1px solid var(--border);
    background:linear-gradient(180deg, rgba(255,255,255,.09), rgba(255,255,255,.03));
    backdrop-filter: var(--blur); -webkit-backdrop-filter: var(--blur);
    box-shadow: var(--shadow); transform-style: preserve-3d;
    transition: transform .2s ease, box-shadow .25s ease, border-color .25s ease;
    --rx: 0deg; --ry: 0deg;
    transform: rotateX(var(--rx)) rotateY(var(--ry));
  }
  .card:hover{ box-shadow: 0 36px 120px rgba(2,8,23,.65); border-color: rgba(255,255,255,.28) }
  @media (max-width: 900px){ .card{ grid-template-columns: 1fr; padding:22px } }

  .panel-art{
    position:relative; border-radius:var(--radius-sm); border:1px solid var(--border); overflow:hidden;
    background: radial-gradient(900px 500px at -10% 110%, rgba(56,189,248,.12), transparent 60%),
               linear-gradient(180deg, rgba(3,7,18,.82), rgba(2,6,23,.68));
    min-height: 420px; display:flex; flex-direction:column; justify-content:space-between;
  }
  .brand{ display:flex; align-items:center; gap:14px; padding:18px }
  .logo{
    width:60px; height:60px; border-radius:12px; overflow:visible; isolation:isolate;
    /* No background — show PNG only */
    background: transparent;
  }
  .logo img{
    width:100%; height:100%; object-fit:contain; display:block;
    /* Make black pixels visually disappear on dark bg while keeping colors vivid */
    mix-blend-mode: screen;              /* treats black as transparent over dark sky */
    filter: saturate(1.08) brightness(1.12) drop-shadow(0 10px 28px rgba(56,189,248,.35));
    background: transparent !important;  /* ensure no fill */
  }
  .brand h1{ margin:0; line-height:1.05; letter-spacing:-.02em; font-size:1.05rem; font-weight:900 }
  .brand .sub{ font-size:.78rem; color:var(--muted); margin-top:2px }

  /* orb + ring */
  .planet{
    position:absolute; right:-30px; bottom:-30px; width: 240px; height: 240px; border-radius:50%;
    background: radial-gradient(circle at 30% 30%, #b7f9ff 0%, #82e6ff 25%, #5fb9ff 55%, #6376ff 80%, #3342c6 100%);
    box-shadow: inset -30px -40px 70px rgba(0,0,0,.35), 0 30px 120px rgba(99,102,241,.35);
    animation: float 9s ease-in-out infinite;
  }
  .ring{
    position:absolute; width:360px; height:360px; left:-60px; top:-30px; border-radius:50%;
    border:2px solid rgba(255,255,255,.08); transform: rotate(-18deg); animation: spin 28s linear infinite;
  }
  @keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}
  @keyframes spin{to{transform:rotate(342deg)}}

  .panel-form{
    position:relative; border-radius:var(--radius-sm); border:1px solid var(--border);
    background: linear-gradient(180deg, rgba(8,12,24,.88), rgba(6,10,20,.72)); padding: 26px 24px;
  }
  .heading{ margin:2px 0 14px }
  .heading h2{ margin:0 0 6px; font-weight:900; letter-spacing:-.02em; font-size:1.45rem }
  .heading p{ margin:0; color:var(--muted); font-size:.92rem }

  .badge{
    display:inline-flex; align-items:center; gap:8px; font-weight:800; letter-spacing:.02em;
    color:#082032; background:linear-gradient(135deg, var(--accent-2), var(--accent-1));
    border-radius:999px; padding:6px 10px; box-shadow: 0 14px 36px rgba(56,189,248,.28);
    margin-bottom:10px;
  }

  .field{ margin-top:14px }
  label{ display:block; font-size:.9rem; color:var(--muted); margin-bottom:6px }
  .input{
    width:100%; border-radius:12px; border:1px solid var(--border); background: rgba(255,255,255,.03); color:var(--text);
    padding: 12px 14px; font-size:.96rem; outline:none; transition: box-shadow .2s, border-color .2s, background .2s;
  }
  .input:focus{ border-color: var(--accent-2); box-shadow: 0 0 0 4px var(--ring); background: rgba(255,255,255,.06) }

  .error{ margin-top:8px; color:var(--danger); font-size:.85rem }
  .error ul{ margin:6px 0 0 18px }

  .inline{ display:flex; align-items:center; justify-content:space-between; gap:10px; margin-top:14px }
  .check{ display:inline-flex; align-items:center; gap:10px; user-select:none; cursor:pointer; color:var(--muted); font-size:.92rem }
  .check input{
    appearance:none; width:18px; height:18px; border-radius:6px; border:1px solid var(--border);
    background:rgba(255,255,255,.04); position:relative; outline:none; cursor:pointer; transition: all .2s;
  }
  .check input:checked{ border-color:transparent; background: linear-gradient(135deg, var(--accent-1), var(--accent-3)); box-shadow: 0 0 0 3px var(--ring) }
  .check input:checked::after{ content:''; position:absolute; inset:0; margin:auto; width:10px; height:10px; border-radius:4px; background:white }

  .actions{ display:flex; align-items:center; justify-content:flex-end; gap:10px; margin-top:18px }
  .btn{
    display:inline-flex; align-items:center; gap:8px; border:0; cursor:pointer;
    border-radius:12px; padding: 11px 14px; font-weight:800; font-size:.95rem;
    transition: transform .06s ease, box-shadow .22s ease, opacity .2s ease;
  }
  .btn:active{ transform: translateY(1px) }
  .btn-ghost{ background:transparent; color:var(--text); border:1px solid var(--border) }
  .btn-ghost:hover{ background: rgba(255,255,255,.04) }
  .btn-primary{ background: linear-gradient(135deg, var(--accent-1), var(--accent-2)); color:#fff; box-shadow: 0 16px 40px rgba(56,189,248,.35) }
  .btn-primary:hover{ opacity:.96 }

  .micro{ margin-top:10px; color:var(--muted); font-size:.8rem; text-align:center }

  @media (prefers-reduced-motion: reduce){
    .bg-nebula,.layer1,.layer2,.layer3,.meteor,.planet,.ring,.card{ animation:none !important; transform:none!important }
  }
</style>

<!-- Space background with parallax stars & meteors -->
<div class="space-bg" aria-hidden="true">
  <div class="bg-nebula"></div>
  <div class="bg-stars layer1"></div>
  <div class="bg-stars layer2"></div>
  <div class="bg-stars layer3"></div>
  <div class="meteor m1"></div>
  <div class="meteor m2"></div>
  <div class="meteor m3"></div>
</div>

<div class="page-fore">
  <div class="auth-wrap">
    <div class="card" id="tilt">
      <!-- Left: Brand + artwork -->
      <section class="panel-art" aria-label="NASA Zoom Explorer brand">
        <div class="brand">
          <div class="logo" title="NASA logo">
            <img src="https://images.seeklogo.com/logo-png/19/2/nasa-logo-png_seeklogo-195796.png" alt="NASA Zoom Explorer Logo (PNG)">
          </div>
          <div>
            <span class="badge">ADMIN ACCESS ONLY</span>
            <h1>NASA Zoom Explorer</h1>
            <div class="sub">Secure portal for managing deep-zoom datasets.</div>
          </div>
        </div>

        <div class="planet" aria-hidden="true"></div>
        <div class="ring" aria-hidden="true"></div>
      </section>

      <!-- Right: Auth form -->
      <section class="panel-form" aria-label="Sign in form">
        <header class="heading">
          <h2>Log in to Mission Control</h2>
          <p>Only administrators can sign in to perform create, read, update, and delete (CRUD) operations on imagery datasets.</p>
        </header>

        @if (session('status'))
          <div class="micro" role="status">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}" novalidate>
          @csrf

          <div class="field">
            <label for="email">Email</label>
            <input id="email" class="input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@mission.nasa">
            @if ($errors->has('email'))
              <div class="error">
                <ul>
                  @foreach ($errors->get('email') as $msg)
                    <li>{{ $msg }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
          </div>

          <div class="field">
            <label for="password">Password</label>
            <input id="password" class="input" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
            @if ($errors->has('password'))
              <div class="error">
                <ul>
                  @foreach ($errors->get('password') as $msg)
                    <li>{{ $msg }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
          </div>

          <div class="inline">
           
            <!-- no forgot/register links -->
            <span style="min-width:1px"></span>
          </div>

          <div class="actions">
            <a class="btn btn-ghost" href="{{ Route::has('home') ? route('home') : url('/') }}">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="margin-right:2px">
                <path d="m3 9 9-7 9 7v10a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-4H9v4a2 2 0 0 1-2 2H3z"/>
              </svg>
              Back to Home
            </a>

            <button type="submit" class="btn btn-primary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="margin-right:2px">
                <path d="M14 4.5c2.5.5 5.5 3.5 6 6L13.5 17c-2.5-.5-5.5-3.5-6-6L14 4.5Z"/>
                <path d="M14 4.5c-1 2-1.5 4.5-1 7"/><path d="M5 19l2-2"/><path d="M3.5 20.5c2 0 3-1 3-3-2 0-3 1-3 3Z"/>
              </svg>
              Log in
            </button>
          </div>

          <div class="micro">Restricted access: Admins only. Unauthorized access is prohibited.</div>
        </form>
      </section>
    </div>
  </div>
</div>

<script>
  // Subtle 3D tilt
  (function(){
    const el = document.getElementById('tilt');
    if(!el) return;
    function move(e){
      const r = el.getBoundingClientRect();
      const px = (e.clientX - r.left) / r.width - 0.5;
      const py = (e.clientY - r.top) / r.height - 0.5;
      el.style.setProperty('--rx', (py * -6).toFixed(2) + 'deg');
      el.style.setProperty('--ry', (px *  8).toFixed(2) + 'deg');
    }
    el.addEventListener('mousemove', move, {passive:true});
    el.addEventListener('mouseleave', ()=>{ el.style.setProperty('--rx','0deg'); el.style.setProperty('--ry','0deg'); }, {passive:true});
  })();
</script>
@endsection

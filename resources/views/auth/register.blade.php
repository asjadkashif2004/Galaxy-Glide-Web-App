@extends('layouts.app')

@section('title', 'Register — NASA Zoom Explorer')

@section('content')
<style>
  /* ===================== Global Space Backdrop (page-wide) ===================== */
  :root{
    --bg:#050816;
    --bg-alt:#0b1220;
    --text:#e6edf6;
    --muted:#a7b0c0;
    --accent-1:#6366f1;     /* indigo-500 */
    --accent-2:#38bdf8;     /* sky-400  */
    --accent-3:#06b6d4;     /* cyan-500 */
    --danger:#ef4444;
    --surface:rgba(10,14,25,.6);
    --border:rgba(148,163,184,.22);
    --ring:rgba(56,189,248,.32);
    --radius:16px;
    --radius-sm:12px;
    --shadow:0 20px 80px rgba(0,0,0,.55);
    --blur:blur(14px) saturate(160%);
  }

  .space-bg{
    position: fixed; inset: 0; z-index: 0; pointer-events: none; overflow: hidden;
    background:
      radial-gradient(1200px 700px at 85% 10%, rgba(56,189,248,.08), transparent 60%),
      radial-gradient(900px 500px at 10% 90%, rgba(99,102,241,.08), transparent 60%),
      radial-gradient(1000px 600px at 50% 50%, rgba(6,182,212,.05), transparent 70%),
      linear-gradient(180deg, var(--bg), #030614 65%, #02040d);
  }
  .space-bg .nebula{
    position:absolute; inset:-35%;
    background:
      radial-gradient(700px 420px at 78% 18%, rgba(56,189,248,.22), transparent 60%),
      radial-gradient(620px 380px at 16% 84%, rgba(99,102,241,.22), transparent 62%),
      radial-gradient(820px 540px at 50% 52%, rgba(6,182,212,.16), transparent 70%);
    filter: blur(40px) saturate(120%);
    opacity:.55; animation: nebulaPulse 18s ease-in-out infinite alternate;
  }
  .space-bg .stars{ position:absolute; inset:0; background-repeat:repeat; opacity:.75;
    animation: starDrift var(--dur,160s) linear infinite; }
  .space-bg .layer1{ --dur:160s; background-image:
      radial-gradient(1.6px 1.6px at 40px 60px,#fff,transparent 38%),
      radial-gradient(1.4px 1.4px at 220px 120px,#fff,transparent 40%),
      radial-gradient(1.4px 1.4px at 340px 200px,#fff,transparent 40%),
      radial-gradient(1.2px 1.2px at 520px 50px,#fff,transparent 40%); background-size:600px 600px; }
  .space-bg .layer2{ --dur:220s; opacity:.6; animation-direction:reverse; background-image:
      radial-gradient(1.2px 1.2px at 100px 80px,#fff,transparent 40%),
      radial-gradient(1.2px 1.2px at 300px 150px,#fff,transparent 40%),
      radial-gradient(1px 1px at 520px 40px,#fff,transparent 40%),
      radial-gradient(1px 1px at 420px 260px,#fff,transparent 40%); background-size:800px 800px; }
  .space-bg .layer3{ --dur:320s; opacity:.45; background-image:
      radial-gradient(1px 1px at 90px 140px,#fff,transparent 40%),
      radial-gradient(1px 1px at 260px 240px,#fff,transparent 40%),
      radial-gradient(1px 1px at 380px 120px,#fff,transparent 40%),
      radial-gradient(1px 1px at 560px 300px,#fff,transparent 40%); background-size:1100px 1100px; }
  .shooting{
    position:absolute; width:2px; height:2px; background:transparent; opacity:0; transform: rotate(35deg);
    filter: drop-shadow(0 0 6px #fff); animation: shoot var(--sDur,8s) ease-in-out var(--sDelay,0s) infinite;
  }
  .shooting::after{ content:""; position:absolute; width:160px; height:2px; right:-8px; top:0;
    background: linear-gradient(90deg, rgba(255,255,255,0), rgba(255,255,255,.95), rgba(56,189,248,0)); }
  .s1{ top:12%; left:-8%;  --sDelay: 1.8s; --sDur: 9s; }
  .s2{ top:28%; left:-10%; --sDelay: 5s;   --sDur: 10.5s; }
  .s3{ top:60%; left:-12%; --sDelay: 8.5s; --sDur: 11.5s; }

  @keyframes starDrift{ from{ transform: translateY(0) } to{ transform: translateY(-400px) } }
  @keyframes nebulaPulse{ from{ transform: scale(1) translateY(0); opacity:.55 } to{ transform: scale(1.06) translateY(-6px); opacity:.7 } }
  @keyframes shoot{ 0%,60%{ opacity:0; transform: translate3d(0,0,0) rotate(35deg); }
    61%{opacity:1} 75%{ transform: translate3d(1200px,420px,0) rotate(35deg); opacity:0 } 100%{ opacity:0 } }
  @media (prefers-reduced-motion: reduce){
    .space-bg .nebula, .space-bg .stars, .shooting{ animation: none !important; }
  }

  /* ======================= Register Card & UI ======================= */
  .page-fore { position: relative; z-index: 1; }
  .auth-wrap{
    position:relative; min-height:100svh; display:grid; place-items:center; padding: 20px 12px;
    color:var(--text);
  }

  /* Optional local parallax (subtle depth inside the page) */
  .stars, .stars2, .stars3{ position:absolute; inset:0; background-repeat:repeat; pointer-events:none; }
  .stars{ background-image:
      radial-gradient(2px 2px at 20px 30px,rgba(255,255,255,.8),transparent 40%),
      radial-gradient(1.5px 1.5px at 120px 80px,rgba(255,255,255,.6),transparent 40%),
      radial-gradient(2px 2px at 200px 20px,rgba(255,255,255,.75),transparent 40%);
      background-size:400px 400px; animation: drift 120s linear infinite; opacity:.28; }
  .stars2{ background-image:
      radial-gradient(1.3px 1.3px at 60px 90px,rgba(255,255,255,.7),transparent 40%),
      radial-gradient(1.3px 1.3px at 160px 190px,rgba(255,255,255,.5),transparent 40%),
      radial-gradient(1.3px 1.3px at 300px 40px,rgba(255,255,255,.65),transparent 40%);
      background-size:600px 600px; animation: drift 180s linear infinite reverse; opacity:.22; }
  .stars3{ background-image:
      radial-gradient(1px 1px at 90px 140px,rgba(255,255,255,.6),transparent 40%),
      radial-gradient(1px 1px at 260px 240px,rgba(255,255,255,.45),transparent 40%),
      radial-gradient(1px 1px at 380px 120px,rgba(255,255,255,.5),transparent 40%);
      background-size:800px 800px; animation: drift 240s linear infinite; opacity:.18; }
  @keyframes drift{ 0%{ transform: translateY(0) } 100%{ transform: translateY(-400px) } }

  .card{
    position:relative; width:min(960px, 92vw); display:grid; grid-template-columns: 1.05fr .95fr;
    gap:24px; padding:28px; border-radius:var(--radius); border:1px solid var(--border);
    background:linear-gradient(180deg, rgba(255,255,255,.09), rgba(255,255,255,.03));
    background-clip: padding-box; box-shadow: var(--shadow); overflow:hidden;
  }
  @media (max-width: 920px){ .card{ grid-template-columns: 1fr; padding:22px; } }
  .glass{ position:absolute; inset:-2px; border-radius:inherit; backdrop-filter: var(--blur); -webkit-backdrop-filter: var(--blur); pointer-events:none; }
  .aura{ position:absolute; inset:-60%; pointer-events:none; opacity:.35;
    background: radial-gradient(600px 320px at 80% 20%, rgba(56,189,248,.35), transparent 70%),
                radial-gradient(600px 320px at 10% 90%, rgba(99,102,241,.35), transparent 70%),
                radial-gradient(700px 380px at 50% 50%, rgba(6,182,212,.25), transparent 70%);
    filter: blur(40px); }

  .panel-art{
    position:relative; border-radius:var(--radius-sm);
    border:1px solid var(--border); overflow:hidden;
    background: radial-gradient(900px 500px at -10% 110%, rgba(56,189,248,.1), transparent 60%),
                linear-gradient(180deg, rgba(3,7,18,.8), rgba(2,6,23,.65));
    min-height: 420px; display:flex; flex-direction:column; justify-content:space-between;
  }
  .brand{ display:flex; align-items:center; gap:12px; padding:18px; }
  .logo{ width:44px; height:44px; border-radius:12px; display:grid; place-items:center; overflow:hidden;
    background: conic-gradient(from 220deg, var(--accent-1), var(--accent-2), var(--accent-3));
    box-shadow: 0 6px 18px rgba(56,189,248,.35); }
  .logo img{ width:100%; height:100%; object-fit:cover; display:block; }
  .brand h1{ margin:0; line-height:1.05; letter-spacing:-.02em; font-size:1.05rem; font-weight:800; color:var(--text); }
  .brand .sub{ font-size:.78rem; color:var(--muted); margin-top:2px; }

  .planet{ position:absolute; right:-30px; bottom:-30px; width: 230px; height: 230px; border-radius:50%;
    background: radial-gradient(circle at 30% 30%, #b7f9ff 0%, #82e6ff 25%, #5fb9ff 55%, #6376ff 80%, #3342c6 100%);
    box-shadow: inset -30px -40px 70px rgba(0,0,0,.35), 0 30px 120px rgba(99,102,241,.35);
    animation: float 9s ease-in-out infinite; }
  .ring{ position:absolute; width:330px; height:330px; left:-50px; top:-20px; border-radius:50%;
    border:2px solid rgba(255,255,255,.08); transform: rotate(-18deg); animation: rotate 28s linear infinite; }
  @keyframes float{ 0%,100%{ transform: translateY(0) } 50%{ transform: translateY(-10px) } }
  @keyframes rotate{ 0%{ transform: rotate(-18deg) } 100%{ transform: rotate(342deg) } }

  .panel-form{
    position:relative; border-radius:var(--radius-sm); border:1px solid var(--border);
    background: linear-gradient(180deg, rgba(8,12,24,.85), rgba(6,10,20,.7)); padding: 28px 26px;
  }
  .heading{ margin:2px 0 18px; }
  .heading h2{ margin:0 0 6px; font-weight:800; letter-spacing:-.02em; font-size:1.45rem; }
  .heading p{ margin:0; color:var(--muted); font-size:.92rem; }

  .field{ margin-top:14px; }
  label{ display:block; font-size:.9rem; color:var(--muted); margin-bottom:6px; }
  .input{
    width:100%; border-radius:12px; border:1px solid var(--border);
    background: rgba(255,255,255,.03); color:var(--text);
    padding: 12px 14px; font-size:.96rem; outline:none; transition: box-shadow .2s, border-color .2s, background .2s;
  }
  .input:focus{ border-color: var(--accent-2); box-shadow: 0 0 0 4px var(--ring); background: rgba(255,255,255,.06); }

  .error{ margin-top:8px; color:var(--danger); font-size:.85rem; }
  .error ul{ margin: 6px 0 0 18px; }

  .inline{ display:flex; align-items:center; justify-content:space-between; gap:10px; margin-top:14px; flex-wrap:wrap; }
  .links a{ color:var(--muted); text-decoration:none; font-size:.9rem; border-radius:8px; padding:6px 8px; transition: color .2s, background .2s; }
  .links a:hover{ color:var(--text); background: rgba(56,189,248,.09); }

  .actions{ display:flex; align-items:center; justify-content:flex-end; gap:10px; margin-top:18px; }
  .btn{
    display:inline-flex; align-items:center; gap:8px; border:0; cursor:pointer; border-radius:12px;
    padding: 11px 14px; color:#0b1220; background:white; font-weight:700; font-size:.95rem;
    transition: transform .05s ease, box-shadow .2s ease, opacity .2s ease;
  }
  .btn:active{ transform: translateY(1px) }
  .btn-primary{ background: linear-gradient(135deg, var(--accent-1), var(--accent-2)); color:#fff; box-shadow: 0 10px 30px rgba(56,189,248,.35); }
  .btn-primary:hover{ opacity:.95 }
  .btn-ghost{ background:transparent; color:var(--text); border:1px solid var(--border); }
  .btn-ghost:hover{ background: rgba(255,255,255,.04) }

  .micro{ margin-top:10px; color:var(--muted); font-size:.8rem; text-align:center; }
</style>

<!-- Fixed animated background -->
<div class="space-bg" aria-hidden="true">
  <div class="nebula"></div>
  <div class="stars layer1"></div>
  <div class="stars layer2"></div>
  <div class="stars layer3"></div>
  <div class="shooting s1"></div>
  <div class="shooting s2"></div>
  <div class="shooting s3"></div>
</div>

<!-- Foreground -->
<div class="page-fore">
  <div class="auth-wrap">
    <!-- Optional local depth -->
    <div class="stars"></div><div class="stars2"></div><div class="stars3"></div>

    <div class="card">
      <div class="glass"></div>
      <div class="aura"></div>

      <!-- Left: Brand + art -->
      <section class="panel-art" aria-label="NASA Zoom Explorer brand">
        <div class="brand">
          <div class="logo" title="Upload your logo here">
            <img src="" alt="NASA Zoom Explorer Logo (placeholder)">
          </div>
          <div>
            <h1>NASA Zoom Explorer</h1>
            <div class="sub">Create your mission profile.</div>
          </div>
        </div>

        <div class="planet" aria-hidden="true"></div>
        <div class="ring" aria-hidden="true"></div>
      </section>

      <!-- Right: Register form -->
      <section class="panel-form" aria-label="Register form">
        <header class="heading">
          <h2>Join the mission</h2>
          <p>Unlock deep-zoom datasets, annotate features, and explore the cosmos.</p>
        </header>

        <form method="POST" action="{{ route('register') }}" novalidate>
          @csrf

          <!-- Name -->
          <div class="field">
            <label for="name">Name</label>
            <input id="name" class="input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Ada Lovelace">
            @if ($errors->has('name'))
              <div class="error">
                <ul>
                  @foreach ($errors->get('name') as $msg)
                    <li>{{ $msg }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
          </div>

          <!-- Email -->
          <div class="field">
            <label for="email">Email</label>
            <input id="email" class="input" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="you@universe.space">
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

          <!-- Password -->
          <div class="field">
            <label for="password">Password</label>
            <input id="password" class="input" type="password" name="password" required autocomplete="new-password" placeholder="••••••••">
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

          <!-- Confirm Password -->
          <div class="field">
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" class="input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
            @if ($errors->has('password_confirmation'))
              <div class="error">
                <ul>
                  @foreach ($errors->get('password_confirmation') as $msg)
                    <li>{{ $msg }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
          </div>

          <div class="inline">
            <div class="links">
              <a href="{{ route('login') }}">Already registered?</a>
            </div>

            <div class="actions">
              <a class="btn btn-ghost" href="{{ Route::has('home') ? route('home') : url('/') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="margin-right:2px">
                  <path d="m3 9 9-7 9 7v10a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-4H9v4a2 2 0 0 1-2 2H3z"/>
                </svg>
                Back to Home
              </a>

              <button type="submit" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                  <path d="M14 4.5c2.5.5 5.5 3.5 6 6L13.5 17c-2.5-.5-5.5-3.5-6-6L14 4.5Z"/>
                  <path d="M14 4.5c-1 2-1.5 4.5-1 7"/><path d="M5 19l2-2"/><path d="M3.5 20.5c2 0 3-1 3-3-2 0-3 1-3 3Z"/>
                </svg>
                Create Account
              </button>
            </div>
          </div>

          <div class="micro">By creating an account you agree to our space-time continuum policy.</div>
        </form>
      </section>
    </div>
  </div>
</div>
@endsection

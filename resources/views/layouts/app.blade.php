<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Galaxy Glide</title>

  <!-- Favicon (add your file paths below) -->
  <link rel="icon" href="">
  <link rel="icon" type="image/png" sizes="192x192" href="">
  <link rel="apple-touch-icon" sizes="180x180" href="">
  <meta name="theme-color" content="#0a0f18">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,600,700&display=swap" rel="stylesheet" />
  <!-- NASA-style wordmark font -->
  <link href="https://fonts.cdnfonts.com/css/nasalization" rel="stylesheet">

  <!-- Vite assets -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    /* If you want to self-host the font instead of the CDN above, add files to /public/fonts and uncomment:
    @font-face{
      font-family: 'Nasalization';
      src: url('{{ asset('fonts/nasalization-rg.woff2') }}') format('woff2'),
           url('{{ asset('fonts/nasalization-rg.woff') }}') format('woff');
      font-weight: 400;
      font-style: normal;
      font-display: swap;
    } */

    /* ====================== Global Theme ====================== */
    :root {
      --accent:   #6366f1;
      --accent-2: #38bdf8;
      --accent-3: #06b6d4;
      --line:     rgba(255,255,255,.08);
      --muted:    #9ca3af;
      --radius:   12px;
      --radius-lg: 16px;
      --shadow:    0 10px 40px rgba(0,0,0,.45);
      --bg-deep:  #050816;

      /* nav */
      --nav-bg: rgba(10,15,24,.85);
      --nav-border: rgba(255,255,255,.08);
      --chip-bg: rgba(255,255,255,.04);
      --chip-hover: rgba(255,255,255,.08);
      --ring: 0 0 0 3px rgba(56,189,248,.35);

      /* logo control */
      --logo-size: 36px;
      --logo-scale: 1.32;
    }

    html, body { height: 100%; }

    body {
      margin: 0;
      background: #0a0f18;
      color: #e7ecf3;
      font-family: 'Figtree', system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial, 'Noto Sans', sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      overflow-x: hidden;
      position: relative;
    }

    /* ====================== Fixed Space Background ====================== */
    .space-bg{
      position: fixed; inset: 0; z-index: 0; pointer-events: none; overflow: hidden;
      background:
        radial-gradient(1200px 700px at 85% 10%, rgba(56,189,248,.08), transparent 60%),
        radial-gradient(900px 500px at 10% 90%, rgba(99,102,241,.08), transparent 60%),
        radial-gradient(1000px 600px at 50% 50%, rgba(6,182,212,.05), transparent 70%),
        linear-gradient(180deg, var(--bg-deep), #030614 65%, #02040d);
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
    .stars{ position:absolute; inset:0; background-repeat:repeat; opacity:.75; animation: starDrift var(--dur,160s) linear infinite; }
    .layer1{ --dur:160s; background-image:
      radial-gradient(1.6px 1.6px at 40px 60px,#fff,transparent 38%),
      radial-gradient(1.4px 1.4px at 220px 120px,#fff,transparent 40%),
      radial-gradient(1.4px 1.4px at 340px 200px,#fff,transparent 40%),
      radial-gradient(1.2px 1.2px at 520px 50px,#fff,transparent 40%);
      background-size:600px 600px;
    }
    .layer2{ --dur:220s; opacity:.6; animation-direction: reverse; background-image:
      radial-gradient(1.2px 1.2px at 100px 80px,#fff,transparent 40%),
      radial-gradient(1.2px 1.2px at 300px 150px,#fff,transparent 40%),
      radial-gradient(1px 1px at 520px 40px,#fff,transparent 40%),
      radial-gradient(1px 1px at 420px 260px,#fff,transparent 40%);
      background-size:800px 800px;
    }
    .layer3{ --dur:320s; opacity:.45; background-image:
      radial-gradient(1px 1px at 90px 140px,#fff,transparent 40%),
      radial-gradient(1px 1px at 260px 240px,#fff,transparent 40%),
      radial-gradient(1px 1px at 380px 120px,#fff,transparent 40%),
      radial-gradient(1px 1px at 560px 300px,#fff,transparent 40%);
      background-size:1100px 1100px;
    }
    .shooting{ position:absolute; width:2px; height:2px; background:transparent; opacity:0; transform: rotate(35deg); filter: drop-shadow(0 0 6px #fff); animation: shoot var(--sDur,8s) ease-in-out var(--sDelay,0s) infinite; }
    .shooting::after{ content:""; position:absolute; width:160px; height:2px; right:-8px; top:0; background: linear-gradient(90deg, rgba(255,255,255,0), rgba(255,255,255,.95), rgba(56,189,248,0)); }
    .s1{ top:12%; left:-8%;  --sDelay: 1.8s; --sDur: 9s; }
    .s2{ top:28%; left:-10%; --sDelay: 5s;   --sDur: 10.5s; }
    .s3{ top:60%; left:-12%; --sDelay: 8.5s; --sDur: 11.5s; }
    @keyframes starDrift{ from{transform:translateY(0)} to{transform:translateY(-400px)} }
    @keyframes nebulaPulse{ from{transform:scale(1) translateY(0);opacity:.55} to{transform:scale(1.06) translateY(-6px);opacity:.7} }
    @keyframes shoot{ 0%,60%{ opacity:0; transform: translate3d(0,0,0) rotate(35deg); } 61%{ opacity:1 } 75%{ transform: translate3d(1200px,420px,0) rotate(35deg); opacity:0 } 100%{ opacity:0 } }

    /* ====================== Page Chrome ====================== */
    header, main, footer { position: relative; z-index: 1; }

    /* ======== POLISHED NAV ======== */
    header {
      background: var(--nav-bg);
      border-bottom: 1px solid var(--nav-border);
      backdrop-filter: blur(12px) saturate(120%);
      position: sticky; top: 0; z-index: 50;
      box-shadow: 0 8px 30px rgba(0,0,0,.2);
    }
    nav {
      max-width: 1200px; margin: 0 auto; padding: 12px 20px;
      display: flex; justify-content: space-between; align-items: center;
      min-height: 64px;
    }
    nav .logo {
      display:inline-flex; align-items:center; gap:12px;
      font-weight: 800; font-size: 20px; letter-spacing: .02em;
      color: #e7ecf3; text-decoration: none;
      padding: 8px 10px; border-radius: var(--radius);
      transition: transform .2s ease, background .2s ease, box-shadow .2s ease;
      white-space: nowrap;
    }
    nav .logo img.brand-img{
      width: var(--logo-size);
      height: var(--logo-size);
      display:inline-block;
      object-fit: contain;
      border: none;
      box-shadow: none;
      transform: scale(var(--logo-scale));
      transform-origin: left center;
      will-change: transform;
    }
    /* NASA-style wordmark for the site title */
    nav .logo .wordmark{
      font-family: 'Nasalization', 'Futura', 'Eurostile', system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial, sans-serif;
      font-weight: 700;
      letter-spacing: .06em;
      text-transform: uppercase;
      line-height: 1;
    }
    nav .logo:hover { background: rgba(255,255,255,.04); transform: translateY(-1px); box-shadow: 0 8px 18px rgba(0,0,0,.12); }

    .links { display:flex; flex-wrap:wrap; gap:8px; align-items:center; }

    .chip {
      display:inline-flex; align-items:center; gap:8px;
      color: var(--muted); text-decoration:none; font-weight:600;
      padding: 8px 12px; border-radius: var(--radius);
      background: var(--chip-bg); border: 1px solid rgba(255,255,255,.06);
      transition: color .2s ease, background .2s ease, transform .15s ease, box-shadow .25s ease, border-color .2s ease;
      position: relative;
    }
    .chip:hover {
      color:#fff; background: var(--chip-hover); transform: translateY(-1px);
      border-color: rgba(255,255,255,.14);
      box-shadow: 0 10px 24px rgba(6, 182, 212, .18);
    }
    .chip:focus-visible { outline:none; box-shadow: var(--ring); }
    .chip::after{
      content:""; position:absolute; left:12px; right:12px; bottom:6px; height:2px; border-radius:999px;
      background: linear-gradient(90deg, transparent, rgba(99,102,241,.7), rgba(56,189,248,.7), transparent);
      opacity:0; transform: scaleX(.6); transition: opacity .25s ease, transform .25s ease;
    }
    .chip:hover::after{ opacity:.9; transform: scaleX(1); }

    .i{ width:18px; height:18px; opacity:.9; flex:0 0 18px; color: currentColor; transition: transform .2s ease, opacity .2s ease; }
    .chip:hover .i{ transform: translateY(-1px); opacity:1; }

    .links form[method="POST"] button{
      all: unset;
      display:inline-flex; align-items:center; gap:8px;
      color: #fca5a5; cursor:pointer; font-weight:700;
      padding: 8px 12px; border-radius: var(--radius);
      background: rgba(220,38,38,.08); border: 1px solid rgba(248,113,113,.25);
      transition: color .2s ease, background .2s ease, transform .15s ease, box-shadow .25s ease, border-color .2s ease;
    }
    .links form[method="POST"] button:hover{
      color:#fecaca; background: rgba(220,38,38,.12); transform: translateY(-1px);
      border-color: rgba(248,113,113,.4); box-shadow: 0 10px 24px rgba(248,113,113,.2);
    }
    .links form[method="POST"] button:focus-visible{ outline:none; box-shadow: var(--ring); }

    main { flex: 1; width: 100%; max-width: 1200px; margin: 0 auto; padding: 32px 20px; position: relative; }
    footer { text-align: center; padding: 20px; font-size: 14px; color: var(--muted); border-top: 1px solid var(--line); background: rgba(10,15,24,.92); }

    @media (max-width: 520px){
      :root{ --logo-scale: 1.18; }
      nav { padding: 10px 16px; }
      .links { gap: 6px; }
    }
    @media (prefers-reduced-motion: reduce){
      nav .logo img.brand-img{ transform: none; }
    }
  </style>
</head>
<body>
  <!-- Background -->
  <div class="space-bg" aria-hidden="true">
    <div class="nebula"></div>
    <div class="stars layer1"></div>
    <div class="stars layer2"></div>
    <div class="stars layer3"></div>
    <div class="shooting s1"></div>
    <div class="shooting s2"></div>
    <div class="shooting s3"></div>
  </div>

  <header>
    <nav>
      <!-- Image logo (bigger visually, layout preserved) -->
      <a href="{{ route('home') }}" class="logo">
        <img class="brand-img" src="https://images.seeklogo.com/logo-png/19/2/nasa-logo-png_seeklogo-195796.png" alt="NASA Zoom Logo" />
        <span class="wordmark">Galaxy Glide</span>
      </a>

      <div class="links">
        <!-- Home -->
        <a href="{{ route('images.index') }}" class="chip" title="Home">
          <svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M3 10.5 12 3l9 7.5"/><path d="M5 10v10h14V10"/><path d="M9 20v-6h6v6"/>
          </svg>
          Home
        </a>

        {{-- Only show upload to admins --}}
        @auth
          @if(Auth::user()->role === 'admin')
            <!-- Admin Dashboard -->
            <a href="{{ route('admin.images.index') }}" class="chip" title="Admin Dashboard">
              <svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="3" y="3" width="7" height="7" rx="1.6"/><rect x="14" y="3" width="7" height="7" rx="1.6"/>
                <rect x="14" y="14" width="7" height="7" rx="1.6"/><rect x="3" y="14" width="7" height="7" rx="1.6"/>
              </svg>
              Admin Dashboard
            </a>

            <!-- Upload -->
            <a href="{{ route('admin.images.create') }}" class="chip" title="Upload">
              <svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 4v12"/><path d="M7 9l5-5 5 5"/><path d="M4 20h16"/>
              </svg>
              Upload
            </a>
          @endif

          <!-- Logout (form method preserved) -->
          <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit" title="Logout">
              <svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M9 21H6a3 3 0 0 1-3-3V6a3 3 0 0 1 3-3h3"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/>
              </svg>
              Logout
            </button>
          </form>
        @else
          <!-- Login -->
          <a href="{{ route('login') }}" class="chip" title="Login">
            <svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M9 21H6a3 3 0 0 1-3-3V6a3 3 0 0 1 3-3h3"/><path d="M8 12h12"/><path d="M14 6l6 6-6 6"/>
            </svg>
            Login
          </a>
        @endauth
      </div>
    </nav>
  </header>

  <main>
    @yield('content')
  </main>

  <footer>
    © {{ date('Y') }} Developed by AsIbraNexis. All rights reserved.
  </footer>
</body>
</html>

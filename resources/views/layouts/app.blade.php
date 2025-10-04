<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ config('app.name', 'NASA Zoom Explorer') }}</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,600,700&display=swap" rel="stylesheet" />

  <!-- Vite assets -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    /* ====================== Global Theme ====================== */
    :root {
      --accent:   #6366f1;
      --accent-2: #38bdf8;
      --accent-3: #06b6d4;
      --line:     rgba(255,255,255,.08);
      --muted:    #9ca3af;
      --radius-lg: 16px;
      --shadow:    0 10px 40px rgba(0,0,0,.45);
      --bg-deep:  #050816;
    }

    html, body {
      height: 100%;
    }

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
      position: fixed;
      inset: 0;
      z-index: 0;
      pointer-events: none;
      overflow: hidden;
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
      opacity:.55;
      animation: nebulaPulse 18s ease-in-out infinite alternate;
    }

    .stars{
      position:absolute; inset:0; background-repeat:repeat; opacity:.75;
      animation: starDrift var(--dur,160s) linear infinite;
    }
    .layer1{
      --dur: 160s;
      background-image:
        radial-gradient(1.6px 1.6px at 40px 60px,#fff,transparent 38%),
        radial-gradient(1.4px 1.4px at 220px 120px,#fff,transparent 40%),
        radial-gradient(1.4px 1.4px at 340px 200px,#fff,transparent 40%),
        radial-gradient(1.2px 1.2px at 520px 50px,#fff,transparent 40%);
      background-size: 600px 600px;
    }
    .layer2{
      --dur: 220s; opacity:.6; animation-direction: reverse;
      background-image:
        radial-gradient(1.2px 1.2px at 100px 80px,#fff,transparent 40%),
        radial-gradient(1.2px 1.2px at 300px 150px,#fff,transparent 40%),
        radial-gradient(1px 1px at 520px 40px,#fff,transparent 40%),
        radial-gradient(1px 1px at 420px 260px,#fff,transparent 40%);
      background-size: 800px 800px;
    }
    .layer3{
      --dur: 320s; opacity:.45;
      background-image:
        radial-gradient(1px 1px at 90px 140px,#fff,transparent 40%),
        radial-gradient(1px 1px at 260px 240px,#fff,transparent 40%),
        radial-gradient(1px 1px at 380px 120px,#fff,transparent 40%),
        radial-gradient(1px 1px at 560px 300px,#fff,transparent 40%);
      background-size: 1100px 1100px;
    }

    .shooting{
      position:absolute; width:2px; height:2px; background:transparent; opacity:0;
      transform: rotate(35deg); filter: drop-shadow(0 0 6px #fff);
      animation: shoot var(--sDur,8s) ease-in-out var(--sDelay,0s) infinite;
    }
    .shooting::after{
      content:""; position:absolute; width:160px; height:2px; right:-8px; top:0;
      background: linear-gradient(90deg, rgba(255,255,255,0), rgba(255,255,255,.95), rgba(56,189,248,0));
    }
    .s1{ top:12%; left:-8%;  --sDelay: 1.8s; --sDur: 9s; }
    .s2{ top:28%; left:-10%; --sDelay: 5s;   --sDur: 10.5s; }
    .s3{ top:60%; left:-12%; --sDelay: 8.5s; --sDur: 11.5s; }

    @keyframes starDrift{ from{transform:translateY(0)} to{transform:translateY(-400px)} }
    @keyframes nebulaPulse{ from{transform:scale(1) translateY(0);opacity:.55} to{transform:scale(1.06) translateY(-6px);opacity:.7} }
    @keyframes shoot{
      0%,60%{ opacity:0; transform: translate3d(0,0,0) rotate(35deg); }
      61%{ opacity:1 }
      75%{ transform: translate3d(1200px,420px,0) rotate(35deg); opacity:0 }
      100%{ opacity:0 }
    }

    /* Page Chrome */
    header, main, footer { position: relative; z-index: 1; }

    header {
      background: rgba(10,15,24,.9);
      border-bottom: 1px solid var(--line);
      backdrop-filter: blur(10px);
      position: sticky; top: 0; z-index: 50;
    }
    nav {
      max-width: 1200px;
      margin: 0 auto;
      padding: 14px 20px;
      display: flex; justify-content: space-between; align-items: center;
    }
    nav .logo {
      font-weight: 800; font-size: 20px; letter-spacing: .02em;
      color: var(--accent-2); text-decoration: none;
    }
    nav .links a, nav .links button {
      color: var(--muted);
      margin-left: 20px;
      text-decoration: none;
      font-weight: 500;
      transition: color .25s ease;
      background: none; border: none; cursor: pointer; font: inherit;
    }
    nav .links a:hover, nav .links button:hover { color: #fff; }

    main { flex: 1; width: 100%; max-width: 1200px; margin: 0 auto; padding: 32px 20px; position: relative; }

    footer {
      text-align: center;
      padding: 20px;
      font-size: 14px;
      color: var(--muted);
      border-top: 1px solid var(--line);
      background: rgba(10,15,24,.92);
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
      <a href="{{ route('home') }}" class="logo">🚀 NASA Zoom</a>

      <div class="links">
        <a href="{{ route('images.index') }}">Home</a>

        {{-- Only show upload to admins --}}
        @auth
          @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.images.index') }}">Admin Dashboard</a>
            <a href="{{ route('admin.images.create') }}">Upload</a>
          @endif

          <a href="{{ route('dashboard') }}">Dashboard</a>
          <a href="{{ route('profile.edit') }}">Profile</a>
          <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit">Logout</button>
          </form>
        @else
          <a href="{{ route('login') }}">Login</a>
          <a href="{{ route('register') }}">Register</a>
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

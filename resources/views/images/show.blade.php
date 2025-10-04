@extends('layouts.app')

@section('content')
<style>
  :root{
    --bg-deep:#030711;
    --bg:#050816;
    --ink:#e8eef7;
    --muted:#9fb0c9;

    --stroke:rgba(255,255,255,.12);
    --panel-1:rgba(255,255,255,.055);
    --panel-2:rgba(255,255,255,.028);

    --brand:#38bdf8;     /* cyan */
    --brand-2:#6366f1;   /* indigo */
    --brand-3:#22d3ee;   /* sky/cyan */

    --ring:0 0 0 5px rgba(56,189,248,.25);
    --shadow-lg:0 28px 100px rgba(0,0,0,.55);
    --shadow-md:0 16px 50px rgba(2,8,23,.48);

    --radius:16px;
    --radius-lg:20px;
  }

  /* ======================= Cinematic Space Backdrop ======================= */
  .universe{
    position:fixed; inset:0; z-index:0; pointer-events:none; overflow:hidden;
    background:
      radial-gradient(1200px 700px at 82% 8%, rgba(56,189,248,.10), transparent 60%),
      radial-gradient(1000px 640px at 14% 90%, rgba(99,102,241,.10), transparent 62%),
      linear-gradient(180deg, var(--bg-deep), var(--bg) 60%, #040a15);
  }
  .field{position:absolute; inset:-50% -50%; background-repeat:repeat; will-change:transform; filter:saturate(1.08)}
  .f1{
    background-image:
      radial-gradient(1.5px 1.5px at 30px 40px,#fff,transparent 40%),
      radial-gradient(1.4px 1.4px at 380px 120px,#eaf2ff,transparent 40%),
      radial-gradient(1.2px 1.2px at 240px 260px,#fff,transparent 40%);
    background-size:720px 720px; opacity:.78; animation: driftA 160s linear infinite;
  }
  .f2{
    background-image:
      radial-gradient(1.1px 1.1px at 100px 80px,#fff,transparent 40%),
      radial-gradient(1.1px 1.1px at 520px 40px,#cfe8ff,transparent 40%),
      radial-gradient(1.1px 1.1px at 340px 200px,#fff,transparent 40%);
    background-size:1000px 1000px; opacity:.58; animation: driftB 220s linear infinite reverse;
  }
  .f3{
    background-image:
      radial-gradient(.9px .9px at 120px 360px,#fff,transparent 40%),
      radial-gradient(.9px .9px at 260px 140px,#e9d8ff,transparent 40%),
      radial-gradient(.9px .9px at 420px 280px,#fff,transparent 40%);
    background-size:1400px 1400px; opacity:.42; animation: driftC 300s linear infinite;
  }
  @keyframes driftA{to{transform:translate3d(240px,180px,0)}}
  @keyframes driftB{to{transform:translate3d(-280px,-220px,0)}}
  @keyframes driftC{to{transform:translate3d(320px,260px,0)}}

  /* nebulas */
  .nebula{
    position:absolute; inset:-35%;
    background:
      radial-gradient(60rem 34rem at 78% 18%, rgba(56,189,248,.18), transparent 60%),
      radial-gradient(46rem 28rem at 16% 86%, rgba(99,102,241,.18), transparent 62%),
      radial-gradient(60rem 34rem at 50% 52%, rgba(6,182,212,.12), transparent 70%);
    filter: blur(44px) saturate(120%); opacity:.6; animation: pulse 18s ease-in-out infinite alternate;
    mix-blend: screen;
  }
  @keyframes pulse{from{transform:scale(1);opacity:.5}to{transform:scale(1.06);opacity:.75}}

  /* dynamic meteors */
  .meteor{
    position:absolute; width:2px; height:2px; transform:rotate(18deg); opacity:0; pointer-events:none;
    filter: drop-shadow(0 0 7px #fff);
    animation: shoot var(--dur,8s) ease-in-out var(--delay,0s) infinite;
  }
  .meteor::after{
    content:""; position:absolute; width:180px; height:2px; left:-180px; top:0;
    background: linear-gradient(90deg, rgba(255,255,255,0), rgba(255,255,255,.98), rgba(56,189,248,0));
  }
  .m1{ top:12%; left:-10%; --delay:1.2s; --dur:9s }
  .m2{ top:36%; left:-12%; --delay:4.9s; --dur:10.8s }
  .m3{ top:68%; left:-14%; --delay:8.2s; --dur:11.6s }
  @keyframes shoot{
    0%,58%{ opacity:0; transform: translate3d(0,0,0) rotate(18deg) }
    60%   { opacity:1 }
    76%   { transform: translate3d(1100px,420px,0) rotate(18deg); opacity:0 }
    100%  { opacity:0 }
  }

  /* ======================= Page Layout ======================= */
  .wrap{ position:relative; z-index:1; max-width:1200px; margin:0 auto; padding:28px 18px 48px; color:var(--ink) }

  /* Header strip */
  .head{
    display:flex; align-items:flex-end; justify-content:space-between; gap:16px; flex-wrap:wrap;
    margin-bottom:12px;
  }
  .title{
    margin:0; font-weight:900; letter-spacing:.01em; line-height:1.05;
    font-size: clamp(24px, 4.2vw, 40px);
    background: linear-gradient(90deg,#fff 10%, var(--brand) 35%, var(--brand-2) 70%, #fff 100%);
    -webkit-background-clip:text; background-clip:text; color:transparent; background-size:300% 100%;
    animation: shimmer 12s linear infinite;
    text-shadow: 0 10px 32px rgba(99,102,241,.14);
  }
  @keyframes shimmer{to{background-position:300% 50%}}
  .tag{
    display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:999px;
    border:1px solid var(--stroke); background:linear-gradient(180deg, var(--panel-1), var(--panel-2));
    color:#cfe2ff; font-size:.82rem; backdrop-filter: blur(8px);
  }

  /* Back FAB (keeps your route) */
  .back{
    position:fixed; left:16px; top:86px; z-index:5; width:46px; height:46px; display:grid; place-items:center;
    border-radius:999px; border:1px solid var(--stroke); background:linear-gradient(180deg,rgba(255,255,255,.07),rgba(255,255,255,.03));
    box-shadow: 0 14px 36px rgba(0,0,0,.38); backdrop-filter: blur(8px);
    transition: transform .14s ease, box-shadow .25s ease, background .2s ease;
  }
  .back:hover{ transform:translateY(-1px); box-shadow: 0 18px 50px rgba(0,0,0,.44) }

  /* Viewer controls */
  .toolbar{ display:flex; gap:10px; align-items:center; margin:10px 0 12px; flex-wrap:wrap }
  .btn{
    padding:9px 14px; border-radius:12px; border:1px solid var(--stroke);
    background:linear-gradient(180deg, rgba(255,255,255,.08), rgba(255,255,255,.04));
    color:#e7ecf3; font-weight:800; font-size:.94rem; letter-spacing:.01em; cursor:pointer;
    transition: transform .08s, box-shadow .2s, filter .2s, background .2s;
  }
  .btn:hover{ transform:translateY(-1px); filter:saturate(1.05); box-shadow: 0 14px 34px rgba(2,8,23,.38) }
  .btn:focus-visible{ outline:none; box-shadow: var(--ring) }

  /* Deep Zoom viewer */
  .viewer{
    width:100%; height: min(72vh, 760px); border-radius: var(--radius-lg); overflow:hidden;
    border:1px solid var(--stroke);
    background: #000;
    box-shadow: var(--shadow-lg);
    position:relative; isolation:isolate;
  }
  /* chromatic frame glow */
  .viewer::after{
    content:""; position:absolute; inset:-1px; border-radius:inherit; pointer-events:none; z-index:-1;
    background: conic-gradient(from 0deg, rgba(34,211,238,.28), rgba(99,102,241,.28), rgba(56,189,248,.28), rgba(34,211,238,.28));
    filter: blur(18px); opacity:.25; animation: roll 10s linear infinite;
  }
  @keyframes roll{to{transform:rotate(1turn)}}

  /* Zoom indicator (NEW) */
  .zoom-indicator{
    position:absolute; top:12px; right:12px; z-index:10; pointer-events:none;
    font-weight:800; letter-spacing:.02em; font-size:.92rem;
    background:linear-gradient(180deg, rgba(255,255,255,.10), rgba(255,255,255,.06));
    border:1px solid var(--stroke);
    color:#eaf2ff; padding:6px 10px; border-radius:999px;
    box-shadow: 0 10px 24px rgba(0,0,0,.35);
    backdrop-filter: blur(6px);
  }

  /* Info area */
  .info{ display:grid; grid-template-columns: 1.15fr .85fr; gap:16px; margin-top:16px }
  @media (max-width:980px){ .info{ grid-template-columns: 1fr } }

  .card{
    background:linear-gradient(180deg, var(--panel-1), var(--panel-2));
    border:1px solid var(--stroke); border-radius: var(--radius);
    padding:16px; box-shadow: var(--shadow-md);
  }
  .card h3{ margin:.25rem 0 .65rem; font-size:1.08rem; letter-spacing:.02em; color:#dbe8ff }
  .muted{ color:var(--muted); line-height:1.7 }

  .grid-meta{ display:grid; grid-template-columns:1fr 1fr; gap:12px }
  .grid-meta .cell{
    background:rgba(255,255,255,.035); border:1px solid var(--stroke); border-radius:12px; padding:12px;
  }
  .cell small{ display:block; color:#9fb0c9; font-size:.78rem; margin-bottom:2px }
  .cell b{ font-weight:900; color:#e8eef7 }

  /* Reduced motion */
  @media (prefers-reduced-motion:reduce){
    .f1,.f2,.f3,.nebula,.meteor,.viewer::after,.title{ animation:none!important }
    .btn:hover{ transform:none }
  }
</style>

<!-- ======= Background Universe ======= -->
<div class="universe" aria-hidden="true">
  <div class="nebula"></div>
  <div class="field f1"></div>
  <div class="field f2"></div>
  <div class="field f3"></div>
  <div class="meteor m1"></div>
  <div class="meteor m2"></div>
  <div class="meteor m3"></div>
</div>

<!-- Back -->
<a href="{{ route('home') }}" class="back" title="Back to gallery" aria-label="Back to gallery">
  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
    <path d="M15 18l-6-6 6-6" stroke="#e7ecf3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
</a>

<div class="wrap">
  <div class="head">
    <h1 class="title">{{ $image->title }}</h1>
    @if(!empty($image->type))
      <span class="tag">{{ $image->type }}</span>
    @endif
  </div>

  {{-- ======= Toolbar (no method changes) ======= --}}
  <div class="toolbar">
    <button id="btn-zoom-in" class="btn" type="button">＋ Zoom in</button>
    <button id="btn-zoom-out" class="btn" type="button">－ Zoom out</button>
    <button id="btn-home" class="btn" type="button">Reset</button>
    <button id="btn-full" class="btn" type="button">Fullscreen</button>
  </div>

  <div id="openseadragon" class="viewer" role="region" aria-label="Deep zoom viewer">
    <!-- NEW: zoom percentage overlay -->
    <div id="zoomIndicator" class="zoom-indicator">100%</div>
  </div>

  <div class="info">
    <section class="card">
      <h3>Description</h3>
      <div class="muted">
        {!! $image->description ? nl2br(e($image->description)) : 'No description was provided for this dataset.' !!}
      </div>
    </section>

    <aside class="card">
      <h3>Details</h3>
      <div class="grid-meta">
        <div class="cell">
          <small>Uploaded</small>
          <b>{{ $image->created_at?->format('Y-m-d H:i') }}</b>
        </div>
        <div class="cell">
          <small>Updated</small>
          <b>{{ $image->updated_at?->format('Y-m-d H:i') }}</b>
        </div>
        <div class="cell">
          <small>Thumbnail</small>
          <b>{{ $image->thumbnail_path ? 'Available' : '—' }}</b>
        </div>
        <div class="cell">
          <small>Type</small>
          <b>{{ $image->type ?: '—' }}</b>
        </div>
        {{-- DZI path intentionally omitted per your request --}}
      </div>
    </aside>
  </div>
</div>

{{-- ======= OpenSeadragon (tuned for sharpness) ======= --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/openseadragon/3.1.0/openseadragon.min.js" crossorigin="anonymous"></script>
<script>
  (function () {
    const dziUrl = "{{ url('storage/' . $image->dzi_path) }}";
    const zoomEl = document.getElementById('zoomIndicator');

    const viewer = OpenSeadragon({
      id: "openseadragon",
      prefixUrl: "https://cdnjs.cloudflare.com/ajax/libs/openseadragon/3.1.0/images/",
      tileSources: dziUrl,

      // --- Sharpness / Over-zoom control ---
      maxZoomPixelRatio: 2.5,     // lower top-end to avoid soft/blurry over-zoom
      minZoomImageRatio: 0.75,
      visibilityRatio: 1.0,

      // --- Smoother motion (less time in blurry transitions) ---
      animationTime: 0.5,         // faster
      springStiffness: 8.0,
      zoomPerClick: 1.5,
      zoomPerScroll: 1.25,

      // --- Tile behaviour (prefer crisp tiles) ---
      preload: true,              // prefetch neighbors
      immediateRender: false,     // wait for sharp tiles if they're near-ready
      blendTime: 0.08,            // shorter blend = less mush
      alwaysBlend: false,
      smoothTileEdges: true,

      showNavigator: true,
      navigatorAutoFade: true,
      constrainDuringPan: false
    });

    // Prefer photo-like smoothing for space imagery (if supported)
    if (viewer.drawer && typeof viewer.drawer.setImageSmoothingEnabled === 'function') {
      viewer.drawer.setImageSmoothingEnabled(true);
    }

    // --- Toolbar bindings (unchanged behaviour) ---
    document.getElementById('btn-zoom-in').addEventListener('click', () => {
      viewer.viewport.zoomBy(1.3); viewer.viewport.applyConstraints();
    });
    document.getElementById('btn-zoom-out').addEventListener('click', () => {
      viewer.viewport.zoomBy(1/1.3); viewer.viewport.applyConstraints();
    });
    document.getElementById('btn-home').addEventListener('click', () => viewer.viewport.goHome());
    document.getElementById('btn-full').addEventListener('click', () => viewer.setFullScreen(!viewer.isFullPage()));

    // --- Live zoom percentage (NEW) ---
    function updateZoomLabel() {
      // Convert current viewport zoom to "image zoom":
      // 1.00 == native 1:1 image pixels to screen pixels.
      const z = viewer.viewport.viewportToImageZoom(viewer.viewport.getZoom(true));
      const pct = Math.max(0, Math.round(z * 100)); // show 0–xxx%
      if (zoomEl) zoomEl.textContent = pct + '%';
    }

    // Update on open and while zooming
    viewer.addHandler('open', updateZoomLabel);
    viewer.addHandler('animation', updateZoomLabel);
    viewer.addHandler('zoom', updateZoomLabel);
    viewer.addHandler('resize', updateZoomLabel);

    // Graceful failure
    viewer.addHandler('open-failed', function(e){
      const el = document.getElementById('openseadragon');
      el.innerHTML =
        '<div style="display:grid;place-items:center;height:100%;color:#d7e2f7;padding:16px;text-align:center">'+
        '<div><strong>Unable to open the Deep Zoom source.</strong><br><span style="opacity:.8">Please verify the DZI file exists in storage.</span></div>'+
        '</div>';
      console.warn('DZI open failed:', e);
    });
  })();
</script>
@endsection

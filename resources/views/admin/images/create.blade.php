{{-- resources/views/admin/images/create.blade.php --}}
@extends('layouts.app')

@section('content')
 
<style>
  /* ===== Page Theme (scoped) ===== */
  .upload-page {
    --bg: #070c18;
    --surface: #0b1120;
    --surface-2: #0a0f1c;
    --border: rgba(255,255,255,0.10);
    --line: rgba(148,163,184,.18);
    --text: #e7ecf3;
    --muted: #9aa4b2;
    --brand: #38bdf8;
    --brand-2: #6366f1;
    --ring: rgba(56,189,248,0.35);
    --error: #ef4444;

    display: grid;
    gap: clamp(16px, 2.5vw, 24px);
    padding: clamp(16px, 4vw, 36px) clamp(12px, 3vw, 28px);
    color: var(--text);
    perspective: 1200px;
  }

  /* ===== Cosmic backdrop ===== */
  .upload-bg {
    position: fixed; inset: 0; z-index: 0; pointer-events: none; overflow: hidden;
    background:
      radial-gradient(1000px 600px at 85% 12%, rgba(56,189,248,.10), transparent 60%),
      radial-gradient(900px 520px at 12% 88%, rgba(99,102,241,.10), transparent 60%),
      linear-gradient(180deg, #050816, var(--bg));
  }
  .ubg-stars, .ubg-stars-2, .ubg-stars-3 {
    position:absolute; inset:-25vh -25vw; background-repeat:repeat; will-change: transform;
  }
  .ubg-stars {
    opacity:.75;
    background-image:
      radial-gradient(1.4px 1.4px at 30px 40px, #fff, transparent 40%),
      radial-gradient(1.3px 1.3px at 280px 140px, #eaf2ff, transparent 40%),
      radial-gradient(1.1px 1.1px at 520px 60px, #fff, transparent 40%);
    background-size: 700px 700px;
    animation: bgDrift1 160s linear infinite;
  }
  .ubg-stars-2 {
    opacity:.55;
    background-image:
      radial-gradient(1.1px 1.1px at 100px 80px, #fff, transparent 40%),
      radial-gradient(1.1px 1.1px at 420px 240px, #dfe6ff, transparent 40%),
      radial-gradient(1.0px 1.0px at 260px 300px, #fff, transparent 40%);
    background-size: 1000px 1000px;
    animation: bgDrift2 220s linear infinite reverse;
  }
  .ubg-stars-3 {
    opacity:.38;
    background-image:
      radial-gradient(0.9px 0.9px at 180px 200px, #fff, transparent 40%),
      radial-gradient(0.9px 0.9px at 520px 320px, #f5f7ff, transparent 40%);
    background-size: 1400px 1400px;
    animation: bgDrift3 320s linear infinite;
  }
  .ubg-nebula {
    position:absolute; inset:-40%;
    background:
      radial-gradient(60rem 34rem at 78% 18%, rgba(56,189,248,.18), transparent 60%),
      radial-gradient(46rem 28rem at 16% 86%, rgba(99,102,241,.18), transparent 62%),
      radial-gradient(60rem 34rem at 50% 52%, rgba(6,182,212,.12), transparent 70%);
    filter: blur(42px) saturate(120%); opacity:.55;
    animation: nebulaPulse 18s ease-in-out infinite alternate;
  }

  @keyframes bgDrift1 { to { transform: translate3d(220px,180px,0) } }
  @keyframes bgDrift2 { to { transform: translate3d(-260px,-200px,0) } }
  @keyframes bgDrift3 { to { transform: translate3d(260px,220px,0) } }
  @keyframes nebulaPulse { from { transform: scale(1); opacity:.55 } to { transform: scale(1.06); opacity:.7 } }

  /* ===== Shooting stars (base styles) ===== */
  .meteor {
    position:absolute; width:2px; height:2px; transform: rotate(18deg); opacity:0;
    filter: drop-shadow(0 0 6px #fff);
  }
  .meteor::after{
    content:""; position:absolute; width:160px; height:2px; left:-160px; top:0;
    background: linear-gradient(90deg, rgba(255,255,255,0), rgba(255,255,255,.98), rgba(56,189,248,0));
  }

  /* ===== Back FAB ===== */
  .upload-page a.fab-back {
    position: fixed; z-index: 2;
    top: clamp(10px, 2vw, 16px);
    left: clamp(10px, 2vw, 16px);
    width: 44px; height: 44px;
    display: grid; place-items: center;
    border-radius: 999px;
    background: linear-gradient(180deg, #0f172a, #0b1324);
    border: 1px solid var(--border);
    box-shadow: 0 16px 36px rgba(0,0,0,.4);
    transition: transform .12s ease, box-shadow .2s ease, background .2s ease;
  }
  .upload-page a.fab-back:hover { transform: translateY(-1px) scale(1.02); }
  .upload-page a.fab-back:active { transform: translateY(0); }
  .upload-page a.fab-back:focus-visible { outline: none; box-shadow: 0 0 0 6px var(--ring); }
  .upload-page a.fab-back svg path { stroke: #e7ecf3; opacity: .9; }

  /* ===== Upload Card (3D + spotlight) ===== */
  .upload-wrap {
    position: relative; z-index: 1;
    width: 100%; max-width: 880px; margin: 0 auto;
    border-radius: 22px;
    padding: clamp(16px, 3vw, 28px);
    background: linear-gradient(200deg, rgba(111,151,255,.10), rgba(185,147,255,.08) 35%, rgba(255,255,255,.03));
    border:1px solid var(--border);
    box-shadow: 0 30px 80px rgba(2,8,23,.55);
    transform-style: preserve-3d;
    transition: transform .18s ease, box-shadow .25s ease, border-color .25s ease;
    --rx: 0deg; --ry: 0deg;
    transform: rotateX(var(--rx)) rotateY(var(--ry));
  }
  .upload-wrap:hover { box-shadow: 0 36px 120px rgba(2,8,23,.65); border-color: var(--line); }

  .upload-wrap::after{
    content:""; position:absolute; inset:-1px; border-radius: inherit; z-index:-1;
    background: conic-gradient(from 0deg, rgba(56,189,248,.35), rgba(99,102,241,.35), rgba(56,189,248,.35));
    filter: blur(18px); opacity:.28; animation: ringSpin 8s linear infinite;
  }
  @keyframes ringSpin { to { transform: rotate(1turn) } }

  .shine { position:absolute; inset:0; pointer-events:none; opacity:.0; transition: opacity .25s ease; }
  .upload-wrap:hover .shine{ opacity:.9 }
  .shine::before{
    content:""; position:absolute; inset:-60%; transform: rotate(15deg);
    background: radial-gradient(420px 160px at var(--mx,60%) var(--my,20%), rgba(255,255,255,.07), transparent 42%);
  }

  /* ===== Headings ===== */
  .upload-head { margin-bottom: clamp(10px, 2vw, 18px); }
  .upload-head h1 {
    margin: 0 0 8px 0;
    font-size: clamp(20px, 2.5vw, 30px); line-height: 1.15; letter-spacing: -0.01em; font-weight: 900;
    background: linear-gradient(90deg,#fff 10%, var(--brand) 35%, var(--brand-2) 65%, #fff 100%);
    -webkit-background-clip: text; background-clip: text; color: transparent;
    background-size: 300% 100%; animation: titleShimmer 10s linear infinite;
    text-shadow: 0 8px 24px rgba(99,102,241,.14);
  }
  .upload-head .muted { color: var(--muted); }
  @keyframes titleShimmer{ to { background-position: 300% 50% } }

  /* ===== Cards ===== */
  .card {
    background: rgba(255,255,255,0.02);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: clamp(14px, 2vw, 20px);
    position: relative;
    overflow: hidden;
  }

  /* Errors */
  .card.errors {
    border-color: rgba(239,68,68,.35);
    background: rgba(239,68,68,.06);
    color: #ffe2e2;
    margin-bottom: 12px;
  }
  .card.errors ul { margin: 0; padding-left: 18px; }

  /* ===== Form Grid ===== */
  .form-grid {
    display: grid;
    gap: 14px;
    grid-template-columns: 1fr 1fr;
    transform: translateZ(10px);
  }
  @media (max-width: 820px) { .form-grid { grid-template-columns: 1fr; } }
  .form-grid .full { grid-column: 1 / -1; }

  /* Labels & Inputs */
  .label-strong {
    font-weight: 700; font-size: 13px; letter-spacing: .02em; color: var(--muted);
    margin-bottom: 8px; text-transform: uppercase; display: block;
  }
  .input, textarea.input, input[type="file"].input {
    width: 100%;
    background: linear-gradient(180deg, rgba(255,255,255,.04), rgba(255,255,255,.02));
    border: 1px solid var(--border);
    color: var(--text);
    border-radius: 12px;
    padding: 12px 14px;
    font-size: 15px; line-height: 1.4;
    transition: border-color .15s ease, box-shadow .15s ease, background .15s ease, transform .08s ease;
  }
  .input:hover { transform: translateY(-1px); }
  .input:focus, textarea.input:focus, input[type="file"].input:focus {
    outline: none; border-color: var(--brand);
    box-shadow: 0 0 0 6px var(--ring);
    background: linear-gradient(180deg, rgba(56,189,248,.08), rgba(255,255,255,.03));
  }
  textarea.input { min-height: 110px; resize: vertical; }
  input[type="file"].input { padding: 10px 12px; }

  .helper { display:block; margin-top:6px; font-size:12px; color: var(--muted); }

  /* ===== Actions ===== */
  .actions { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 16px; transform: translateZ(14px); }
  .cta, .btn-ghost {
    position: relative;
    display: inline-flex; align-items: center; gap: 8px;
    border-radius: 12px;
    padding: 11px 16px;
    font-weight: 800; font-size: 14px;
    border: 1px solid transparent;
    transition: transform .08s ease, box-shadow .15s ease, background .15s ease, color .15s ease, border-color .15s ease;
    white-space: nowrap; overflow: hidden;
  }
  /* soft glow ripple on hover */
  .cta::after, .btn-ghost::after{
    content:""; position:absolute; inset:-120%; transform: rotate(25deg);
    background: radial-gradient(220px 120px at var(--bx,50%) var(--by,50%), rgba(255,255,255,.12), transparent 42%);
    opacity:0; transition: opacity .25s ease;
  }
  .cta:hover::after, .btn-ghost:hover::after{ opacity:1 }

  .cta {
    background: linear-gradient(135deg, var(--brand), var(--brand-2));
    color: #071023;
    box-shadow: 0 16px 40px rgba(56,189,248,.28);
    transform: translateZ(20px);
  }
  .cta:hover { transform: translateZ(20px) translateY(-1px) scale(1.01); box-shadow: 0 20px 50px rgba(56,189,248,.34); }
  .cta:active { transform: translateZ(18px) translateY(0) scale(.995); }
  .cta:focus-visible { outline: none; box-shadow: 0 0 0 6px var(--ring); }

  .btn-ghost {
    background: transparent; color: var(--text); border-color: var(--border);
  }
  .btn-ghost:hover { color: var(--brand); border-color: var(--brand); transform: translateY(-1px); }
  .btn-ghost:active { transform: translateY(0); }
  .btn-ghost:focus-visible { outline: none; box-shadow: 0 0 0 6px var(--ring); }

  /* Reduced motion */
  @media (prefers-reduced-motion: reduce){
    .ubg-stars,.ubg-stars-2,.ubg-stars-3,.ubg-nebula{ animation: none!important }
  }
</style>

<!-- Background -->
<div class="upload-bg" aria-hidden="true">
  <div class="ubg-nebula"></div>
  <div class="ubg-stars"></div>
  <div class="ubg-stars-2"></div>
  <div class="ubg-stars-3"></div>
  <!-- initial meteors (more added dynamically below) -->
  <div class="meteor" style="top:12%; left:-12%"></div>
  <div class="meteor" style="top:34%; left:-10%"></div>
  <div class="meteor" style="top:66%; left:-14%"></div>
</div>

<a href="{{ route('home') }}" class="fab-back" aria-label="Back to gallery">
  <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
    <path d="M15 18l-6-6 6-6" stroke="#0b0f17" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
</a>

<div class="upload-page">
  <div class="upload-wrap" id="panel3d">
    <span class="shine" aria-hidden="true"></span>

    <div class="upload-head">
      <h1>Upload a High-Resolution Image (JPG/JPEG)</h1>
      <p class="muted">We’ll convert it to Deep Zoom (DZI) automatically for smooth, massive zooming.</p>
    </div>

    @if ($errors->any())
      <div class="card errors" role="alert" aria-live="polite">
        <ul style="margin:0;">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.images.store') }}" enctype="multipart/form-data" class="card" novalidate>
      @csrf

      <div class="form-grid">
        <label>
          <span class="label-strong">Title</span>
          <input type="text" name="title" class="input" placeholder="e.g., Milky Way — Southern Sky" required>
        </label>

        <label>
          <span class="label-strong">Type <span class="muted">(optional)</span></span>
          <input type="text" name="type" class="input" placeholder="e.g., space / galaxy / earth / mars">
        </label>

        <label class="full">
          <span class="label-strong">Description <span class="muted">(optional)</span></span>
          <textarea name="description" class="input" rows="3" placeholder="What is this dataset about?"></textarea>
        </label>

        <label class="full">
          <span class="label-strong">Select image (JPG/JPEG only)</span>
          <input type="file" name="photo" class="input" accept=".jpg,.jpeg,image/jpeg" required>
          <small class="helper">Tip: Bigger is better. Very large JPGs tile beautifully for deep zoom.</small>
        </label>
      </div>

      <div class="actions">
        <button class="cta" type="submit" id="ctaBtn">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" style="margin-right:6px">
            <path d="M12 3v12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            <path d="M7.5 8.5L12 4l4.5 4.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M5 15v3a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3v-3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
          </svg>
          Upload & Convert
        </button>

        <a href="{{ route('home') }}" class="btn-ghost" id="cancelBtn">Cancel</a>
      </div>
    </form>
  </div>
</div>

<script>
  // 3D tilt + spotlight on panel
  (function(){
    const panel = document.getElementById('panel3d');
    if(!panel) return;

    function setSpot(e){
      const r = panel.getBoundingClientRect();
      const px = (e.clientX - r.left) / r.width;
      const py = (e.clientY - r.top) / r.height;
      const rx = (py - 0.5) * -6;  // tilt X
      const ry = (px - 0.5) *  8;  // tilt Y
      panel.style.setProperty('--rx', rx + 'deg');
      panel.style.setProperty('--ry', ry + 'deg');
      panel.style.setProperty('--mx', (px*100)+'%');
      panel.style.setProperty('--my', (py*100)+'%');
    }
    panel.addEventListener('mousemove', setSpot, {passive:true});
    panel.addEventListener('mouseleave', ()=>{
      panel.style.setProperty('--rx', '0deg');
      panel.style.setProperty('--ry', '0deg');
      panel.style.setProperty('--mx', '60%');
      panel.style.setProperty('--my', '20%');
    }, {passive:true});
  })();

  // Button hover ripple position (for the glossy ::after)
  (function(){
    function attach(btn){
      if(!btn) return;
      btn.addEventListener('mousemove', (e)=>{
        const r = e.currentTarget.getBoundingClientRect();
        const x = ((e.clientX - r.left) / r.width) * 100 + '%';
        const y = ((e.clientY - r.top) / r.height) * 100 + '%';
        e.currentTarget.style.setProperty('--bx', x);
        e.currentTarget.style.setProperty('--by', y);
      }, {passive:true});
    }
    attach(document.getElementById('ctaBtn'));
    attach(document.getElementById('cancelBtn'));
  })();

  // Dynamic shooting stars (randomized, continuous)
  (function(){
    const bg = document.querySelector('.upload-bg');
    if(!bg) return;

    function spawnMeteor(){
      if (document.hidden) return; // pause when tab not visible
      const m = document.createElement('div');
      m.className = 'meteor';
      const startY = Math.random() * window.innerHeight * 0.7 + 20;   // from 20px to ~70vh
      const startX = -Math.random() * 200 - 40;                       // off-screen left
      const len = 120 + Math.random() * 200;
      const dur = 6 + Math.random() * 5;

      m.style.top = startY + 'px';
      m.style.left = startX + 'px';

      bg.appendChild(m);

      // Keyframe via WAAPI for smoothness
      m.animate([
        { transform: 'translate3d(0,0,0) rotate(18deg)', opacity: 0 },
        { transform: 'translate3d(30vw, 14vh, 0) rotate(18deg)', opacity: .95, offset: .25 },
        { transform: 'translate3d(110vw, 55vh, 0) rotate(18deg)', opacity: 0 }
      ], { duration: dur * 1000, easing: 'linear' });

      // trail length
      m.style.setProperty('--trail', len + 'px');
      m.style.setProperty('--dur', dur + 's');

      // cleanup
      setTimeout(()=> m.remove(), dur*1000 + 120);
    }

    // steady trickle of meteors
    const loop = () => {
      spawnMeteor();
      const next = 1200 + Math.random()*2200; // 1.2s–3.4s
      setTimeout(loop, next);
    };
    loop();
  })();
</script>
@endsection

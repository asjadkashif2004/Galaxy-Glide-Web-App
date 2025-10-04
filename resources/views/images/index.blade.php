@extends('layouts.app')

@section('content')
<style>
  /* (your cosmic CSS unchanged) */
   :root{
    --bg: #060a13;
    --panel: #0a0f18;
    --text: #e8eef7;
    --muted: #a4b1c8;
    --line: rgba(148,163,184,.18);
    --accent: #6366f1;
    --accent-2: #38bdf8;
    --accent-3: #06b6d4;
    --ring: rgba(56,189,248,.35);
    --shadow: 0 20px 80px rgba(0,0,0,.45);
    --radius: 14px;
    --radius-lg: 18px;
  }

  /* fixed full-page cosmic layers */
  .cosmos{ position:fixed; inset:0; z-index:-5; overflow:hidden; background: radial-gradient(1200px 600px at 15% -10%, rgba(99,102,241,.1), transparent 60%), radial-gradient(1000px 600px at 110% 0%, rgba(6,182,212,.08), transparent 60%), var(--bg);}
  .starfield{ position:absolute; inset:-100vh -100vw; background-repeat:repeat; }
  .s1{
    background-image:
      radial-gradient(1.3px 1.3px at 20px 30px, #fff, transparent 40%),
      radial-gradient(1.3px 1.3px at 380px 120px, #e6f5ff, transparent 40%),
      radial-gradient(1.3px 1.3px at 240px 260px, #fff, transparent 40%);
    background-size: 600px 600px;
    animation: drift1 160s linear infinite;
    opacity:.75;
  }
  .s2{
    background-image:
      radial-gradient(1px 1px at 100px 80px, #fff, transparent 40%),
      radial-gradient(1px 1px at 340px 200px, #cfe8ff, transparent 40%),
      radial-gradient(1px 1px at 520px 40px, #fff, transparent 40%);
    background-size: 900px 900px;
    animation: drift2 220s linear infinite reverse;
    opacity:.55;
  }
  .s3{
    background-image:
      radial-gradient(0.8px 0.8px at 120px 360px, #fff, transparent 40%),
      radial-gradient(0.8px 0.8px at 260px 140px, #e9d8ff, transparent 40%),
      radial-gradient(0.8px 0.8px at 420px 280px, #fff, transparent 40%);
    background-size: 1200px 1200px;
    animation: drift3 300s linear infinite;
    opacity:.45;
  }
  @keyframes drift1{ to { transform: translate3d(200px,160px,0)}}
  @keyframes drift2{ to { transform: translate3d(-220px,-180px,0)}}
  @keyframes drift3{ to { transform: translate3d(260px,220px,0)}}

  /* soft aurora haze */
  .aurora{
    position:absolute; inset:0; pointer-events:none; mix-blend:screen;
    background:
      radial-gradient(60rem 30rem at 10% 20%, rgba(99,102,241,.16), transparent 60%),
      radial-gradient(46rem 26rem at 85% 12%, rgba(56,189,248,.14), transparent 60%),
      radial-gradient(60rem 34rem at 20% 85%, rgba(6,182,212,.12), transparent 60%);
  }

  /* occasional twinkles */
  .twinkle{
    position:absolute; width:2px; height:2px; background:#fff; border-radius:50%; opacity:.85;
    animation: twinkle 2.6s ease-in-out infinite;
    filter: drop-shadow(0 0 6px rgba(255,255,255,.55));
  }
  @keyframes twinkle { 0%,100%{ transform:scale(.7); opacity:.3 } 50%{ transform:scale(1.8); opacity:1 } }

  /* shooting stars (meteors) */
  .meteor{
    position:absolute; width:2px; height:2px; background:linear-gradient(90deg,#fff,rgba(255,255,255,0));
    box-shadow:0 0 6px 2px rgba(255,255,255,.65);
    transform: rotate(18deg);
    opacity:.0;
    pointer-events:none;
  }

  /* ==================== Page UI (unchanged look, just polished) ==================== */
  .muted{ color:var(--muted) }
  .pill{
    display:inline-flex; align-items:center; gap:6px; padding:6px 10px; border-radius:999px; font-size:.78rem;
    background:linear-gradient(180deg, rgba(255,255,255,.08), rgba(255,255,255,.04)); border:1px solid var(--line); color:var(--text);
  }
  .btn-pill{ border-radius:999px!important }
  .btn-ghost{ background:transparent; color:var(--text); border:1px solid var(--line); padding:8px 10px; border-radius:10px; cursor:pointer; transition:background .2s, border-color .2s }
  .btn-ghost:hover{ background:rgba(255,255,255,.06) }
  .btn-ghost-danger{ color:#ffd3d3; border-color:rgba(255,99,99,.25) }
  .btn-ghost-danger:hover{ background:rgba(255,99,99,.1) }

  .cta{
    display:inline-flex; align-items:center; gap:8px; font-weight:800; letter-spacing:.01em; padding:11px 14px; border-radius:12px; text-decoration:none;
    background:linear-gradient(135deg, var(--accent), var(--accent-2)); color:#0b0f17;
    box-shadow:0 16px 40px rgba(56,189,248,.25); transition: transform .06s, opacity .2s, box-shadow .2s;
  }
  .cta:hover{ opacity:.98; box-shadow:0 18px 50px rgba(56,189,248,.32) }
  .cta:active{ transform:translateY(1px) }
  .cta--ghost{ background:transparent; color:var(--text); border:1px solid var(--line); box-shadow:none }
  .cta--ghost:hover{ background:rgba(255,255,255,.06) }

  .input{
    width:min(680px,100%); border-radius:12px; border:1px solid var(--line);
    background:linear-gradient(180deg, rgba(255,255,255,.04), rgba(255,255,255,.02));
    color:var(--text); padding:12px 14px; font-size:.96rem; outline:none; transition: box-shadow .2s, border-color .2s;
  }
  .input::placeholder{ color:#8fa0be }
  .input:focus{ border-color:var(--accent-2); box-shadow:0 0 0 4px var(--ring) }

  .grid{ display:grid; gap:14px; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)) }
  .card{
    position:relative; border-radius:var(--radius);
    background:linear-gradient(200deg, rgba(111,151,255,.10), rgba(185,147,255,.08) 35%, rgba(255,255,255,.03));
    border:1px solid var(--line); box-shadow:var(--shadow); padding:14px; overflow:hidden; isolation:isolate;
  }

  .hero{ position:relative; display:grid; grid-template-columns:1.15fr .85fr; gap:clamp(16px,4vw,32px); align-items:center; margin:clamp(10px,3vw,24px) 0 clamp(14px,3vw,28px)}
  @media (max-width:980px){ .hero{ grid-template-columns:1fr } }

  .hero h1{
    margin:0 0 12px 0; font-weight:900; line-height:1.04; letter-spacing:-.02em; font-size:clamp(30px,5.6vw,56px);
    background:linear-gradient(90deg, #fff 10%, var(--accent) 30%, var(--accent-2) 60%, var(--accent-3) 80%, #fff 100%);
    -webkit-background-clip:text; background-clip:text; color:transparent; background-size:300% 100%; animation:heroShimmer 10s linear infinite;
    text-shadow: 0 6px 30px rgba(99,102,241,.15);
  }
  @keyframes heroShimmer{ to{ background-position:300% 50% } }

  .hero p{ margin:0 0 16px 0; color:var(--muted); max-width:62ch }

  .tag{
    display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:999px;
    background:linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.02)); border:1px solid var(--line); color:var(--muted); position:relative; overflow:hidden;
  }
  .tag::after{
    content:''; position:absolute; inset:-150%; background:conic-gradient(from 0deg, transparent 0 30%, rgba(255,255,255,.08), transparent 70% 100%); animation:spin 14s linear infinite;
  }
  @keyframes spin{ to{ transform:rotate(1turn) } }

  .hero-card{ position:relative; overflow:hidden; isolation:isolate; border-radius:var(--radius-lg);
    background:linear-gradient(200deg, rgba(111,151,255,.10), rgba(185,147,255,.08) 35%, rgba(255,255,255,.03));
    border:1px solid var(--line); box-shadow:var(--shadow); padding:16px;
  }
  .hero-card .spark{
    height:230px; border-radius:14px; border:1px dashed rgba(120,140,190,.28);
    background: radial-gradient(600px 420px at 70% 50%, rgba(96,165,250,.14), transparent 60%), radial-gradient(520px 360px at 20% 80%, rgba(167,139,250,.12), transparent 60%), var(--panel);
  }

  .stats{ display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-top:12px }
  @media (max-width:540px){ .stats{ grid-template-columns:1fr 1fr } }
  .stat{ background:linear-gradient(180deg, rgba(255,255,255,.05), rgba(255,255,255,.02)); border:1px solid var(--line); border-radius:12px; padding:12px; text-align:center }
  .stat b{ display:block; font-size:clamp(20px,3.2vw,30px); font-weight:900 }

  .thumb{ width:100%; aspect-ratio:16/9; border-radius:12px; overflow:hidden; border:1px solid #1d2741; margin-bottom:10px;
    background: radial-gradient(260px 160px at 60% 30%, rgba(96,165,250,.20), transparent 60%), radial-gradient(320px 180px at 20% 90%, rgba(167,139,250,.18), transparent 60%), #0b0f17; position:relative;
  }
  .thumb img{ width:100%; height:100%; object-fit:cover; display:block; transform:translateZ(0) scale(1.01); transition: transform .6s cubic-bezier(.2,.7,.2,1) }
  .item:hover .thumb img{ transform:scale(1.06) }

  .card .shine{ position:absolute; inset:0; pointer-events:none; opacity:0; transition:opacity .25s }
  .card:hover .shine{ opacity:1 }
  .card .shine::before{ content:''; position:absolute; inset:-70%; transform:rotate(15deg);
    background: radial-gradient(420px 160px at var(--mx,60%) var(--my,20%), rgba(255,255,255,.07), transparent 42%);
  }

  .search-bar{ position:sticky; top:10px; z-index:5; display:flex; gap:10px; align-items:center; backdrop-filter: blur(6px) }

  @media (prefers-reduced-motion: reduce){
    .hero h1, .tag::after, .card .shine, .s1, .s2, .s3{ animation:none!important }
  }
</style>

<!-- Cosmic background layers -->
<div class="cosmos">
  <div class="starfield s1"></div>
  <div class="starfield s2"></div>
  <div class="starfield s3"></div>
  <div class="aurora"></div>
  <span class="twinkle" style="left:12%; top:22%"></span>
  <span class="twinkle" style="left:78%; top:18%; animation-delay:.6s"></span>
  <span class="twinkle" style="left:64%; top:72%; animation-delay:1.2s"></span>
</div>

{{-- HERO --}}
<section class="hero">
  <div style="position:relative">
    <h1>Explore Space like Google Maps</h1>
    <p>Zoom through gigapixel NASA imagery, label features, and compare changes over time — all in your browser.</p>

    <div style="display:flex;gap:10px;flex-wrap:wrap">
      <a href="#gallery" class="cta" title="Start Exploring">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0b0f17" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M14 4.5c2.5.5 5.5 3.5 6 6L13.5 17c-2.5-.5-5.5-3.5-6-6L14 4.5Z"/><path d="M14 4.5c-1 2-1.5 4.5-1 7"/>
          <path d="M5 19l2-2"/><path d="M3.5 20.5c2 0 3-1 3-3-2 0-3 1-3 3Z"/>
        </svg>
        Start Exploring
      </a>

      {{-- Removed Upload button from public page --}}

      <span class="tag">Deep Zoom · DZI Tiles</span>
      <span class="tag">OpenSeadragon</span>
    </div>
  </div>

  <div class="hero-card">
    <div class="spark"></div>
    <div class="stats">
      <div class="stat">
        <b>{{ $images->count() }}</b>
        <div class="muted">Datasets</div>
      </div>
      <div class="stat">
        <b>∞</b>
        <div class="muted">Zoom Levels</div>
      </div>
      <div class="stat">
        <b>Labels</b>
        <div class="muted">Add + Explore</div>
      </div>
    </div>
  </div>
</section>

{{-- SEARCH --}}
<div class="search-bar">
  <input id="q" type="text" class="input" placeholder="Search datasets (e.g., Earth, Mars, Andromeda)…" oninput="filterCards()" autocomplete="off">
  <button class="cta cta--ghost" onclick="document.getElementById('gallery').scrollIntoView({behavior:'smooth'})">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>
    </svg>
    Browse
  </button>
</div>

{{-- STATUS --}}
@if (session('status'))
  <div class="card" style="border-color:#2b3a63; margin-bottom:12px">
    <span class="shine"></span>
    <p style="margin:0">{{ session('status') }}</p>
  </div>
@endif

{{-- GALLERY --}}
<section id="gallery">
  @if($images->isEmpty())
    <div class="card">
      <span class="shine"></span>
      <h3 style="margin:0 0 6px 0;font-weight:800">No datasets yet</h3>
      <p class="muted" style="margin:0">
        Datasets will appear here once uploaded by the Admin.
      </p>
    </div>
  @else
    <div class="grid" id="cards">
      @foreach($images as $img)
        @php $thumbUrl = $img->thumbnail_path ? asset('storage/'.$img->thumbnail_path) : null; @endphp
        <article class="card item" style="position:relative"
                 data-title="{{ \Illuminate\Support\Str::lower($img->title) }}"
                 data-type="{{ \Illuminate\Support\Str::lower($img->type) }}">
          <span class="shine"></span>

          {{-- Removed delete button from public page --}}

          <a href="{{ route('images.show', $img->id) }}" style="display:block; color:inherit">
            <div class="thumb">
              @if($thumbUrl)
                <img src="{{ $thumbUrl }}" alt="{{ $img->title }} thumbnail" loading="lazy">
              @endif
            </div>

            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
              <h3 style="margin:0;font-weight:800;letter-spacing:.01em">{{ $img->title }}</h3>
              <span class="pill">{{ $img->type ?: 'dataset' }}</span>
            </div>

            <p class="muted" style="margin:0">
              {{ \Illuminate\Support\Str::limit($img->description, 110) }}
            </p>
          </a>
        </article>
      @endforeach
    </div>
  @endif
</section>

<script>
  /* === Filter cards === */
  function filterCards(){
    const q = document.getElementById('q').value.trim().toLowerCase();
    document.querySelectorAll('#cards .item').forEach(card => {
      const hay = (card.dataset.title + ' ' + card.dataset.type).toLowerCase();
      card.style.display = hay.includes(q) ? '' : 'none';
    });
  }

  /* === Card spotlight === */
  document.querySelectorAll('.card').forEach(card => {
    card.addEventListener('mousemove', e => {
      const r = card.getBoundingClientRect();
      card.style.setProperty('--mx', ((e.clientX - r.left) / r.width) * 100 + '%');
      card.style.setProperty('--my', ((e.clientY - r.top) / r.height) * 100 + '%');
    });
    card.addEventListener('mouseleave', () => {
      card.style.setProperty('--mx', '60%'); card.style.setProperty('--my', '20%');
    });
  });

  /* === Shooting stars generator === */
  const sky = document.querySelector('.cosmos');
  function spawnMeteor(){
    const m = document.createElement('div');
    m.className = 'meteor';
    const startX = Math.random() * window.innerWidth * 0.6 - 100;
    const startY = -40 - Math.random() * 120;
    const length = 140 + Math.random() * 180;
    const duration = 4 + Math.random() * 3;
    m.style.left = startX + 'px';
    m.style.top = startY + 'px';
    m.style.width = '2px';
    m.style.height = length + 'px';
    m.style.opacity = '0';
    sky.appendChild(m);
    m.animate([
      { transform: 'translate(0,0) rotate(18deg)', opacity: 0 },
      { transform: 'translate(60vw, 40vh) rotate(18deg)', opacity: .9, offset: .2 },
      { transform: 'translate(100vw, 70vh) rotate(18deg)', opacity: 0 }
    ], { duration: duration * 1000, easing: 'linear' });
    setTimeout(() => m.remove(), duration * 1000 + 200);
  }
  for (let i=0;i<3;i++) setTimeout(spawnMeteor, 600*i);
  setInterval(() => { if (!document.hidden) spawnMeteor(); }, 2000 + Math.random()*3000);
</script>
@endsection

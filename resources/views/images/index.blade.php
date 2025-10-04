@extends('layouts.app')

@section('content')
<style>
  :root{
    --bg:#060a13; --panel:#0a0f18; --text:#e8eef7; --muted:#a4b1c8;
    --line:rgba(148,163,184,.18); --accent:#6366f1; --accent-2:#38bdf8; --accent-3:#06b6d4;
    --ring:rgba(56,189,248,.35); --radius:14px; --radius-lg:18px; --card-max:420px;
  }

  /* ===== Space background (unchanged) ===== */
  .cosmos{position:fixed;inset:0;z-index:-5;overflow:hidden;
    background:radial-gradient(1200px 600px at 15% -10%, rgba(99,102,241,.10), transparent 60%),
               radial-gradient(1000px 600px at 110% 0%, rgba(6,182,212,.08), transparent 60%), #050816;}
  .starfield{position:absolute;inset:-120vh -120vw;background-repeat:repeat;will-change:transform;filter:saturate(1.05)}
  .s1{background-image:radial-gradient(1.3px 1.3px at 20px 30px,#fff,transparent 40%),
                             radial-gradient(1.3px 1.3px at 380px 120px,#e6f5ff,transparent 40%),
                             radial-gradient(1.3px 1.3px at 240px 260px,#fff,transparent 40%);
      background-size:620px 620px;animation:drift1 160s linear infinite;opacity:.75}
  .s2{background-image:radial-gradient(1px 1px at 100px 80px,#fff,transparent 40%),
                             radial-gradient(1px 1px at 340px 200px,#cfe8ff,transparent 40%),
                             radial-gradient(1px 1px at 520px 40px,#fff,transparent 40%);
      background-size:940px 940px;animation:drift2 220s linear infinite reverse;opacity:.55}
  .s3{background-image:radial-gradient(.8px .8px at 120px 360px,#fff,transparent 40%),
                             radial-gradient(.8px .8px at 260px 140px,#e9d8ff,transparent 40%),
                             radial-gradient(.8px .8px at 420px 280px,#fff,transparent 40%);
      background-size:1260px 1260px;animation:drift3 300s linear infinite;opacity:.45}
  @keyframes drift1{to{transform:translate3d(220px,180px,0)}}
  @keyframes drift2{to{transform:translate3d(-260px,-200px,0)}}
  @keyframes drift3{to{transform:translate3d(280px,240px,0)}}
  .aurora{position:absolute;inset:0;pointer-events:none;mix-blend:screen;
    background:radial-gradient(60rem 30rem at 10% 20%,rgba(99,102,241,.16),transparent 60%),
               radial-gradient(46rem 26rem at 85% 12%,rgba(56,189,248,.14),transparent 60%),
               radial-gradient(60rem 34rem at 20% 85%,rgba(6,182,212,.12),transparent 60%)}
  .twinkle{position:absolute;width:2px;height:2px;background:#fff;border-radius:50%;opacity:.85;
    animation:twinkle 2.6s ease-in-out infinite;filter:drop-shadow(0 0 6px rgba(255,255,255,.55))}
  @keyframes twinkle{0%,100%{transform:scale(.7);opacity:.3}50%{transform:scale(1.8);opacity:1}}
  .meteor{position:absolute;width:2px;height:2px;background:linear-gradient(90deg,#fff,rgba(255,255,255,0));
    box-shadow:0 0 6px 2px rgba(255,255,255,.65);transform:rotate(18deg);opacity:0;pointer-events:none}

  .cosmos .s1{transform:translate3d(var(--px1,0),var(--py1,0),0)}
  .cosmos .s2{transform:translate3d(var(--px2,0),var(--py2,0),0)}
  .cosmos .s3{transform:translate3d(var(--px3,0),var(--py3,0),0)}

  /* ===== Base UI ===== */
  .muted{color:var(--muted)}
  .pill{display:inline-flex;align-items:center;gap:6px;padding:6px 10px;border-radius:999px;font-size:.78rem;
    background:linear-gradient(180deg,rgba(255,255,255,.08),rgba(255,255,255,.04));border:1px solid var(--line);color:var(--text)}
  .cta{display:inline-flex;align-items:center;gap:8px;font-weight:800;letter-spacing:.01em;
    padding:10px 14px;border-radius:12px;text-decoration:none;background:linear-gradient(135deg,var(--accent),var(--accent-2));
    color:#0b0f17;box-shadow:0 16px 40px rgba(56,189,248,.25);transition:transform .06s,opacity .2s,box-shadow .2s}
  .cta:hover{opacity:.98;box-shadow:0 18px 50px rgba(56,189,248,.32)}
  .cta:active{transform:translateY(1px)}
  .cta--ghost{background:transparent;color:var(--text);border:1px solid var(--line);box-shadow:none}
  .cta--ghost:hover{background:rgba(255,255,255,.06)}

  /* ===== Hero ===== */
  .hero{display:grid;grid-template-columns:1.15fr .85fr;gap:clamp(16px,4vw,32px);align-items:center;margin:clamp(10px,3vw,24px) 0}
  @media (max-width:980px){.hero{grid-template-columns:1fr}}
  .hero h1{margin:0 0 10px 0;font-weight:900;line-height:1.04;letter-spacing:-.02em;font-size:clamp(28px,5.2vw,52px);
    background:linear-gradient(90deg,#fff 10%,var(--accent) 30%,var(--accent-2) 60%,var(--accent-3) 80%,#fff 100%);
    -webkit-background-clip:text;background-clip:text;color:transparent;background-size:300% 100%;animation:heroShimmer 10s linear infinite;
    text-shadow:0 6px 30px rgba(99,102,241,.15)}
  @keyframes heroShimmer{to{background-position:300% 50%}}
  .hero p{margin:0 0 14px 0;color:var(--muted);max-width:62ch}

  /* ===== Hero card — picture space removed ===== */
  .hero-card{
    position:relative;border-radius:20px;padding:14px;
    background:linear-gradient(200deg,rgba(111,151,255,.10),rgba(185,147,255,.08) 35%,rgba(255,255,255,.03));
    border:1px solid var(--line);box-shadow:0 18px 60px rgba(0,0,0,.45);backdrop-filter: blur(6px);
  }
  .hero-card .spark{display:none !important;}

  /* Table-like stat row */
  .stat-table{
    display:grid;grid-template-columns:repeat(3,1fr);gap:12px;
  }
  @media (max-width:540px){ .stat-table{grid-template-columns:1fr 1fr} }
  .cell{
    position:relative; border:1px solid var(--line); border-radius:14px; padding:14px 12px; text-align:center;
    background:linear-gradient(180deg,rgba(255,255,255,.05),rgba(255,255,255,.02));
    transition:transform .12s ease, box-shadow .2s ease, border-color .2s;
  }
  .cell:hover{ transform:translateY(-2px); box-shadow:0 16px 40px rgba(2,8,23,.35); border-color:rgba(148,163,184,.35) }
  .cell .top{display:flex;justify-content:center;align-items:center;gap:8px;margin-bottom:6px}
  .cell b{display:block;font-size:clamp(18px,3vw,28px);font-weight:900}
  .cell .sub{font-size:.9rem;color:var(--muted)}
  .cell svg{opacity:.9}

  /* ===== Search ===== */
  .search-bar{position:sticky;top:10px;z-index:5;display:flex;gap:8px;align-items:center;backdrop-filter:blur(8px)}
  .search{position:relative}
  .input{width:min(520px,100%);padding:11px 40px 11px 42px;border-radius:12px;border:1px solid var(--line);
    background:linear-gradient(180deg,rgba(255,255,255,.04),rgba(255,255,255,.02));color:var(--text);font-size:.95rem;outline:none;
    transition:border-color .2s, box-shadow .2s}
  .input::placeholder{color:#8fa0be}
  .input:focus{border-color:var(--accent-2);box-shadow:0 0 0 4px var(--ring)}
  .i-mag{position:absolute;left:12px;top:50%;transform:translateY(-50%);opacity:.85}
  .btn-clear{position:absolute;right:8px;top:50%;transform:translateY(-50%);border:0;background:transparent;color:#9fb0d0;cursor:pointer;padding:6px;border-radius:8px}
  .btn-clear:hover{background:rgba(255,255,255,.06);color:#cfe0ff}
  .search::after{content:"";position:absolute;left:38px;right:46px;bottom:7px;height:1px;border-radius:999px;
    background:linear-gradient(90deg,transparent,rgba(99,102,241,.35),rgba(56,189,248,.35),transparent);opacity:0;transition:opacity .25s}
  .search:hover::after{opacity:.9}

  /* ===== Gallery tiles ===== */
  .grid{display:grid;gap:18px;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));justify-items:center}
  .item{width:100%;max-width:var(--card-max);transform-style:preserve-3d}
  .card{position:relative;border-radius:16px;padding:0;border:none;background:transparent;box-shadow:none;overflow:visible;transition:transform .18s ease}
  .card:hover{transform:translateY(-4px)}
  .thumb{width:100%;aspect-ratio:16/9;border-radius:16px;overflow:hidden;background:
      radial-gradient(240px 150px at 60% 30%,rgba(96,165,250,.18),transparent 60%),
      radial-gradient(300px 170px at 20% 90%,rgba(167,139,250,.16),transparent 60%),#0b0f17;position:relative;box-shadow:0 14px 40px rgba(2,8,23,.45)}
  .thumb::before{content:"";position:absolute;inset:0;background:radial-gradient(120% 80% at 80% 0%,rgba(56,189,248,.20),transparent 60%);mix-blend-mode:screen;opacity:.6;pointer-events:none}
  .thumb img{width:100%;height:100%;object-fit:cover;display:block;transform:translateZ(0) scale(1.01);transition:transform .6s cubic-bezier(.2,.7,.2,1)}
  .item:hover .thumb img{transform:scale(1.06)}
  .meta{display:flex;justify-content:space-between;align-items:center;margin:10px 2px 6px}
  .meta h3{margin:0;font-weight:800;letter-spacing:.01em}
  .desc{margin:0;color:var(--muted);font-size:.95rem}
  .item.match .thumb{outline:2px solid rgba(56,189,248,.35); box-shadow:0 18px 50px rgba(56,189,248,.18)}
  .empty{text-align:center;color:var(--muted);border:1px dashed var(--line);border-radius:12px;padding:16px;background:rgba(255,255,255,.02)}

  @media (prefers-reduced-motion:reduce){
    .s1,.s2,.s3{animation:none!important}
    .cosmos .s1,.cosmos .s2,.cosmos .s3{transform:none!important}
  }
</style>

<!-- Sky -->
<div class="cosmos" id="cosmos">
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
  <div>
    <h1>Explore Space like Google Maps</h1>
    <p>Zoom through gigapixel NASA imagery, label features, and compare changes over time — all in your browser.</p>
    <div style="display:flex;gap:10px;flex-wrap:wrap">
      <a href="#gallery" class="cta" title="Start Exploring">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0b0f17" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M14 4.5c2.5.5 5.5 3.5 6 6L13.5 17c-2.5-.5-5.5-3.5-6-6L14 4.5Z"/><path d="M14 4.5c-1 2-1.5 4.5-1 7"/>
          <path d="M5 19l2-2"/><path d="M3.5 20.5c2 0 3-1 3-3-2 0-3 1-3 3Z"/>
        </svg>
        Start Exploring
      </a>
      <span class="pill">Deep Zoom · DZI Tiles</span>
      <span class="pill">OpenSeadragon</span>
    </div>
  </div>

  <!-- hero-card with table-like stats (no picture area) -->
  <div class="hero-card">
    <div class="stat-table">
      <div class="cell">
        <div class="top">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#38bdf8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h18M3 12h18M3 17h18"/></svg>
          <b>{{ $images->count() }}</b>
        </div>
        <div class="sub">Datasets</div>
      </div>

      <!-- UPDATED: replace infinity tile with Admin Management -->
      <div class="cell">
        <div class="top">
          <!-- Shield-check admin icon -->
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M12 3l7 4v5c0 5-3.5 8.5-7 9-3.5-.5-7-4-7-9V7l7-4z"/>
            <path d="M9 12l2 2 4-4"/>
          </svg>
          <b>Admin</b>
        </div>
        <div class="sub">Management</div>
      </div>
      <!-- /UPDATED -->

      <div class="cell">
        <div class="top">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#22d3ee" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7l-8 10-5-5"/></svg>
          <b>Labels</b>
        </div>
        <div class="sub">Add + Explore</div>
      </div>
    </div>
  </div>
</section>

{{-- SEARCH --}}
<div class="search-bar">
  <div class="search">
    <svg class="i-mag" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#a4b1c8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>
    </svg>
    <input id="q" type="text" class="input" placeholder="Search datasets (e.g., Earth, Mars, Andromeda)…" oninput="filterCards()" autocomplete="off">
    <button class="btn-clear" type="button" aria-label="Clear" onclick="document.getElementById('q').value='';filterCards()">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6L6 18M6 6l12 12"/></svg>
    </button>
  </div>
  <button class="cta cta--ghost" onclick="document.getElementById('gallery').scrollIntoView({behavior:'smooth'})">Browse</button>
</div>

{{-- STATUS --}}
@if (session('status'))
  <div class="empty" style="margin-bottom:12px">{{ session('status') }}</div>
@endif

{{-- GALLERY --}}
<section id="gallery">
  @if($images->isEmpty())
    <div class="empty">No datasets yet. Datasets will appear here once uploaded by the Admin.</div>
  @else
    <div class="grid" id="cards">
      @foreach($images as $img)
        @php $thumbUrl = $img->thumbnail_path ? asset('storage/'.$img->thumbnail_path) : null; @endphp
        <article class="card item" data-title="{{ \Illuminate\Support\Str::lower($img->title) }}" data-type="{{ \Illuminate\Support\Str::lower($img->type) }}">
          <a href="{{ route('images.show', $img->id) }}" style="display:block; color:inherit">
            <div class="thumb">
              @if($thumbUrl)
                <img src="{{ $thumbUrl }}" alt="{{ $img->title }} thumbnail" loading="lazy">
              @endif
            </div>
            <div class="meta">
              <h3>{{ $img->title }}</h3>
              <span class="pill">{{ $img->type ?: 'dataset' }}</span>
            </div>
            <p class="desc">{{ \Illuminate\Support\Str::limit($img->description, 110) }}</p>
          </a>
        </article>
      @endforeach
    </div>
    <div id="noresults" class="empty" style="display:none;margin-top:12px">No matches. Try a different keyword.</div>
  @endif
</section>

<script>
  /* filter */
  function filterCards(){
    const q = document.getElementById('q').value.trim().toLowerCase();
    const cards = document.querySelectorAll('#cards .item');
    let visible = 0;
    cards.forEach(card => {
      const hay = (card.dataset.title + ' ' + card.dataset.type).toLowerCase();
      const match = q === '' ? true : hay.includes(q);
      card.classList.toggle('match', match && q !== '');
      card.style.display = match ? '' : 'none';
      if(match) visible++;
    });
    const nores = document.getElementById('noresults');
    if(nores) nores.style.display = (visible === 0) ? '' : 'none';
  }

  /* meteors */
  const sky = document.querySelector('.cosmos');
  function spawnMeteor(){
    if(document.hidden) return;
    const m = document.createElement('div');
    m.className = 'meteor';
    const startX = -80 - Math.random()*220;
    const startY = Math.random() * window.innerHeight * 0.7 + 10;
    const length = 120 + Math.random() * 160;
    const duration = 3.2 + Math.random() * 2.6;
    m.style.left = startX + 'px';
    m.style.top = startY + 'px';
    m.style.height = length + 'px';
    sky.appendChild(m);
    m.animate([
      { transform:'translate(0,0) rotate(18deg)', opacity:0 },
      { transform:'translate(58vw,38vh) rotate(18deg)', opacity:.9, offset:.22 },
      { transform:'translate(102vw,62vh) rotate(18deg)', opacity:0 }
    ], { duration: duration*1000, easing:'linear' });
    setTimeout(() => m.remove(), duration*1000 + 150);
  }
  for (let i=0;i<2;i++) setTimeout(spawnMeteor, 500*i);
  setInterval(spawnMeteor, 2400 + Math.random()*2400);

  /* parallax */
  const cosmos = document.getElementById('cosmos');
  window.addEventListener('mousemove', (e)=>{
    const x = (e.clientX / window.innerWidth - 0.5);
    const y = (e.clientY / window.innerHeight - 0.5);
    cosmos.style.setProperty('--px1', `${x*6}px`);
    cosmos.style.setProperty('--py1', `${y*6}px`);
    cosmos.style.setProperty('--px2', `${x*-8}px`);
    cosmos.style.setProperty('--py2', `${y*-8}px`);
    cosmos.style.setProperty('--px3', `${x*12}px`);
    cosmos.style.setProperty('--py3', `${y*12}px`);
  }, {passive:true});
</script>
@endsection

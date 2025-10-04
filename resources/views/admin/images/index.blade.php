@extends('layouts.app')

@section('content')
<style>
  :root{
    --bg:#050914;
    --panel: rgba(255,255,255,.04);
    --border: rgba(255,255,255,.10);
    --line: rgba(255,255,255,.12);
    --text:#e7ecf3;
    --muted:#a8b3c7;
    --accent:#38bdf8;
    --accent-2:#6366f1;
    --radius:16px;
    --shadow: 0 24px 60px rgba(0,0,0,.45);
    --ring: 0 0 0 4px rgba(56,189,248,.28);
    --danger:#ef4444;
    --danger-2:#dc2626;
  }

  /* Background */
  .space-bg{position:fixed;inset:0;overflow:hidden;z-index:0;pointer-events:none;
    background:
      radial-gradient(1200px 700px at 85% 10%, rgba(56,189,248,.08), transparent 60%),
      radial-gradient(900px 500px at 12% 88%, rgba(99,102,241,.08), transparent 60%),
      linear-gradient(180deg, #070c1a, var(--bg));
  }
  .layer{position:absolute;inset:-30vh -30vw;background-repeat:repeat;will-change:transform}
  .l1{background-image:
        radial-gradient(1.4px 1.4px at 20px 30px,#fff,transparent 40%),
        radial-gradient(1.4px 1.4px at 280px 140px,#dfe9ff,transparent 40%),
        radial-gradient(1.2px 1.2px at 520px 60px,#fff,transparent 40%);
      background-size:700px 700px;opacity:.75;animation:drift1 160s linear infinite}
  .l2{background-image:
        radial-gradient(1px 1px at 100px 80px,#fff,transparent 40%),
        radial-gradient(1px 1px at 340px 200px,#cfe0ff,transparent 40%),
        radial-gradient(1px 1px at 620px 240px,#fff,transparent 40%);
      background-size:1000px 1000px;opacity:.55;animation:drift2 220s linear infinite reverse}
  .nebula{position:absolute;inset:-40%;
      background:
        radial-gradient(60rem 36rem at 78% 18%, rgba(56,189,248,.18), transparent 60%),
        radial-gradient(50rem 30rem at 18% 82%, rgba(99,102,241,.18), transparent 62%),
        radial-gradient(70rem 44rem at 50% 52%, rgba(6,182,212,.14), transparent 70%);
      filter:blur(42px) saturate(120%);opacity:.55;animation:nebulaPulse 18s ease-in-out infinite alternate}
  @keyframes drift1{to{transform:translate3d(220px,180px,0)}}
  @keyframes drift2{to{transform:translate3d(-260px,-200px,0)}}
  @keyframes nebulaPulse{from{transform:scale(1);opacity:.55}to{transform:scale(1.06);opacity:.7}}
  .star{position:absolute;background:#fff;border-radius:50%;opacity:.85;
        animation:twinkle var(--dur,3s) ease-in-out infinite alternate;
        filter:drop-shadow(0 0 6px rgba(255,255,255,.6))}
  @keyframes twinkle{from{opacity:.25;transform:scale(.8)}to{opacity:1;transform:scale(1.35)}}
  .meteor{position:absolute;width:2px;height:2px;transform:rotate(20deg);opacity:0;filter:drop-shadow(0 0 6px #fff);
          animation:shoot var(--sDur,8s) ease-in-out var(--sDelay,0s) infinite}
  .meteor::after{content:"";position:absolute;left:-160px;top:0;width:160px;height:2px;
                 background:linear-gradient(90deg,rgba(255,255,255,0),rgba(255,255,255,.95),rgba(56,189,248,0))}
  .m1{top:12%;left:-10%;--sDelay:1.3s;--sDur:9s}
  .m2{top:32%;left:-12%;--sDelay:4.6s;--sDur:10.5s}
  .m3{top:64%;left:-14%;--sDelay:8.2s;--sDur:11.2s}
  @keyframes shoot{0%,58%{opacity:0;transform:translate3d(0,0,0) rotate(20deg)}60%{opacity:1}
                   75%{transform:translate3d(1100px,420px,0) rotate(20deg);opacity:0}100%{opacity:0}}

  .content-container{position:relative;z-index:1;padding:36px 20px 64px;max-width:1200px;margin:auto}
  .header-row{display:flex;align-items:end;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-bottom:14px}
  .title{margin:0;color:var(--text);font-weight:900;letter-spacing:.01em;line-height:1.05;
         font-size:clamp(24px,3.6vw,34px);text-shadow:0 8px 24px rgba(56,189,248,.15)}
  .subtitle{margin:4px 0 0 0;color:var(--muted);font-size:.98rem}

  .btn-primary{
    display:inline-flex;align-items:center;justify-content:center;height:44px;padding:0 18px;
    border-radius:12px;border:1px solid transparent;
    background:linear-gradient(135deg,var(--accent),var(--accent-2));
    color:#061120;font-weight:800;letter-spacing:.01em;text-decoration:none;
    box-shadow:0 18px 40px rgba(56,189,248,.25);
    transition:transform .08s,box-shadow .2s,filter .2s;
  }
  .btn-primary:hover{transform:translateY(-1px);box-shadow:0 20px 50px rgba(56,189,248,.32);filter:saturate(1.04)}
  .btn-primary:focus-visible{outline:none;box-shadow:var(--ring)}

  .grid-wrap{display:grid;gap:18px;grid-template-columns:repeat(auto-fit,minmax(260px,1fr))}
  .dataset-card{
    position:relative;border-radius:var(--radius);
    background:linear-gradient(200deg,rgba(111,151,255,.10),rgba(185,147,255,.08) 35%,rgba(255,255,255,.03));
    border:1px solid var(--border);box-shadow:var(--shadow);padding:14px;overflow:hidden;isolation:isolate;
    transform-style:preserve-3d;transition:transform .18s, box-shadow .25s, border-color .25s;
  }
  .dataset-card:hover{transform:translateY(-6px) rotateX(2deg);box-shadow:0 28px 80px rgba(2,8,23,.48);border-color:var(--line)}
  .dataset-card .shine{position:absolute;inset:0;pointer-events:none;opacity:0;transition:opacity .25s}
  .dataset-card:hover .shine{opacity:1}
  .dataset-card .shine::before{content:"";position:absolute;inset:-60%;transform:rotate(15deg);
    background:radial-gradient(420px 160px at var(--mx,60%) var(--my,20%),rgba(255,255,255,.07),transparent 42%)}

  .thumb{position:relative;border-radius:12px;overflow:hidden;border:1px solid rgba(255,255,255,.14);margin-bottom:10px;
         background:radial-gradient(240px 150px at 60% 30%,rgba(96,165,250,.20),transparent 60%),
                    radial-gradient(300px 170px at 20% 90%,rgba(167,139,250,.18),transparent 60%),#0b0f17;
         aspect-ratio:16/9}
  .thumb img{width:100%;height:100%;object-fit:cover;display:block;transform:translateZ(0) scale(1.01);opacity:.98;
             transition:transform .6s cubic-bezier(.2,.7,.2,1),opacity .25s,filter .25s}
  .dataset-card:hover .thumb img{transform:scale(1.06);filter:saturate(1.05)}
  .card-title{margin:2px 0 6px 0;color:var(--text);font-weight:800;letter-spacing:.01em}
  .card-desc{margin:0 0 10px 0;color:var(--muted);font-size:.95rem}

  .card-footer{display:flex;justify-content:flex-end;gap:8px;margin-top:8px}

  /* Edit + Delete buttons */
  .btn-edit{
    display:inline-flex;align-items:center;justify-content:center;height:38px;padding:0 14px;
    border-radius:10px;border:1px solid rgba(99,102,241,.28);
    background:linear-gradient(180deg,var(--accent-2),#4f46e5);
    color:#fff;font-weight:800;letter-spacing:.01em;text-decoration:none;
    box-shadow:0 14px 28px rgba(99,102,241,.22);
    transition:transform .08s,box-shadow .2s,filter .2s,opacity .2s;
  }
  .btn-edit:hover{transform:translateY(-1px);box-shadow:0 18px 40px rgba(99,102,241,.28);filter:saturate(1.03)}
  .btn-edit:focus-visible{outline:none;box-shadow:0 0 0 6px rgba(99,102,241,.25)}

  .btn-danger{
    display:inline-flex;align-items:center;justify-content:center;height:38px;padding:0 14px;
    border-radius:10px;border:1px solid rgba(239,68,68,.28);
    background:linear-gradient(180deg,var(--danger),var(--danger-2));
    color:#fff;font-weight:800;letter-spacing:.01em;cursor:pointer;
    box-shadow:0 14px 28px rgba(239,68,68,.22);
    transition:transform .08s,box-shadow .2s,filter .2s,opacity .2s;
  }
  .btn-danger:hover{transform:translateY(-1px);box-shadow:0 18px 40px rgba(239,68,68,.28);filter:saturate(1.03)}
  .btn-danger:active{transform:translateY(0)}
  .btn-danger:focus-visible{outline:none;box-shadow:0 0 0 6px rgba(239,68,68,.25)}

  @media (prefers-reduced-motion:reduce){
    .l1,.l2,.nebula,.meteor,.star{animation:none!important}
    .dataset-card:hover{transform:none}
    .btn-primary:hover,.btn-danger:hover,.btn-edit:hover{transform:none}
  }
</style>

<div class="space-bg">
  <div class="nebula"></div>
  <div class="layer l1"></div>
  <div class="layer l2"></div>

  @for ($i = 0; $i < 120; $i++)
    <div class="star" style="--dur: {{ rand(2,5) }}s; top:{{ rand(0,100) }}%; left:{{ rand(0,100) }}%; width:{{ rand(1,3) }}px; height:{{ rand(1,3) }}px;"></div>
  @endfor

  <div class="meteor m1"></div>
  <div class="meteor m2"></div>
  <div class="meteor m3"></div>
</div>

<div class="content-container">
  <div class="header-row">
    <div>
      <h1 class="title">Manage NASA Datasets</h1>
      <p class="subtitle">Curate, organize, and explore high-resolution imagery with a focused, elegant UI.</p>
    </div>

    <a href="{{ route('admin.images.create') }}" class="btn-primary">Upload New Image</a>
    {{-- Removed stray Edit link that referenced $img outside the loop --}}
  </div>

  @if($images->isEmpty())
    <p style="color:var(--muted);margin-top:8px">No datasets uploaded yet.</p>
  @else
    <div class="grid-wrap" id="cards">
      @foreach($images as $img)
        <article class="dataset-card">
          <span class="shine" aria-hidden="true"></span>

          <h3 class="card-title">{{ $img->title }}</h3>

          <div class="thumb">
            <img src="{{ asset('storage/'.$img->thumbnail_path) }}" alt="{{ $img->title }}" loading="lazy">
          </div>

          <p class="card-desc">{{ $img->description }}</p>

          <div class="card-footer">
            <a href="{{ route('admin.images.edit', $img->id) }}" class="btn-edit">Edit</a>

            <form action="{{ route('admin.images.destroy', $img->id) }}" method="POST" onsubmit="return confirmDelete('{{ addslashes($img->title) }}')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn-danger">Delete</button>
            </form>
          </div>
        </article>
      @endforeach
    </div>
  @endif
</div>

<script>
  document.querySelectorAll('.dataset-card').forEach(card=>{
    card.addEventListener('mousemove', e=>{
      const r = card.getBoundingClientRect();
      card.style.setProperty('--mx', ((e.clientX - r.left)/r.width)*100 + '%');
      card.style.setProperty('--my', ((e.clientY - r.top)/r.height)*100 + '%');
    });
    card.addEventListener('mouseleave', ()=>{
      card.style.setProperty('--mx','60%'); card.style.setProperty('--my','20%');
    });
  });

  function confirmDelete(name){
    return confirm(`Delete “${name}”? This action cannot be undone.`);
  }
</script>
@endsection

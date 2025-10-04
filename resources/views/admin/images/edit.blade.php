@extends('layouts.app')

@section('content')
<style>
  :root{
    --ink:#e8eef7; --muted:#9fb0c9;
    --bg:#050816; --bg2:#0a1222;
    --panel-1:rgba(255,255,255,.06); --panel-2:rgba(255,255,255,.03);
    --stroke:rgba(255,255,255,.12); --ring:0 0 0 6px rgba(56,189,248,.25);
    --indigo:#6366f1; --sky:#38bdf8; --cyan:#06b6d4;
    --danger:#ef4444; --danger-2:#dc2626;
    --radius:18px; --rsm:12px;
    --shadow-lg:0 28px 100px rgba(2,8,23,.55);
  }

  /* Subtle animated space background */
  .sky{
    position:fixed; inset:0; z-index:0; pointer-events:none; overflow:hidden;
    background:
      radial-gradient(1200px 700px at 80% 10%, rgba(56,189,248,.08), transparent 60%),
      radial-gradient(900px 520px at 12% 88%, rgba(99,102,241,.08), transparent 60%),
      linear-gradient(180deg, #070c1a, var(--bg));
  }
  .sky .stars{ position:absolute; inset:-30% -30%; background-repeat:repeat; opacity:.65; }
  .s1{ background-image:
        radial-gradient(1.6px 1.6px at 30px 40px,#fff,transparent 40%),
        radial-gradient(1.4px 1.4px at 260px 160px,#eaf2ff,transparent 40%),
        radial-gradient(1.2px 1.2px at 520px 60px,#fff,transparent 40%);
       background-size:700px 700px; animation: drift1 160s linear infinite; }
  .s2{ background-image:
        radial-gradient(1.1px 1.1px at 120px 80px,#fff,transparent 40%),
        radial-gradient(1px 1px at 340px 220px,#dfe8ff,transparent 40%),
        radial-gradient(1px 1px at 620px 260px,#fff,transparent 40%);
       background-size:1000px 1000px; opacity:.55; animation: drift2 220s linear infinite reverse; }
  @keyframes drift1{ to{ transform: translate3d(240px,180px,0)}}
  @keyframes drift2{ to{ transform: translate3d(-260px,-200px,0)}}

  /* Foreground container (glass card) */
  .wrap{ position:relative; z-index:1; display:grid; place-items:center; padding:40px 16px; }
  .card{
    width:min(920px, 94vw);
    border-radius: var(--radius);
    border:1px solid var(--stroke);
    background: linear-gradient(180deg, var(--panel-1), var(--panel-2));
    box-shadow: var(--shadow-lg);
    padding: 26px;
    backdrop-filter: blur(14px) saturate(140%);
  }
  .heading{
    display:flex; align-items:center; justify-content:space-between; gap:14px; margin-bottom:14px;
  }
  .title{
    margin:0; font-weight:900; letter-spacing:.01em; line-height:1.05;
    font-size: clamp(22px, 3vw, 30px);
    background: linear-gradient(90deg,#fff 0%, var(--sky) 30%, var(--indigo) 65%, #fff 100%);
    -webkit-background-clip:text; background-clip:text; color:transparent; background-size:300% 100%;
    animation: shimmer 12s linear infinite;
  }
  @keyframes shimmer{ to{ background-position:300% 50% } }

  /* Form */
  .grid{ display:grid; gap:16px; }
  .label{ display:block; color:var(--muted); font-size:.92rem; margin-bottom:6px; }
  .input, .textarea{
    width:100%; color:var(--ink);
    border-radius: 14px; border:1px solid var(--stroke);
    background: rgba(255,255,255,.04);
    padding: 12px 14px; font-size: .98rem; outline: none;
    transition: box-shadow .2s ease, border-color .2s ease, background .2s ease, transform .06s ease;
  }
  .textarea{ min-height: 120px; resize: vertical; }
  .input:hover, .textarea:hover{ background: rgba(255,255,255,.06) }
  .input:focus, .textarea:focus{ border-color: var(--sky); box-shadow: var(--ring); }

  /* Preview panel */
  .preview{
    border:1px solid var(--stroke); border-radius:14px; overflow:hidden;
    background: #0b1220; padding:12px; display:flex; align-items:center; gap:14px;
  }
  .thumb{
    width: 240px; height: 150px; border-radius:10px; overflow:hidden;
    border:1px solid rgba(255,255,255,.14); flex:0 0 auto;
    box-shadow: 0 14px 30px rgba(0,0,0,.35);
  }
  .thumb img{ width:100%; height:100%; object-fit:cover; display:block; }

  /* Buttons */
  .actions{ display:flex; gap:10px; justify-content:flex-end; margin-top:6px; }
  .btn{
    display:inline-flex; align-items:center; gap:8px; cursor:pointer; border:0;
    padding:11px 16px; border-radius:12px; font-weight:800; letter-spacing:.01em; font-size:.95rem;
    transition: transform .06s ease, box-shadow .2s ease, filter .2s ease, opacity .2s ease;
  }
  .btn:active{ transform: translateY(1px) }
  .btn-primary{
    color:#fff; background: linear-gradient(135deg, var(--indigo), var(--sky));
    box-shadow: 0 18px 44px rgba(56,189,248,.28);
  }
  .btn-primary:hover{ filter:saturate(1.04) }
  .btn-ghost{
    color: var(--ink); background: transparent; border:1px solid var(--stroke);
  }
  .btn-ghost:hover{ background: rgba(255,255,255,.05) }
</style>

<!-- Background -->
<div class="sky" aria-hidden="true">
  <div class="stars s1"></div>
  <div class="stars s2"></div>
</div>

<div class="wrap">
  <div class="card">
    <div class="heading">
      <h1 class="title">🪐 Edit Image Details</h1>
    </div>

    <form action="{{ route('admin.images.update', $image->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="grid">
        <!-- Title -->
        <div>
          <label class="label">Title</label>
          <input type="text" name="title" value="{{ old('title', $image->title) }}" class="input">
        </div>

        <!-- Description -->
        <div>
          <label class="label">Description</label>
          <textarea name="description" rows="4" class="textarea">{{ old('description', $image->description) }}</textarea>
        </div>

        <!-- Type -->
        <div>
          <label class="label">Category / Type</label>
          <input type="text" name="type" value="{{ old('type', $image->type) }}" class="input">
        </div>

        <!-- Preview -->
        <div>
          <label class="label">Current Thumbnail</label>
          <div class="preview">
            @if($image->thumbnail_path)
              <div class="thumb">
                <img src="{{ asset('storage/'.$image->thumbnail_path) }}" alt="Current thumbnail">
              </div>
              <div style="color:var(--muted); font-size:.92rem;">
                <div><strong style="color:var(--ink)">Preview</strong></div>
                <div>Shown from stored thumbnail. Replace by re-uploading the original image on the upload page.</div>
              </div>
            @else
              <div style="color:var(--muted)">No thumbnail available</div>
            @endif
          </div>
        </div>

        <!-- Actions -->
        <div class="actions">
          <a href="{{ route('admin.images.index') }}" class="btn btn-ghost">← Cancel</a>
          <button type="submit" class="btn btn-primary">
            💾 Save Changes
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<style>
  :root {
    --ink:#e8eef7; --muted:#9fb0c9;
    --bg:#050816; --bg2:#0a1222;
    --panel-1:rgba(255,255,255,.06); --panel-2:rgba(255,255,255,.03);
    --stroke:rgba(255,255,255,.12); --ring:0 0 0 6px rgba(56,189,248,.25);
    --indigo:#6366f1; --sky:#38bdf8; --cyan:#06b6d4;
    --danger:#ef4444;
    --radius:18px; --rsm:12px;
    --shadow-lg:0 28px 100px rgba(2,8,23,.55);
  }

  body { background: var(--bg); color: var(--ink); }

  /* Starfield background */
  .sky {
    position:fixed; inset:0; z-index:0; pointer-events:none;
    background:
      radial-gradient(1200px 700px at 80% 10%, rgba(56,189,248,.08), transparent 60%),
      radial-gradient(900px 520px at 12% 88%, rgba(99,102,241,.08), transparent 60%),
      linear-gradient(180deg, #070c1a, var(--bg));
  }
  .sky::before, .sky::after {
    content:""; position:absolute; inset:-50%; background-repeat:repeat;
    opacity:.5; animation: drift 220s linear infinite;
  }
  .sky::before {
    background-image:
      radial-gradient(1.4px 1.4px at 50px 100px, #fff, transparent 40%),
      radial-gradient(1.2px 1.2px at 400px 200px, #cfdfff, transparent 40%);
  }
  .sky::after {
    background-image:
      radial-gradient(1.1px 1.1px at 300px 150px, #fff, transparent 40%),
      radial-gradient(1px 1px at 100px 500px, #d0e0ff, transparent 40%);
    animation-direction: reverse;
  }
  @keyframes drift { to { transform: translate3d(200px,150px,0); } }

  /* Card */
  .wrap { position:relative; z-index:1; display:grid; place-items:center; padding:48px 16px; }
  .card {
    width:min(960px,94vw);
    border:1px solid var(--stroke);
    background:linear-gradient(180deg,var(--panel-1),var(--panel-2));
    border-radius:var(--radius);
    box-shadow:var(--shadow-lg);
    padding:36px;
    backdrop-filter:blur(18px) saturate(160%);
    animation:fadeIn 0.8s ease both;
  }
  @keyframes fadeIn { from {opacity:0; transform:translateY(16px);} to {opacity:1; transform:none;} }

  /* Header */
  .heading { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; }
  .title {
    margin:0;
    font-weight:900;
    font-size:clamp(24px,3vw,34px);
    background:linear-gradient(90deg,#fff 0%,var(--sky) 35%,var(--indigo) 65%,#fff 100%);
    -webkit-background-clip:text; color:transparent; background-size:300% 100%;
    animation:shimmer 12s linear infinite;
  }
  @keyframes shimmer { to { background-position:300% 50%; } }

  /* Form */
  .grid { display:grid; gap:20px; }
  .label { display:block; color:var(--muted); font-size:.92rem; margin-bottom:6px; }
  .input, .textarea {
    width:100%; color:var(--ink);
    border-radius:12px; border:1px solid var(--stroke);
    background:rgba(255,255,255,.04);
    padding:12px 14px; font-size:.97rem; outline:none;
    transition:border-color .2s ease, box-shadow .2s ease, background .2s ease;
  }
  .textarea { min-height:120px; resize:vertical; }
  .input:hover, .textarea:hover { background:rgba(255,255,255,.06); }
  .input:focus, .textarea:focus { border-color:var(--sky); box-shadow:var(--ring); }

  /* Preview section */
  .preview {
    border:1px solid var(--stroke);
    border-radius:14px;
    background:#0b1220;
    padding:14px;
    display:flex; gap:18px; align-items:center;
  }
  .thumb {
    width:260px; height:160px;
    border-radius:12px; overflow:hidden;
    border:1px solid rgba(255,255,255,.14);
    flex:0 0 auto;
    box-shadow:0 14px 30px rgba(0,0,0,.35);
  }
  .thumb img { width:100%; height:100%; object-fit:cover; }

  /* Actions */
  .actions { display:flex; gap:12px; justify-content:flex-end; margin-top:12px; }
  .btn {
    display:inline-flex; align-items:center; justify-content:center; gap:8px;
    padding:11px 18px; border-radius:12px; font-weight:700; font-size:.95rem;
    border:1px solid transparent; cursor:pointer;
    transition:all .2s ease;
  }
  .btn:active { transform:translateY(1px); }
  .btn-primary {
    background:linear-gradient(135deg,var(--indigo),var(--sky));
    color:#fff; box-shadow:0 16px 42px rgba(56,189,248,.25);
  }
  .btn-primary:hover { filter:brightness(1.05); }
  .btn-ghost {
    color:var(--ink);
    background:transparent;
    border:1px solid var(--stroke);
  }
  .btn-ghost:hover { background:rgba(255,255,255,.05); }

  small.helper {
    display:block;
    color:var(--muted);
    font-size:.85rem;
    margin-top:6px;
  }
</style>

<div class="sky"></div>

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
          <input type="text" name="title" value="{{ old('title', $image->title) }}" class="input" required>
        </div>

        <!-- Description -->
        <div>
          <label class="label">Description</label>
          <textarea name="description" rows="4" class="textarea" placeholder="Enter a short description...">{{ old('description', $image->description) }}</textarea>
        </div>

        <!-- Type -->
        <div>
          <label class="label">Category / Type</label>
          <input type="text" name="type" value="{{ old('type', $image->type) }}" class="input" placeholder="e.g. Galaxy, Nebula, Planet">
        </div>

        <!-- Current Preview -->
        <div>
          <label class="label">Current Thumbnail</label>
          <div class="preview">
            @if($image->thumbnail_path)
              <div class="thumb">
                <img src="{{ asset('storage/'.$image->thumbnail_path) }}" alt="Thumbnail">
              </div>
              <div style="color:var(--muted);font-size:.93rem;">
                <div><strong style="color:var(--ink)">Current Preview</strong></div>
                <div>Displayed from stored thumbnail. You can replace it below.</div>
              </div>
            @else
              <div style="color:var(--muted)">No thumbnail available.</div>
            @endif
          </div>
        </div>

        <!-- Replace Image -->
        <div>
          <label class="label">Replace Image</label>
          <input type="file" name="image" accept="image/jpeg" class="input">
          <small class="helper">Upload a new JPG image to replace the existing one. Leave empty to keep current.</small>
        </div>

        <!-- Actions -->
        <div class="actions">
          <a href="{{ route('admin.images.index') }}" class="btn btn-ghost">← Cancel</a>
          <button type="submit" class="btn btn-primary">
            🔁 Update Image
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

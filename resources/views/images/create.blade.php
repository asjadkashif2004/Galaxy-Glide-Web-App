{{-- resources/views/images/create.blade.php --}}
@extends('layouts.app')

@section('content')
  <style>
    /* Scoped styling for this page to avoid side effects elsewhere */
    .upload-page {
      --bg: #0b0f17;
      --surface: #0f1521;
      --surface-2: #0c121d;
      --border: rgba(255,255,255,0.08);
      --text: #e7ecf3;
      --muted: #9aa4b2;
      --brand: #4ea1ff;
      --brand-2: #2f7de7;
      --ring: rgba(78,161,255,0.35);
      --error: #ef4444;

      display: grid;
      gap: clamp(16px, 2.5vw, 24px);
      padding: clamp(16px, 4vw, 36px) clamp(12px, 3vw, 28px);
      color: var(--text);
    }

    .upload-page a.fab-back {
      position: fixed;
      top: clamp(10px, 2vw, 16px);
      left: clamp(10px, 2vw, 16px);
      width: 42px; height: 42px;
      display: inline-grid; place-items: center;
      border-radius: 999px;
      background: var(--surface);
      border: 1px solid var(--border);
      box-shadow: 0 8px 24px rgba(0,0,0,.3);
      transition: transform .12s ease, box-shadow .2s ease, background .2s ease;
    }
    .upload-page a.fab-back:hover { transform: translateY(-1px); }
    .upload-page a.fab-back:focus-visible { outline: none; box-shadow: 0 0 0 6px var(--ring); }
    .upload-page a.fab-back svg path { stroke: var(--text); opacity: .9; }

    .upload-wrap {
      width: 100%;
      max-width: 860px;
      margin: 0 auto;
      background: linear-gradient(180deg, var(--surface), var(--surface-2));
      border: 1px solid var(--border);
      border-radius: 20px;
      box-shadow: 0 16px 40px rgba(0,0,0,.35);
      padding: clamp(16px, 3vw, 28px);
    }

    .upload-head {
      margin-bottom: clamp(10px, 2vw, 16px);
    }
    .upload-head h1 {
      margin: 0 0 8px 0;
      font-size: clamp(20px, 2.5vw, 28px);
      line-height: 1.15;
      letter-spacing: -0.01em;
      font-weight: 800;
    }
    .upload-head .muted { color: var(--muted); }

    .card {
      background: rgba(255,255,255,0.02);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: clamp(14px, 2vw, 20px);
    }

    /* Errors */
    .card.errors {
      border-color: rgba(239,68,68,.35);
      background: rgba(239,68,68,.06);
      color: #ffe2e2;
      margin-bottom: 12px;
    }
    .card.errors ul { margin: 0; padding-left: 18px; }

    /* Form grid */
    .form-grid {
      display: grid;
      gap: 14px;
      grid-template-columns: 1fr 1fr;
    }
    @media (max-width: 820px) {
      .form-grid { grid-template-columns: 1fr; }
    }
    .form-grid .full { grid-column: 1 / -1; }

    /* Labels & inputs */
    .label-strong {
      font-weight: 700;
      font-size: 13px;
      letter-spacing: .02em;
      color: var(--muted);
      margin-bottom: 8px;
      text-transform: uppercase;
      display: block;
    }

    .input, textarea.input, input[type="file"].input {
      width: 100%;
      background: linear-gradient(180deg, rgba(255,255,255,.04), rgba(255,255,255,.02));
      border: 1px solid var(--border);
      color: var(--text);
      border-radius: 12px;
      padding: 12px 14px;
      font-size: 15px;
      line-height: 1.4;
      transition: border-color .15s ease, box-shadow .15s ease, background .15s ease;
    }
    .input:focus, textarea.input:focus, input[type="file"].input:focus {
      outline: none;
      border-color: var(--brand);
      box-shadow: 0 0 0 6px var(--ring);
      background: linear-gradient(180deg, rgba(78,161,255,.08), rgba(255,255,255,.03));
    }
    textarea.input { min-height: 96px; resize: vertical; }

    /* File input (kept simple, no JS) */
    input[type="file"].input {
      padding: 10px 12px;
    }
    .helper { display:block; margin-top:6px; font-size:12px; color: var(--muted); }

    /* Actions */
    .actions { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 16px; }
    .cta, .btn-ghost {
      display: inline-flex; align-items: center; gap: 8px;
      border-radius: 12px;
      padding: 11px 16px;
      font-weight: 700; font-size: 14px;
      border: 1px solid transparent;
      transition: transform .06s ease, box-shadow .15s ease, background .15s ease, color .15s ease, border-color .15s ease;
      white-space: nowrap;
    }
    .cta {
      background: linear-gradient(180deg, var(--brand), var(--brand-2));
      color: #fff;
      box-shadow: 0 10px 24px rgba(47,125,231,.28);
    }
    .cta:hover { transform: translateY(-1px); }
    .cta:focus-visible { outline: none; box-shadow: 0 0 0 6px var(--ring); }

    .btn-ghost {
      background: transparent;
      color: var(--text);
      border-color: var(--border);
    }
    .btn-ghost:hover { color: var(--brand); border-color: var(--brand); }
    .btn-ghost:focus-visible { outline: none; box-shadow: 0 0 0 6px var(--ring); }
  </style>

  <a href="{{ route('home') }}" class="fab-back" aria-label="Back to gallery">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
      <path d="M15 18l-6-6 6-6" stroke="#0b0f17" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </a>

  <div class="upload-page">
    <div class="upload-wrap">
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

      <form method="POST" action="{{ route('images.store') }}" enctype="multipart/form-data" class="card" novalidate>
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
            <small class="helper">Tip: Bigger is better. Very large JPGs will tile beautifully.</small>
          </label>
        </div>

        <div class="actions">
          <button class="cta" type="submit">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" style="margin-right:6px">
              <path d="M12 3v12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M7.5 8.5L12 4l4.5 4.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M5 15v3a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3v-3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Upload & Convert
          </button>

          <a href="{{ route('home') }}" class="btn-ghost">Cancel</a>
        </div>
      </form>
    </div>
  </div>
@endsection

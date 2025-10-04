@extends('layouts.app')

@section('content')
<style>
  /* Starry animated background */
  .space-bg {
    position: fixed;
    inset: 0;
    background: radial-gradient(ellipse at bottom, #1b2735 0%, #090a0f 100%);
    overflow: hidden;
    z-index: 0;
  }

  .star {
    position: absolute;
    background: white;
    border-radius: 50%;
    opacity: 0.8;
    animation: twinkle 3s infinite ease-in-out alternate;
  }

  @keyframes twinkle {
    from { opacity: 0.2; transform: scale(0.8); }
    to { opacity: 1; transform: scale(1.2); }
  }

  /* Container above stars */
  .content-container {
    position: relative;
    z-index: 1;
    padding: 40px 20px;
    max-width: 1200px;
    margin: auto;
  }

  /* Cards */
  .dataset-card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    padding: 20px;
    backdrop-filter: blur(12px);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }

  .dataset-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.45);
  }

  .dataset-card img {
    border-radius: 12px;
    transition: transform 0.3s ease;
  }

  .dataset-card img:hover {
    transform: scale(1.05);
  }

  /* Buttons */
  .btn-primary {
    display: inline-block;
    background: linear-gradient(90deg, #4ea1ff, #2f7de7);
    color: #fff;
    font-weight: 600;
    padding: 10px 18px;
    border-radius: 10px;
    transition: all 0.2s ease;
  }

  .btn-primary:hover {
    background: linear-gradient(90deg, #2f7de7, #1c52b9);
    transform: translateY(-2px);
  }

  .btn-danger {
    background: rgba(239, 68, 68, 0.9);
    color: #fff;
    font-weight: 600;
    padding: 8px 14px;
    border-radius: 8px;
    border: none;
    transition: background 0.2s ease;
  }

  .btn-danger:hover {
    background: rgba(220, 38, 38, 1);
  }
</style>

<!-- Starry Background -->
<div class="space-bg">
  @for ($i = 0; $i < 100; $i++)
    <div class="star" style="top:{{ rand(0,100) }}%; left:{{ rand(0,100) }}%; width:{{ rand(1,3) }}px; height:{{ rand(1,3) }}px; animation-duration:{{ rand(2,5) }}s;"></div>
  @endfor
</div>

<!-- Content -->
<div class="content-container">
  <h1 class="text-3xl font-extrabold text-white mb-6">📂 Manage NASA Datasets</h1>

  <!-- Upload new image -->
  <a href="{{ route('admin.images.create') }}" class="btn-primary mb-6 inline-block">
    + Upload New Image
  </a>

  <!-- Show existing images -->
  @if($images->isEmpty())
      <p class="text-gray-400">No datasets uploaded yet.</p>
  @else
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
          @foreach($images as $img)
              <div class="dataset-card">
                  <h3 class="text-xl font-bold text-white mb-2">{{ $img->title }}</h3>

                  <img src="{{ asset('storage/'.$img->thumbnail_path) }}" 
                       class="w-full h-48 object-cover mb-3">

                  <p class="text-gray-300 text-sm mb-3">{{ $img->description }}</p>

                  <form action="{{ route('admin.images.destroy', $img->id) }}" method="POST">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn-danger">
                          ❌ Delete
                      </button>
                  </form>
              </div>
          @endforeach
      </div>
  @endif
</div>
@endsection

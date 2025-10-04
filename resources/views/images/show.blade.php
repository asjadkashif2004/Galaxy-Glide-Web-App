@extends('layouts.app')

@section('content')
  {{-- Floating Back → always go to front page --}}
  <a href="{{ route('home') }}" class="fab-back" title="Back to gallery" aria-label="Back to gallery">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
      <path d="M15 18l-6-6 6-6" stroke="#0b0f17" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </a>

  <div class="container">
    <h1 class="text-2xl font-bold mb-4">{{ $image->title }}</h1>
    <div id="openseadragon" style="width: 100%; height: 600px; background:black;"></div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/openseadragon/3.1.0/openseadragon.min.js"></script>
  <script>
    OpenSeadragon({
      id: "openseadragon",
      prefixUrl: "https://cdnjs.cloudflare.com/ajax/libs/openseadragon/3.1.0/images/",

      // — Better zoom & feel —
      showNavigator: true,
      navigatorAutoFade: true,
      zoomPerClick: 1.6,        // click to zoom amount
      zoomPerScroll: 1.35,      // wheel zoom speed
      animationTime: 0.9,       // smoothness
      springStiffness: 6.0,

      // — Go beyond native resolution (digital zoom) —
      maxZoomPixelRatio: 8,     // try 8–12 if you want even more
      minZoomImageRatio: 0.75,
      visibilityRatio: 1.0,
      constrainDuringPan: false,

      // DZI source from storage
      tileSources: "{{ asset('storage/' . $image->dzi_path) }}"
    });
  </script>
@endsection

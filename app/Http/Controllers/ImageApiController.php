<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageApiController extends Controller {
  public function meta(\App\Models\Image $image) {
    return response()->json($image);
  }
}

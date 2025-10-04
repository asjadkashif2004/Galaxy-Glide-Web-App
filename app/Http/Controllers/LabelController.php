<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LabelController extends Controller {
  public function index(\App\Models\Image $image) {
    return $image->labels()->latest()->get(['id','feature_name','nx','ny','description']);
  }
  public function store(Request $req, \App\Models\Image $image) {
    $data = $req->validate([
      'feature_name' => 'required|string|max:120',
      'nx' => 'required|numeric|min:0|max:1',
      'ny' => 'required|numeric|min:0|max:1',
      'description' => 'nullable|string'
    ]);
    $data['user_id'] = auth()->id();
    $image->labels()->create($data);
    return response()->noContent();
  }
}

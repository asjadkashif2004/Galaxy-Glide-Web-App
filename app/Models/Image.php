<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    
  protected $fillable = [
    'title',
    'type',
    'description',
    'mission',
    'nasa_id',
    'taken_at',
    'thumbnail_path',
    'dzi_path',
];

  
  public function labels() { return $this->hasMany(Label::class); }

    
}

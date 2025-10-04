<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
     protected $fillable = ['title','description','type','dzi_path'];
  public function labels() { return $this->hasMany(Label::class); }

    
}

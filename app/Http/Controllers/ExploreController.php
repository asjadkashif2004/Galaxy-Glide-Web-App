<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExploreController extends Controller
{
   
    public function index()
    {
        // return your explore page
        return view('explore.index');
    }
}

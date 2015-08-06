<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GalleryController extends Controller
{
    public function index($host){
        return view('gallery.index', compact('host'));
    }
}

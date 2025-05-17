<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeknisiController extends Controller
{
    public function perbaikan()
    {
        return view('pages.teknisi.perbaikan.index');
    }
}

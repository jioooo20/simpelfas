<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SarpraController extends Controller
{
    public function dasbor()
    {
        return view('pages.sarpra.dasbor.index');
    }
}

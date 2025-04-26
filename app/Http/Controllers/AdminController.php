<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dasbor()
    {
        return view('pages.admin.dasbor.index');
    }
}

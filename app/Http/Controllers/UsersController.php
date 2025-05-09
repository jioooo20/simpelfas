<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        return view('pages.users.laporan.index');
    }


}

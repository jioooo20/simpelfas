<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        return view('pages.users.laporan.index');
    }

    public function statusLaporan()
    {
        return view('pages.users.status-laporan.index');
    }
    public function UmpanBalik() {
        return view ('pages.users.feedback.index');
    }

    public function UmpanBalik_Create() {
        return view ('pages.users.feedback.create');
    }

    public function store() {
        
    }


}

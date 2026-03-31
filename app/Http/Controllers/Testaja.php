<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Testaja extends Controller
{
    public $search;
    public function index () {
        return view('testaja');
    }
}

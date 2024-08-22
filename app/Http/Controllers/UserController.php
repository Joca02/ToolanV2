<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return response()
            ->view('home')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }
}

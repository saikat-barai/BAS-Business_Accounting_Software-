<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function admin_login(){
        return view('backend.auth.login');
    }

    public function admin_register(){
        return view('backend.auth.register');
    }
}

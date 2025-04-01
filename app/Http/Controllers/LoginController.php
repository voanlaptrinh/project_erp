<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function showform()
    {
        if(\Auth::check()){
            return redirect()->route('home'); // Chuyển hướng đến trang chính
        }
        return view('login');
    }
    
}

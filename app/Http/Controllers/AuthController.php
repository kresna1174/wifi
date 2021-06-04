<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index() {
        return view('login');
    }

    public function login(Request $request) {
        if(Auth::attempt(['name' => $request->username, 'password' => $request->password])) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('login')->with('error', 'username atau kata sandi salah');
        }
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('login-index');
    }
}

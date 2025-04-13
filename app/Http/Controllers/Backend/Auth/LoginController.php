<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
   public function loginForm()
   {
      return view('backend.auth.login');
   }

   public function registerForm()
   {
      return view('backend.auth.register');
   }
   
    public function forgotPasswordForm()
    {
        return view('backend.auth.forgot-password');
    }
}

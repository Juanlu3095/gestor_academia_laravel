<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /*
    * It returns the login form.
    */
    public function loginForm()
    {
        return view('login');
    }

    /*
    * It returns the register form.
    */
    public function registerForm()
    {
        return view('register');
    }

    /*
    * It allows to login for user.
    */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
 
            return redirect()->route('welcome');
        } else {
            abort(401);
        }
    }

    /*
    * It allows to register an user.
    */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ]);

        $user = User::createUser($request);

        if(!$user) {
            abort(404);
        }

        return redirect(route('login'));
    }

    /*
    * It allows the user to logout.
    */
    public function logout(Request $request)
    {
        Auth::logout();
 
        $request->session()->invalidate();
     
        $request->session()->regenerateToken();
     
        return redirect('/login');
    }

    public function prueba_user_model ()
    {
        $users = DB::table('users')->get();
        return view('welcome', compact('users'));
    }
}

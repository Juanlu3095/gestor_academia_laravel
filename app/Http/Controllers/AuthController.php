<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
 
        // No se puede usar un Form Request, pero sí validate aquí ya que éste devuelve un array y no el Form Request
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
 
            return redirect()->route('welcome');
        } else {
            return back()->withErrors([ // Devolvemos a la página del login con los errores
                'email' => 'Email y/o contraseña no válidos.', // Estos errores los mostramos directamente en blade con $errors
            ])->onlyInput('email');
        }
    }

    /*
    * It allows to register an user.
    */
    public function register(RegisterRequest $request)
    {
        /* $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ]); */

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
    
}

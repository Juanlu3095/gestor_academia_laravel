<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
    * It returns the login form.
    * @return \Illuminate\View\View
    */
    public function loginForm()
    {
        return view('login');
    }

    /**
    * It returns the register form.
    * @return \Illuminate\View\View
    */
    public function registerForm()
    {
        return view('register');
    }

    /**
    * It allows the user to login.
    * @param Illuminate\Http\Request $request
    * @return Redirect
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

    /**
    * It allows to register an user. Returns 404 if error.
    * @param RegisterRequest $request
    * @return Redirect
    * @throws \Symfony\Component\HttpKernel\Exception\HttpException 
    */
    public function register(RegisterRequest $request)
    {
        $user = User::createUser($request);

        if(!$user) {
            abort(404);
        }

        return redirect(route('login'));
    }

    /**
    * It allows the user to logout.
    * @param Illuminate\Http\Request $request
    * @return Redirect
    */
    public function logout(Request $request)
    {
        Auth::logout();
 
        $request->session()->invalidate();
     
        $request->session()->regenerateToken();
     
        return redirect('/login');
    }
    
}

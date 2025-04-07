<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /*
    * It returns the current user page.
    */
    public function index()
    {
        $userId = Auth::user()->id; // Obtención del usuario con Eloquent
        $userJson = User::getUser($userId); // Podríamos usar sólo Eloquent pero queremos usar query builder
        $user = json_decode($userJson, true); // $userJson es algo como: [{""}]. Esto es por usar get() y no first() en la consulta.
        return view('perfil', compact('user'));
    }

    /*
    * It updates the current user data.
    */
    public function updateCurrentUserData(UserRequest $request)
    {
        $userId = Auth::user()->id;
        $user = User::updateUser($userId, $request);

        if(!$user) {
            abort(404);
        }

        return redirect()->route('perfil');

        /* $keys = array_keys($request->all()); // cuidado contiene el token csrf
        $values = array_values($request->all());
        $keys_list = [];
        $values_list = [];
        for($i = 1; $i < count($keys); $i++) {
            array_push($keys_list, $keys[$i]); // Añadimos todos los valores de cada array a excepción del token
            array_push($values_list, $values[$i]);
        }
        $keys_string = implode(', ', $keys_list); // Convertimos a string: ('pepe', 'pepe@gmail.es')
        $values_string = implode(', ', $values_list); */
        
        /* $array = $request->except('_token');
        $keys = array_keys($array);
        $values = array_values($array);
        function unir_arrays($array) {
            foreach($array as $key => $value) {
                return [$key => $value];
            }
        } 
        print_r(unir_arrays($array)); */

        // TONTERÍA: SE LE PUEDE PASAR DIRECTAMENTE TODA LA REQUEST A LA SOLICITUD PATCH
        // dd(implode(', ' ,$request->except('_token')));
    }

    public function deleteCurrentUser(Request $request)
    {
        $userId = Auth::user()->id;
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user = User::deleteUser($userId);

        if(!$user) {
            abort(404);
        }

        return redirect()->route('login');
    }
}

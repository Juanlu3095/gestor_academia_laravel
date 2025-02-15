<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function getUser(string $id)
    {
        try {
            $user = DB::table('users')
                ->select('id', 'name', 'email')
                ->where('id', '=', $id)
                ->get();
            return $user;
        } catch (Exception $e) {
            return 'Usuario no encontrado. Código de error: ' . $e->getCode();
        }
    }

    public static function createUser(Request $request)
    {
        try {
            // Uso del query builder de Laravel en lugar de Eloquent. Los timestamps no se guardan automáticamente
            $user = DB::table('users')->insert([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'created_at' => now(),
                'updated_at' => now()
            ]);
    
            return $user;

        } catch (Exception $e) {
            return 'El usuario no se ha podido crear. Código de error: ' . $e->getCode();
        }
    }

    public static function updateUser(string $id, Request $request)
    {
        // Hay que recorrer las key y las value de la request para inyectarlas en el select
        // De la misma forma que el patch de messages en clinicalahuellaAPI
        try {
            if($request->has('password')) {
                $user = DB::table('users')
                ->where('id', $id)
                ->update(['password' => Hash::make($request->password)]);
                
                return $user;
            }
            $user = DB::table('users')
                ->where('id', $id)
                ->update($request->except('_token', '_method'));
            return $user;
        } catch (Exception $e) {
            return 'El usuario no se ha podido actualizar. Código de error: ' . $e->getMessage();
        }
    }

    public static function deleteUser(string $id)
    {
        try {
            $user = DB::table('users')
                ->where('id', $id)
                ->delete();
            
            return $user;
        } catch (Exception $e) {
            return 'El usuario no ha podido ser eliminado. Código de error: ' . $e->getCode();
        }
    }
}

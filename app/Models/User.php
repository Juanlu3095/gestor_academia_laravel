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

    /**
     * It returns an specific user's data as a collection.
     * @param string $id
     * @return \Illuminate\Support\Collection|string
     */
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

    /**
     * It creates a new user.
     * @param Request $request
     * @return bool|string true if user created correctly, false if not.
     */
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

    /**
     * It updates a specific user by id.
     * @param string $id
     * @param Request $request
     * @return int|string Number of updated rows. It is 0 if none is updated.
     */
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
                ->update($request->except('_token', '_method'), ['updated_at' => now()]); // El updated_at no funciona en los test
            return $user;
        } catch (Exception $e) {
            return 'El usuario no se ha podido actualizar. Código de error: ' . $e->getMessage();
        }
    }

    /**
     * It deletes a user by a specific id.
     * @param string $id
     * @return int|string Number of rows deleted. Returns 0 if none is deleted.
     */
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

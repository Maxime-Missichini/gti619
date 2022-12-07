<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CreateUserController extends Controller
{
    /**
     * Valide la requête
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
            'role' => ['required', 'string'],
        ]);
    }

    /**
     * Crée un utilisateur avec le rôle souhaité
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createUser(Request $request){

        $this->validator($request->all())->validate();

        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'grid_card' => User::generateGridCard(),
        ]);

        $user = User::where('name',$request->name)->first();
        $role = Role::where('name',$request->role)->first();
        $user->assignRole($role);

        error_log('user created');

        return redirect('/home')->with('error','test');
    }
}

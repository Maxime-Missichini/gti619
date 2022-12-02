<?php

namespace Database\Seeders;

use App\Models\Ability;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //Creating roles and setting abilities
        DB::table('abilities')->insert([
            'name' => 'see-admin'
        ]);

        DB::table('abilities')->insert([
            'name' => 'see-affaire'
        ]);

        DB::table('abilities')->insert([
            'name' => 'see-residentiel'
        ]);

        $see_admin = Ability::where('name','see-admin')->first();
        $see_affaire = Ability::where('name','see-affaire')->first();
        $see_residentiel = Ability::where('name','see-residentiel')->first();

        DB::table('roles')->insert([
            'name' => 'Administrateur'
        ]);

        DB::table('roles')->insert([
            'name' => 'Prepose_affaire'
        ]);

        DB::table('roles')->insert([
            'name' => 'Prepose_residentiel'
        ]);

        $administrateur = Role::where('name','Administrateur')->first();
        $administrateur->allowTo($see_admin);
        $administrateur->allowTo($see_affaire);
        $administrateur->allowTo($see_residentiel);

        $prepose_affaire = Role::where('name','Prepose_affaire')->first();
        $prepose_affaire->allowTo($see_affaire);

        $prepose_residentiel = Role::where('name','Prepose_residentiel')->first();
        $prepose_residentiel->allowTo($see_residentiel);

        //Creating users
        DB::table('users')->insert([
            'name' => 'Administrateur',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('secret'),
            'grid_card' => User::generateGridCard(),
        ]);

        DB::table('users')->insert([
            'name' => 'Utilisateur1',
            'email' => 'utilisateur1@gmail.com',
            'password' => bcrypt('secret'),
            'grid_card' => User::generateGridCard(),
        ]);

        DB::table('users')->insert([
            'name' => 'Utilisateur2',
            'email' => 'utilisateur2@gmail.com',
            'password' => bcrypt('secret'),
            'grid_card' => User::generateGridCard(),
        ]);

        $admin = User::where('name','Administrateur')->first();
        $user1 = User::where('name','Utilisateur1')->first();
        $user2 = User::where('name','Utilisateur2')->first();

        $admin->assignRole($administrateur);
        $user1->assignRole($prepose_residentiel);
        $user2->assignRole($prepose_affaire);

        DB::table('clients')->insert([
            'first_name' => 'Jean',
            'last_name' => 'Pierre',
            'type' => 'affaire',
        ]);

        DB::table('clients')->insert([
            'first_name' => 'André',
            'last_name' => 'Ciseaux',
            'type' => 'residentiel',
        ]);
    }
}

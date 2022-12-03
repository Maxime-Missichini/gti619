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
     * Rempli la base de donnée lors du php artisan migrate
     *
     * @return void
     */
    public function run()
    {
        //Création des rôles et des abilities
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

        //Création des utilisateurs par défaut
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

        //Création des clients d'affaire
        DB::table('clients')->insert([
            'first_name' => 'Abel',
            'last_name' => 'Auboisdormant',
            'type' => 'affaire',
        ]);

        //Création des clients
        DB::table('clients')->insert([
            'first_name' => 'Adam',
            'last_name' => 'Troijours',
            'type' => 'affaire',
        ]);

        //Création des clients
        DB::table('clients')->insert([
            'first_name' => 'Adrienne',
            'last_name' => 'Kepoura',
            'type' => 'affaire',
        ]);

        //Création des clients
        DB::table('clients')->insert([
            'first_name' => 'Alain',
            'last_name' => 'Provist',
            'type' => 'affaire',
        ]);

        //Création des clients
        DB::table('clients')->insert([
            'first_name' => 'Alain',
            'last_name' => 'Verse',
            'type' => 'affaire',
        ]);

        //Création des clients
        DB::table('clients')->insert([
            'first_name' => 'Ali',
            'last_name' => 'Gator',
            'type' => 'affaire',
        ]);

        //Création des clients
        DB::table('clients')->insert([
            'first_name' => 'Amandine',
            'last_name' => 'Ozaur',
            'type' => 'affaire',
        ]);

        //Création des clients
        DB::table('clients')->insert([
            'first_name' => 'Andy',
            'last_name' => 'Capé',
            'type' => 'affaire',
        ]);

        //Création des clients
        DB::table('clients')->insert([
            'first_name' => 'Annie',
            'last_name' => 'Versaire',
            'type' => 'affaire',
        ]);

        //Création des clients
        DB::table('clients')->insert([
            'first_name' => 'Aubin',
            'last_name' => 'Sahalor',
            'type' => 'affaire',
        ]);

        //Clients résidentiels

        DB::table('clients')->insert([
            'first_name' => 'Barack',
            'last_name' => 'Afritt',
            'type' => 'residentiel',
        ]);

        DB::table('clients')->insert([
            'first_name' => 'Beth',
            'last_name' => 'Rave',
            'type' => 'residentiel',
        ]);

        DB::table('clients')->insert([
            'first_name' => 'Bienvenue',
            'last_name' => 'Parminou',
            'type' => 'residentiel',
        ]);

        DB::table('clients')->insert([
            'first_name' => 'Camille',
            'last_name' => 'Zole',
            'type' => 'residentiel',
        ]);

        DB::table('clients')->insert([
            'first_name' => 'Céline',
            'last_name' => 'Évitable',
            'type' => 'residentiel',
        ]);

        DB::table('clients')->insert([
            'first_name' => 'Charles',
            'last_name' => 'Ottofraize',
            'type' => 'residentiel',
        ]);

        DB::table('clients')->insert([
            'first_name' => 'Cicéron',
            'last_name' => 'Cépacaré',
            'type' => 'residentiel',
        ]);

        DB::table('clients')->insert([
            'first_name' => 'Djamal',
            'last_name' => 'Alatête',
            'type' => 'residentiel',
        ]);

        DB::table('clients')->insert([
            'first_name' => 'Élie',
            'last_name' => 'Coptère',
            'type' => 'residentiel',
        ]);

        DB::table('clients')->insert([
            'first_name' => 'Èva',
            'last_name' => 'Skacélagueul',
            'type' => 'residentiel',
        ]);
    }
}

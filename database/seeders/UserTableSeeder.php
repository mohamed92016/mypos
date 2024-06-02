<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user= User::create([
            'first_name'=>'super',
            'last_name'=>'admin',
            'email'=>'super_admin@app.com',
            'password'=>bcrypt('123456'),
        ]);
        $user->addRole('super_admin');


    }
}

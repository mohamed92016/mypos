<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients=['ahmed', 'mohamed'];

        foreach($clients as $client){

            Client::create([
                'name'=> $client,
                'phone'=> '01122567',
                'address'=> 'haram',
            ]);
        }
    }

}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;


class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories=['cat one', 'cat two', 'cat three'];

        foreach($categories as $category){

            Category::create([
                'ar'=>['name'=>$category],
                'en'=>['name'=>$category],
            ]);

        }
    }
}

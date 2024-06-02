<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;


class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products=['pro one', 'pro two'];

        foreach($products as $product){

            Product::create([
                'category_id'=> 1,
                'ar'=>['name'=>$product, 'description'=> $product . 'description'],
                'en'=>['name'=>$product, 'description'=> $product . 'description'],
                'purchase_price'=> 100,
                'sale_price'=> 150,
                'stock'=> 100,
            ]);

        }
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Client;
use App\Models\Order;
use Illuminate\Support\Facades\DB;



class WelcomeController extends Controller
{
    public function index(){

        $categories_count = Category::count();
        $products_count = Product::count();
        $clients_count = Client::count();
        $orders_count = Order::count();
        $users_count = User::count();
        // $users_count = User::whereRoleIs('admin')->count();
        




        $sales_data = Order::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total_price) as sum')
        )->groupBy('month')->get();


        return view('dashboard.welcome', compact('categories_count', 'products_count', 'clients_count',
         'orders_count','users_count', 'sales_data'));
    }
}

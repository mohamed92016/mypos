<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class OrderController extends Controller
{
    
    public function index(Request $request){

        $orders = Order::whereHas('client', function($q) use($request) {
            return $q->where('name', 'like', '%'. $request->search. '%');

        })->paginate(5);


            return view('dashboard.orders.index', compact('orders'));

    }

    public function products(Order $order){

        $products = $order->products;

        return view('dashboard.orders._products', compact('order','products'));


    }


    public function destroy(Order $order){

        foreach($order->products as $product){

            $product->update([
                'stock'=> $product->stock + $product->pivot->quantity

            ]);
        }

        $order->delete();

        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.orders.index');

    }
}

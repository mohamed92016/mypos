<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class ClientController extends Controller
{
    public function index(Request $request){

        $clients=Client::when($request->search, function($q) use($request){
            
            return $q->whereTranslationLike('name', '%'. $request->search . '%');
            return $q->where('name', 'like', '%'. $request->search . '%')
                    ->orWhere('phone','like', '%'. $request->search . '%')
                    ->orWhere('address','like', '%'. $request->search . '%');


        })->latest()->paginate(5);
        return view('dashboard.clients.index', compact('clients'));

    }

    public function create(){
        return view('dashboard.clients.create');

    }

    public function store(Request $request){
        $request->validate([
            'name'=>'required',
            'phone'=>'required|array|min:1',
            'phone.0'=>'required',
            'address'=>'required',
        ]);


        $request_data= $request->all();
        //to remove 'null' from array and use implode in the index
        $request_data['phone'] = array_filter($request->phone); 


        Client::create($request_data);
        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.clients.index');

    }

    public function edit(Client $client){

        return redirect()->route('dashboard.clients.edit', compact('client'));

    }

    public function update(Request $request, Client $client){
        $request->validate([
            'name'=>'required',
            'phone'=>'required|array|min:1',
            'phone.0'=>'required',
            'address'=>'required',
        ]);


        $request_data= $request->all();
        //to remove 'null' from array and use implode in the index
        $request_data['phone'] = array_filter($request->phone);


        $client->update($request_data);

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.clients.index');
            

    }

    public function destroy(Client $client){
        $client->delete();

        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.clients.index');

    }

}

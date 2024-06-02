<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\Storage;



use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
// use Spatie\Permission\Models\Role;
use Hash;
use Illuminate\Support\Arr;
use Spatie\Permission\Middlewares\RoleMiddleware;
use Spatie\Permission\Middlewares\PermissionMiddleware;
use Spatie\Permission\Middlewares\RoleOrPermissionMiddleware;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;


class UserController extends Controller implements LaratrustUser
{

    use HasRolesAndPermissions;

    public function __construct(){
        $this->middleware(['permission:users_read'])->only('index');
        $this->middleware(['permission:users_create'])->only('create');
        $this->middleware(['permission:users_update'])->only('edit');
        $this->middleware(['permission:users_delete'])->only('destroy');
    }


    public function index(Request $request){
        $users= User::whereHasRole('admin')->where(function($q) use($request){
            
            return $q->when($request->search, function($query) use($request){

                return $query->where('first_name', 'like', '%' . '$request->search' . '%')
                ->orWhere('last_name', 'like', '%' . '$request->search' . '%');
            });


        })->latest()->paginate(5);
        
                
        return view('dashboard.users.index', compact('users'));
    }

    public function create(){
        return view('dashboard.users.create');

    }

    public function store(Request $request){

        
        $request->validate([
        'first_name'=>'required',
        'last_name'=>'required',
        'email'=>'required|unique:users',
        'image'=>'image',
        'password'=>'required|confirmed',
        'permissions'=>'required|min:1',
        ]);
        
        $request_data=$request->except(['password','password_confirmation','permissions','image']);
        $request_data['password']=bcrypt($request->password);

        if($request->image){
            $manager = new ImageManager(new Driver());
            $img = $manager->read($request->image);
            $img->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
    
                });

            $img->toPng()->save(public_path('uploads/user_images/' . $request->image->hashName()));

            $request_data['image']= $request->image->hashName();

        };

        $user= User::create($request_data);
        $user->addRole('admin');
        $user->syncPermissions($request->permissions);


        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.users.index');

    }

    public function edit(User $user){
        return view('dashboard.users.edit', compact('user'));

    }

    public function update(Request $request,User $user){
        $request->validate([
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=> ['required',Rule::unique('users')->ignore($user->id),],
            'image'=>'image',
            'permissions'=>'required|min:1',

            ]);
            
        $request_data=$request->except(['permissions','image']);

        if($request->image){

            if($user->image != 'default.png'){

                Storage::disk('Public_uploads')->delete('/user_images/'. $user->image);
            };

            $img = Image::make($request->image);
            $img->resize(300, null, function ($constraint) {
            $constraint->aspectRatio();

            })->save(public_path('uploads/user_images/' . $request->image->hashName()));

            $request_data['image']= $request->image->hashName();

         };
        $user->update($request_data);
        $user->syncPermissions($request->permissions);
        

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.users.index');


    } 

    public function destroy(User $user){

        if($user->image != 'default.png'){

        //     Storage::disk('Public_uploads')->delete('/user_images/'. $user->image);
        Storage::disk('public_uploads')->delete('/user_images/' . $user->image);
        }
        
        $user->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.users.index');

    }
}

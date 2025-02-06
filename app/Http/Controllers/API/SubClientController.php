<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\ImageStorageTrait;
use Illuminate\Support\Facades\Validator;


class SubClientController extends Controller
{
    
    use ImageStorageTrait;
    

     /**
     * @var array
     */
    protected $response = [];
    protected $status = 200;
    
    public function index(){
        $users = User::with('teams')->where('id', '<>', auth()->user()->id)->where('client_id', auth()->user()->id)->orderBy('id', 'DESC')->get();
        if(empty($users)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);      
        }
        $this->response['message'] = 'Users list!';
        $this->response['data'] = $users;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'mobile_number' => 'nullable',
            'profile_pic' => 'nullable'
        ]);
  
        if($validator->fails()){
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status);
        }
        
        $user = new User();
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->username = isset($request->username) ? $request->username : $request->first_name.' '.$request->last_name;
        if(isset($request->mobile_number)){
            $user->mobile_number = $request->mobile_number;
        }
        $user->client_id = auth()->user()->id;
        $image_name = '';
        if($request->hasFile('profile_pic')){
            $picture = $request->file('profile_pic');
            $folder = 'uploads'; 
            $image_name = $this->storeImage($picture, $folder);
        }
        
        if(isset($request->is_8_hours_enabled)){
            $user->is_8_hours_enabled = $request->is_8_hours_enabled;
        }
        
        $users = auth()->user();
        
        if(!empty($users)){
            $user->is_8_hours_enabled = $users->is_8_hours_enabled;
        }
        
        $user->profile_pic = $image_name;
        $user->save();
        
        if(isset($request->role_id) && !empty($request->role_id)){
            $role = Role::findOrFail($request->role_id);
            if(!empty($role)){
                $user->syncRoles([$role->name]);
            }

            if(isset($request->permissions) && !empty($request->permissions) && is_array($request->permissions)){
                // dd($request->permissions);
                $permissions = Permission::whereIn('id', $request->permissions)->pluck('id');
                if(!empty($permissions)){
                    $role->syncPermissions($permissions);
                }
            }
        }
        
        $this->response['message'] = 'User created successfully!';
        $this->response['data'] = $user;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);

    }

    public function detail($guid){
        $user = User::where('guid', $guid)->first();
        if(empty($user)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);      
        }
        $this->response['message'] = 'User detail!';
        $this->response['data'] = $user;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);

    }
    public function update(Request $request, $guid){

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,id',
            'mobile_number' => 'nullable',
        ]);
  
        if($validator->fails()){
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status); 
        }
        
        $user = User::where('guid', $guid)->first();
        if(empty($user)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);     
        }
        $user->email = $request->email;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        if(isset($request->username)){
            $user->username = $request->username;
        }
        if(isset($request->mobile_number)){
            $user->mobile_number = $request->mobile_number;
        }

        $image_name = $user->profile_pic;
        if($request->hasFile('profile_pic')){
            $picture = $request->file('profile_pic');
            $folder = 'uploads'; 
            $image_name = $this->storeImage($picture, $folder);
        }
        
        if(isset($request->is_8_hours_enabled)){
            $user->is_8_hours_enabled = $request->is_8_hours_enabled;
        }
        
        $user->profile_pic = $image_name;

        $user->save();
        if(isset($request->role_id) && !empty($request->role_id)){
            $role = Role::findOrFail($request->role_id);
            if(!empty($role)){
                $user->syncRoles([$role->name]);
            }

            if(isset($request->permissions) && !empty($request->permissions) && is_array($request->permissions)){
                // dd($request->permissions);
                $permissions = Permission::whereIn('id', $request->permissions)->pluck('id');
                if(!empty($permissions)){
                    $role->syncPermissions($permissions);
                }
            }
        }
        
        $permissions = Permission::pluck('id', 'id')->all();

        
    
        $this->response['message'] = 'User updated successfully!';
        $this->response['data'] = $user;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function destroy($guid){
        $user = User::where('guid', $guid)->first();
        if(empty($user)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);      
        }
        User::where('id', $user->id)->delete();

        $this->response['message'] = 'User deleted successfully!';
        $this->response['data'] = $user;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);

    }

}

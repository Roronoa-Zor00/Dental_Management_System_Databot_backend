<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\ImageStorageTrait;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ImageStorageTrait;
    

     /**
     * @var array
     */
    protected $response = [];
    protected $status = 200;
    
    public function index(){
        
        $search = '';
        if(isset(request()->search) && !empty(request()->search)){
            $search = request()->search;
        }
        request()['page'] = 1;
        if(isset(request()->current_page) && !empty(request()->current_page)){
            request()['page'] = request()->current_page;
        }
        $pagination = (isset(request()->paginate) && request()->paginate == 'yes') ? 1 : 0;
        $per_page = (isset(request()->per_page) && !empty(request()->per_page)) ? request()->per_page : 20;
        
        
        $user = User::select('id','guid','username','first_name','last_name','email','mobile_number', 'phone', 'profile_pic', 'status', 'team_id','created_at')->with(['teams:id,guid,name,created_at'])->where('id', '<>', auth()->user()->id)
                ->when((!empty($search)), function($q) use($search){
                    $q->where('username', 'like','%'.$search.'%')->orWhere('email', 'like','%'.$search.'%')->orWhere('first_name', 'like','%'.$search.'%')->orWhere('last_name', 'like','%'.$search.'%')->orWhere('full_name', 'like','%'.$search.'%');
                    
                })
            ->orderBy('id', 'DESC');
        
        if(!empty($pagination) && $pagination == 1){
            $users = $user->paginate($per_page);
        }else{
            $users = $user->get();
        }


        if(empty($users)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);      
        }
        
            // $role = Role::findOrFail(1);
            
            // $permissions = Permission::pluck('id')->all();
            // if(!empty($permissions)){
            //     $role->syncPermissions($permissions);
            // }
        
        
        
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
            'username' => 'nullable|unique:users',
            'password' => 'required|confirmed|min:8'
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
        $user->full_name = $request->first_name.' '.$request->last_name;
        if(isset($request->username) && !empty($request->username)){
            $user->username = $request->username;
        }else{
            $user->username = $request->first_name.'-'.$request->last_name;
        }
        $user->mobile_number = $request->mobile_number ?? '';
        if(isset($request->country_name) && !empty($request->country_name)){
            $user->country_name = $request->country_name;
        }

        $image_name = '';
        if($request->hasFile('profile_pic')){
            $picture = $request->file('profile_pic');
            $folder = 'uploads'; 
            $image_name = $this->storeImage($picture, $folder);
        }
        
        if(isset($request->monthly_total_cases) && !empty($request->monthly_total_cases)){
            $user->monthly_total_cases = $request->monthly_total_cases;
        }
        if(isset($request->monthly_target_cases) && !empty($request->monthly_target_cases)){
            $user->monthly_target_cases = $request->monthly_target_cases;
        }
        if(isset($request->extra_cases) && !empty($request->extra_cases)){
            $user->extra_cases = $request->extra_cases;
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
        
        
        $this->response['message'] = 'User created successfully!';
        $this->response['data'] = $user;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);

    }

    public function detail($guid){
        $user = User::with(['my_cases', 'completed_cases'])->where('guid', $guid)->first();
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
            'username' => 'nullable|unique:users,id'        
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
        if(!empty($user) && !empty($user->role_name) && $user->role_name != 'super_admin'){
        $user->email = $request->email;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        if(isset($request->username) && !empty($request->username)){
            $user->username = $request->username;
        }
        if(isset($request->mobile_number) && !empty($request->mobile_number)){
            $user->mobile_number = $request->mobile_number;
        }
        if(isset($request->country_name) && !empty($request->country_name)){
            $user->country_name = $request->country_name;
        }
        $image_name = $user->profile_pic;
        if($request->hasFile('profile_pic')){
            $picture = $request->file('profile_pic');
            $folder = 'uploads'; 
            $image_name = $this->storeImage($picture, $folder);
        }
        if(isset($request->is_8_hours_enabled)){
            $user->is_8_hours_enabled = $request->is_8_hours_enabled;
            User::where('client_id', $user->id)->update(['is_8_hours_enabled' => $request->is_8_hours_enabled]);
        }
        $user->profile_pic = $image_name;
        
        
        if(isset($request->monthly_total_cases) && !empty($request->monthly_total_cases)){
            $user->monthly_total_cases = $request->monthly_total_cases;
        }
        if(isset($request->monthly_target_cases) && !empty($request->monthly_target_cases)){
            $user->monthly_target_cases = $request->monthly_target_cases;
        }
        if(isset($request->extra_cases) && !empty($request->extra_cases)){
            $user->extra_cases = $request->extra_cases;
        }
        
        $user->full_name = $request->first_name.' '.$request->last_name;
        
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
        
        }

        
    
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


    // get treatment planners users
    public function treatmentPlanners(){
        $users = User::where('id', '<>', auth()->user()->id)->whereHas("roles", function($q) {
                                $q->whereIn('name', ['treatment_planner']);
                            })->get();

        $this->response['message'] = 'Treatment planners successfully!';
        $this->response['data'] = $users;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);

    }
    // get treatment planners users
    public function treatmentPlannersQualityCheck(){
        $users = User::where('id', '<>', auth()->user()->id)->whereHas("roles", function($q) {
                                $q->whereIn('name', ['treatment_planner', 'quality_check']);
                            })->get();

        $this->response['message'] = 'Treatment planners successfully!';
        $this->response['data'] = $users;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);

    }
    
    public function passwordUpdate(Request $request){
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed|min:8',
            'user_id' => 'required|numeric'
        ]);
  
        if($validator->fails()){
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status);
        }
        
        $user = User::findOrFail($request->user_id);
        if(empty($user)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);     
        }
        $user->password = bcrypt($request->password);
        $user->save();

        $this->response['message'] = 'Password update successfully!';
        $this->response['data'] = $user;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function updateUserProfilePic(Request $request){
        
        $validator = Validator::make($request->all(), [
            'profile_pic' => 'required',
            'user_id' => 'required|numeric'
        ]);
  
        if($validator->fails()){
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status);
        }
        
        $user = User::findOrFail($request->user_id);
        if(empty($user)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);     
        }

        if($request->hasFile('profile_pic')){
            $picture = $request->file('profile_pic');
            $folder = 'uploads'; 
            $image_name = $this->storeImage($picture, $folder);
            $user->profile_pic = $image_name;
            $user->Save();
        }

        $this->response['message'] = 'Profile update successfully!';
        $this->response['data'] = $user;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }
    
    
    public function updateFullName(){
        $users = User::get();
        if(!empty($users)){
            foreach($users as $key => $value){
                $user = User::findOrFail($value->id);
                if(!empty($user)){
                    $user->full_name = $value->first_name.' '.$value->last_name;
                    $user->save();
                }
            }
            dd('update pdate success full');
        }
    }
}

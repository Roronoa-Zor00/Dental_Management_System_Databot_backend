<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{

     /**
     * @var array
     */
    protected $response = [];
    protected $status = 200;
    
    protected $user_id = 0;
    protected $role_name = '';
    public function __construct(){
        if(!empty(auth()->user())){
            $this->user_id = auth()->user()->id;
            $this->role_name = auth()->user()->role_name;
        }
    }
    
    
    public function index(){
        $roles = [];
        if($this->role_name == 'super_admin' || $this->role_name == 'case_submission'){
            $roles = Role::all();
        }else if($this->role_name == 'client'){
            $roles = Role::where('name', 'sub_client')->get();   
        }else{
            $roles = Role::where('name', $this->role_name)->get(); 
        }

        if(empty($roles)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);    
        }
        $this->response['message'] = 'Roles list';
        $this->response['data'] = $roles;
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
            'name' => 'required'
        ]);
  
        if($validator->fails()){
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status);
        }
        
        $role = new Role();
        $role->name = $request->name;
        $role->guard_name = 'api';
        $role->save();
    
        $this->response['message'] = 'Role created successfully!';
        $this->response['data'] = $role;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function detail($guid){
        $role = Role::where('guid', $guid)->first();
        if(empty($role)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);      
        }
        $this->response['message'] = 'Role details!';
        $this->response['data'] = $role;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }
    public function update(Request $request, $guid){

        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);
  
        if($validator->fails()){
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status);
        }
        
        $role = Role::where('guid', $guid)->first();
        if(empty($role)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);      
        }
        $role->name = $request->name;
        $role->guard_name = 'api';
        $role->save();
        
        $this->response['message'] = 'Role updated successfully!';
        $this->response['data'] = $role;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function destroy($guid){
        $role = Role::where('guid', $guid)->first();
        if(empty($role)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);   
        }
        Role::where('id', $role->id)->delete();

        $this->response['message'] = 'Role deleted successfully!';
        $this->response['data'] = $role;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);

    }
}

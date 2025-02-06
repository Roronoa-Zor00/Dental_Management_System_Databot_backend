<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    /**
     * @var array
     */
    protected $response = [];
    protected $status = 200;
    
    public function index(){
        $permissions = Permission::all();
        if(empty($permissions)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);   
        }
        $this->response['message'] = 'Permissions list';
        $this->response['data'] = $permissions;
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
    
        if ($validator->fails()) {
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status);
        }
        
        $permission = new Permission();
        $permission->name = $request->name;
        $permission->guard_name = 'api';
        $permission->save();
        
        $this->response['message'] = 'Permission created successfully!';
        $this->response['permission'] = $permission;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function detail($guid){
        $permission = Permission::where('guid', $guid)->first();
        if(empty($permission)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);      
        }
        $this->response['message'] = 'Permission details!';
        $this->response['data'] = $permission;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
        return response()->json(['permission' => $permission], 201);
    }
    public function update(Request $request, $guid){

        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);
        
        if ($validator->fails()) {
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status);
        }
        
        $permission = Permission::where('guid', $guid)->first();
        if(empty($permission)){
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);   
        }
        $permission->name = $request->name;
        $permission->guard_name = 'api';
        $permission->save();
        
        $this->response['message'] = 'Permission updated successfully!';
        $this->response['data'] = $permission;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);

       
    }

    public function destroy($guid){
        $permission = Permission::where('guid', $guid)->first();
        if(empty($permission)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);   
        }
        Permission::where('id', $permission->id)->delete();

        $this->response['message'] = 'Permission deleted successfully!';
        $this->response['data'] = $permission;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function assignPermissions(){

        $user = User::findOrFail($request->user_id);
        if(!empty($user)){
            $permissions = Permission::whereIn('id', [$request->permissions])->pluck('name', 'name')->get();
            if(!empty($permissions)){
                foreach ($permissions as $key => $value) {
                    $user->givePermissionTo($value);    
                }
                $this->response['message'] = 'Permission added successfully!';
                $this->response['data'] = $user;
                $this->response['status'] = $this->status;
                return response()->json($this->response, $this->status);
            }
        }
        $this->response['message'] = 'User not found!';
        $this->response['data'] = [];
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }


    public function removePermissions(){
        $user = User::findOrFail($request->user_id);
        if(!empty($user)){
            $permissions = Permission::whereIn('id', [$request->permissions])->pluck('name', 'name')->get();
            if(!empty($permissions)){
                foreach ($permissions as $key => $value) {
                    $user->revokePermissionTo($value);    
                }

                $this->response['message'] = 'Permission removed successfully!';
                $this->response['data'] = $user;
                $this->response['status'] = $this->status;
                return response()->json($this->response, $this->status);
            }
        }
        $this->response['message'] = 'User not found!';
        $this->response['data'] = [];
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    
    }


}

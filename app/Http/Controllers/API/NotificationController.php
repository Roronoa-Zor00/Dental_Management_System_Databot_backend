<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class NotificationController extends Controller
{
    
     /**
     * @var array
     */
    protected $response = [];
    protected $status = 200;
    protected $user_id = 0;
    protected $role_name = '';
    
    public function __construct(ActivityLogs $activityLog=null){
        if(!empty(auth()->user())){
            $this->user_id = auth()->user()->id;
            $this->role_name = auth()->user()->role_name;
        }

    }
    
    
    public function index(){
       $role_names = $this->role_name;
       
       $per_page = 10;
       request()['page'] = 1;
        if(isset(request()->current_page) && !empty(request()->current_page)){
            request()['page'] = request()->current_page;
        }
        
        if(isset(request()->per_page) && !empty(request()->per_page)){
            $per_page = request()->per_page;
        }
        

        

 $notifications = Notification::
            when((!empty($role_names)), function($q) use($role_names){
            if($role_names != 'super_admin' && $role_names != 'case_submission'){
                $q->where('user_id', auth()->user()->id)->orderBy('is_read', 'ASC');
            }else{
                if($role_names == 'super_admin'){
                    $q->orderBy('is_read_admin', 'ASC');
                }else{
                    $q->orderBy('is_read_case_submission', 'ASC');
                }
            }
            
        })->paginate($per_page);
        if(empty($notifications)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);      
        }
        $this->response['message'] = 'Notification list!';
        $this->response['data'] = $notifications;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    /**
     * Register a PatientCase.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required',
            'url_action' => 'required',
            'user_id' => 'required|numeric'
        ]);

        if($validator->fails()){
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status);
        }
        
        $notifications = new Notification();
        $notifications->title = $request->title;
        $notifications->body = $request->body;
        $notifications->url_action = $request->url_action;
        $notifications->user_id = $request->user_id;
        $notifications->created_by = auth()->user()->id;
        $notifications->save();
        
        $this->response['message'] = 'Notification created successfully!';
        $this->response['data'] = $notifications;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);

    }

    public function detail($guid){
        
        $role_names = $this->role_name;

        $notifications = Notification::where('guid', $guid)->first();
        
        if(empty($notifications)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);      
        }
    
        if($role_names == 'super_admin'){
            $notifications->is_read_admin = 1;    
        }else if($role_names == 'case_submission'){
            $notifications->is_read_case_submission = 1;
        }else{
            $notifications->is_read = 1;
        }
        
        $notifications->save();

        $this->response['message'] = 'Notification detail!';
        $this->response['data'] = $notifications;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);

    }

    public function readAllUnReadNotifications(){


        $role_names = $this->role_name;
         
        if($role_names == 'super_admin'){
            DB::table('notifications')
            ->where('is_read_admin', 0)
            ->update(['is_read_admin' => 1]);
            
        }else if($role_names == 'case_submission'){
            DB::table('notifications')
            ->where('is_read_case_submission', 0)
            ->update(['is_read_case_submission' => 1]);
            
        }else{
            DB::table('notifications')
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        }

        $this->response['message'] = 'Notification read successfully!';
        $this->response['data'] = [];
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);


    }


}

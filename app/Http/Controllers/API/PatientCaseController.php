<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CasesStatusUser;
use App\Models\CasesStatusUsersComment;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Traits\ImageStorageTrait;
use App\Models\PatientCase;
use App\Models\Xray;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Repositories\ActivityLogs;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;



class PatientCaseController extends Controller
{

    use ImageStorageTrait;

    private $activityLog;

    /**
     * @var array
     */

    /**
     * Register a PatientCase.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    protected $response = [];
    protected $status = 200;
    protected $user_id = 0;
    protected $role_name = '';

    public function __construct(ActivityLogs $activityLog = null)
    {
        if (!empty(auth()->user())) {
            $this->user_id = auth()->user()->id;
            $this->role_name = auth()->user()->role_name;
        }

        $this->activityLog = $activityLog;
    }

    public function index()
    {

        request()['page'] = 1;
        if (isset(request()->current_page) && !empty(request()->current_page)) {
            request()['page'] = request()->current_page;
        }
        $pagination = (isset(request()->paginate) && request()->paginate == 'yes') ? 1 : 0;
        $per_page = (isset(request()->per_page) && !empty(request()->per_page)) ? request()->per_page : 20;


        $search = '';

        $patient_name = null;
        $clinic_name = null;
        $case_type = null;
        $case_completed = null;
        $date_from = null;
        $date_to = null;

        if (isset(request()->search) && !empty(request()->search)) {
            $search = request()->search;
        }
        if (isset(request()->patient_name) && !empty(request()->patient_name)) {
            $patient_name = request()->patient_name;
        }
        if (isset(request()->clinic_name) && !empty(request()->clinic_name)) {
            $clinic_name = request()->clinic_name;
        }
        if (isset(request()->case_type) && !empty(request()->case_type)) {
            $case_type = request()->case_type;
        }
        if (isset(request()->case_completed) && !empty(request()->case_completed)) {
            $case_completed = request()->case_completed;
        }

        if (isset(request()->date_from) && !empty(request()->date_to)) {
            $date_from = request()->date_from;
            $date_to = request()->date_to;
        }

        $now = Carbon::now()->timestamp;
        $timeLimit = Carbon::now()->addHours(8)->timestamp;





        $patient_cases = [];
        $cases = PatientCase::select('id', 'guid', 'name', 'email', 'case_id', 'age', 'gender', 'chief_complaint', 'status', 'is_priority', 'expected_time', 'start_date_time', 'created_at', 'case_type', 'created_by', 'planner_id', 'assign_to', 'client_id')->with([
            'created_user' => function ($query) {
                $query->select('id', 'guid', 'username', 'first_name', 'last_name');
            },
            'planner' => function ($query) {
                $query->select('id', 'guid', 'username', 'first_name', 'last_name');
            }
        ])->when($this->role_name, function ($q) {
            if ($this->role_name == 'post_processing') {
                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
            } else if ($this->role_name != 'super_admin' && $this->role_name != 'case_submission') {
                if (auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)) {
                    $q->where(function ($query) {
                        $query->where('created_by', auth()->user()->id)
                            ->orWhere('assign_to', auth()->user()->id)
                            ->orWhere('client_id', auth()->user()->id)
                            ->orWhere('created_by', auth()->user()->client_id);
                    });
                    // $q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                } else {
                    $q->where(function ($query) {
                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                    });
                    // $q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                }
            }
        })
            ->when(!empty($search), function ($q) use ($search) {
                $q->where('case_id', 'LIKE', '%' . $search . '%');
            })
            ->when(!empty($patient_name), function ($q) use ($patient_name) {
                $q->where('name', 'LIKE', '%' . $patient_name . '%');
            })
            ->when(!empty($clinic_name), function ($q) use ($clinic_name) {
                $q->whereHas('users', function ($query) use ($clinic_name) {
                    $query->where('username', 'LIKE', '%' . $clinic_name . '%');
                });
            })
            ->when(!empty($case_type), function ($q) use ($case_type) {
                $q->where('case_type', $case_type);
            })
            ->when(!empty($case_completed), function ($q) use ($case_completed) {
                $q->where('status', $case_completed);
            })
            // ->when((!empty($this->role_name) && ($this->role_name != 'sub_client' && $this->role_name != 'client' && $this->role_name != 'post_processing' && $this->role_name == 'super_admin')), function($q){
            //     $q->where('verified_by_client', 1);
            // })
            ->when((isset(request()->status)), function ($q) {
                $q->where('status', request()->status);
            })

            ->when((!empty($date_from) && !empty($date_to)), function ($q) use ($date_from, $date_to) {
                $q->whereBetween('created_at', [date('Y-m-d', strtotime($date_from)) . " 00:00:00", date('Y-m-d', strtotime($date_to)) . " 23:59:59"]);
            })
            ->when((isset(request()->is_modification_cases) && request()->is_modification_cases == 1), function ($q) {
                $q->whereIn('status', [8, 13, 14, 17]);
            })
            // ->when((isset(request()->is_prority_cases) && request()->is_prority_cases == 1), function($q) use($timeLimit){

            //     $q->whereRaw('UNIX_TIMESTAMP(start_date_time) <= ?', [now()->addHours(8)->timestamp])->whereRaw('UNIX_TIMESTAMP(start_date_time) >= ?', [now()->timestamp]);

            //     // $q->where('start_date_time', '<=', now()->addHours(20));

            // })
            ->when(
                isset(request()->is_prority_cases) && request()->is_prority_cases == 1,
                function ($q) use ($now, $timeLimit) {

                    $q->where('start_date_time_timestamp_string', '<=', $timeLimit)->where('start_date_time_timestamp_string', '>=', $now)->whereNotIn('status', [7, 11, 12, 16, 18]);

                    // $q->whereBetween('start_date_time', [
                    //     $now->toDateTimeString(),
                    //     $timeLimit->toDateTimeString()
                    // ]);

                }
            )
            // ->orderBy('is_priority', 'DESC')
            ->orderBy('id', 'DESC');
        if (!empty($pagination) && $pagination == 1) {
            $patient_cases = $cases->paginate($per_page);
        } else {
            $patient_cases = $cases->get();
        }

        if (empty($patient_cases)) {
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);
        }
        $this->response['message'] = 'Patient cases list!';
        $this->response['data'] = $patient_cases;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function export_cases()
    {
        $search = '';

        $patient_name = null;
        $clinic_name = null;
        $case_type = null;
        $case_completed = null;
        $date_from = null;
        $date_to = null;

        if (isset(request()->search) && !empty(request()->search)) {
            $search = request()->search;
        }
        if (isset(request()->patient_name) && !empty(request()->patient_name)) {
            $patient_name = request()->patient_name;
        }
        if (isset(request()->clinic_name) && !empty(request()->clinic_name)) {
            $clinic_name = request()->clinic_name;
        }
        if (isset(request()->case_type) && !empty(request()->case_type)) {
            $case_type = request()->case_type;
        }
        if (isset(request()->case_completed) && !empty(request()->case_completed)) {
            $case_completed = request()->case_completed;
        }

        if (isset(request()->date_from) && !empty(request()->date_to)) {
            $date_from = request()->date_from;
            $date_to = request()->date_to;
        }

        $now = Carbon::now()->timestamp;
        $timeLimit = Carbon::now()->addHours(8)->timestamp;


        $patient_cases = [];
        $cases = PatientCase::select('id', 'guid', 'name', 'email', 'case_id', 'age', 'gender', 'chief_complaint', 'status', 'is_priority', 'expected_time', 'start_date_time', 'created_at', 'case_type', 'created_by', 'planner_id', 'assign_to', 'client_id')->with([
            'created_user' => function ($query) {
                $query->select('id', 'guid', 'username', 'first_name', 'last_name');
            },
            'planner' => function ($query) {
                $query->select('id', 'guid', 'username', 'first_name', 'last_name');
            }
        ])->when($this->role_name, function ($q) {
            if ($this->role_name == 'post_processing') {
                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
            } else if ($this->role_name != 'super_admin' && $this->role_name != 'case_submission') {
                if (auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)) {
                    $q->where(function ($query) {
                        $query->where('created_by', auth()->user()->id)
                            ->orWhere('assign_to', auth()->user()->id)
                            ->orWhere('client_id', auth()->user()->id)
                            ->orWhere('created_by', auth()->user()->client_id);
                    });
                    // $q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                } else {
                    $q->where(function ($query) {
                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                    });
                    // $q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                }
            }
        })
            ->when(!empty($search), function ($q) use ($search) {
                $q->where('case_id', 'LIKE', '%' . $search . '%');
            })
            ->when(!empty($patient_name), function ($q) use ($patient_name) {
                $q->where('name', 'LIKE', '%' . $patient_name . '%');
            })
            ->when(!empty($clinic_name), function ($q) use ($clinic_name) {
                $q->whereHas('users', function ($query) use ($clinic_name) {
                    $query->where('username', 'LIKE', '%' . $clinic_name . '%');
                });
            })
            ->when(!empty($case_type), function ($q) use ($case_type) {
                $q->where('case_type', $case_type);
            })
            ->when(!empty($case_completed), function ($q) use ($case_completed) {
                $q->where('status', $case_completed);
            })
            // ->when((!empty($this->role_name) && ($this->role_name != 'sub_client' && $this->role_name != 'client' && $this->role_name != 'post_processing' && $this->role_name == 'super_admin')), function($q){
            //     $q->where('verified_by_client', 1);
            // })
            ->when((isset(request()->status)), function ($q) {
                $q->where('status', request()->status);
            })

            ->when((!empty($date_from) && !empty($date_to)), function ($q) use ($date_from, $date_to) {
                $q->whereBetween('created_at', [date('Y-m-d', strtotime($date_from)) . " 00:00:00", date('Y-m-d', strtotime($date_to)) . " 23:59:59"]);
            })
            ->when((isset(request()->is_modification_cases) && request()->is_modification_cases == 1), function ($q) {
                $q->whereIn('status', [8, 13, 14, 17]);
            })
            // ->when((isset(request()->is_prority_cases) && request()->is_prority_cases == 1), function($q) use($timeLimit){

            //     $q->whereRaw('UNIX_TIMESTAMP(start_date_time) <= ?', [now()->addHours(8)->timestamp])->whereRaw('UNIX_TIMESTAMP(start_date_time) >= ?', [now()->timestamp]);

            //     // $q->where('start_date_time', '<=', now()->addHours(20));

            // })
            ->when(
                isset(request()->is_prority_cases) && request()->is_prority_cases == 1,
                function ($q) use ($now, $timeLimit) {

                    $q->where('start_date_time_timestamp_string', '<=', $timeLimit)->where('start_date_time_timestamp_string', '>=', $now)->whereNotIn('status', [7, 11, 12, 16, 18]);

                    // $q->whereBetween('start_date_time', [
                    //     $now->toDateTimeString(),
                    //     $timeLimit->toDateTimeString()
                    // ]);

                }
            )
            ->orderBy('id', 'DESC');
        
            $patient_cases = $cases->get();

        if (empty($patient_cases)) {
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);
        }

        $payload = [];

        foreach ($patient_cases as $key => $value) {
            $case_payload = [];
            $case_payload['guid'] = $value->guid ?? '';
            $case_payload['id'] = $value->id ?? '';
            $case_payload['case_id'] = $value->case_id ?? '';
            $case_payload['status'] = $value->status ?? '';
            $case_payload['name'] = $value->name ?? '';
            $case_payload['email'] = $value->email ?? '';
            $case_payload['gender'] = $value->gender ?? '';
            $case_payload['created_at'] = $value->created_at ?? null;
            $case_payload['created_user'] = [
                'username' => $value->created_user->username ?? '',
            ];
            
            $case_payload['planner'] = [
                'username' => $value->planner->username ?? '',
                'first_name' => $value->planner->first_name ?? '',
                'last_name' => $value->planner->last_name ?? ''
            ];
        
            $payload[] = $case_payload;
        }

        $this->response['message'] = 'Patient cases list!';
        $this->response['data'] = $payload;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'extraction' => 'nullable',
            'attachments' => 'nullable',
            'case_id' => 'required|unique:patient_cases',
            'age' => 'required',
            'gender' => 'required',
            'ipr' => 'nullable',
            'chief_complaint' => 'required',
            'treatment_plan' => 'required',
            'stl_upper_file' => 'required',
            'stl_lower_file' => 'required',
            'software_id' => 'required|numeric',
            'is_priority' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status);
        }

        $patient_cases = PatientCase::where('case_id', $request->case_id)->first();

        if ($patient_cases) {
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = 'Case id already exist';
            return response()->json($this->response, $this->status);
        }


        $patient_cases = new PatientCase();
        $patient_cases->name = $request->name;
        $patient_cases->email = isset($request->email) ? $request->email : '';

        if (isset($request->extraction)) {
            $patient_cases->extraction = $request->extraction;
        }
        if (isset($request->attachments)) {
            $patient_cases->attachments = $request->attachments;
        }
        if (isset($request->ipr)) {
            $patient_cases->ipr = $request->ipr;
        }

        if (isset($request->patient_location)) {
            $patient_cases->patient_location = $request->patient_location;
        }
        if (isset($request->case_type)) {
            $patient_cases->case_type = $request->case_type;
        }
        if (isset($request->arch)) {
            $patient_cases->arch = $request->arch;
        }

        if (isset($request->arch) && $request->arch != "") {
            $patient_cases->software_id = $request->software_id;
        }

        // $patient_cases->extraction = $request->extraction;
        // $patient_cases->attachments = $request->attachments;
        $patient_cases->case_id = $request->case_id;
        $patient_cases->age = $request->age;
        $patient_cases->gender = $request->gender;
        $patient_cases->chief_complaint = $request->chief_complaint;
        $patient_cases->treatment_plan = $request->treatment_plan;
        $patient_cases->created_by = auth()->user()->id;
        $patient_cases->verified_by_client = 1;
        if (auth()->user() && !empty(auth()->user()->client_id)) {
            $patient_cases->sub_client_id = auth()->user()->id;
            $patient_cases->client_id = auth()->user()->client_id;
            // $patient_cases->verified_by_client = 0;
        }
        // else{
        $patient_cases->start_date_time = date('Y-m-d H:i:s');
        // }
        if (isset($request->is_priority) && !empty($request->is_priority)) {
            $patient_cases->is_priority = $request->is_priority;
        }

        if (isset($request->created_by_admin) && !empty($request->created_by_admin)) {
            $patient_cases->created_by_admin = $request->created_by_admin;
        }
        if (isset($request->expected_time) && !empty($request->expected_time)) {
            $patient_cases->expected_time = $request->expected_time;
        }
        if (isset($request->tooth_label_format) && !empty($request->tooth_label_format)) {
            $patient_cases->tooth_label_format = $request->tooth_label_format;
        }
        if (isset($request->setup_type) && !empty($request->setup_type)) {
            $patient_cases->setup_type = $request->setup_type;
        }

        $stl_upper_file = '';
        $stl_lower_file = '';
        $stl_byte_scan_file = '';
        if ($request->hasFile('stl_upper_file')) {
            $picture = $request->file('stl_upper_file');
            $folder = 'uploads/stl';
            $stl_upper_file = $this->storeImage($picture, $folder);
        }
        if ($request->hasFile('stl_lower_file')) {
            $picture = $request->file('stl_lower_file');
            $folder = 'uploads/stl';
            $stl_lower_file = $this->storeImage($picture, $folder);
        }
        if ($request->hasFile('stl_byte_scan_file')) {
            $picture = $request->file('stl_byte_scan_file');
            $folder = 'uploads/stl';
            $stl_byte_scan_file = $this->storeImage($picture, $folder);
        }
        // if($request->hasFile('ipr')){
        //     $picture = $request->file('ipr');
        //     $folder = 'uploads/pdf'; 
        //     $ipr = $this->storeImage($picture, $folder);
        // }
        $patient_cases->stl_upper_file = $stl_upper_file;
        $patient_cases->stl_lower_file = $stl_lower_file;
        $patient_cases->stl_byte_scan_file = $stl_byte_scan_file;
        // $patient_cases->ipr = $request->ipr;
        $patient_cases->save();

        $cases_status_user = new CasesStatusUser();
        $cases_status_user->p_case_id = $patient_cases->id;
        $cases_status_user->user_id = auth()->user()->id;
        $cases_status_user->case_status = $patient_cases->status;
        $cases_status_user->save();

        $p_case_id = $patient_cases->id;
        // save patient images files
        if (isset($request->image_files) && !empty($request->image_files)) {
            foreach ($request->image_files as $key => $value) {
                if (is_file($value)) {
                    $images = new Image();
                    $picture = $value;
                    $folder = 'uploads/images';
                    $file_name = $this->storeImage($picture, $folder);
                    $images->file_name = $file_name;
                    $images->type = 'patient_cases';
                    $images->p_case_id = $p_case_id;
                    $images->save();
                }
            }
        }
        // save the patient xrays files 
        if (isset($request->xrays_files) && !empty($request->xrays_files)) {
            foreach ($request->xrays_files as $key => $value) {
                if (is_file($value)) {
                    $xrays = new Xray();
                    $picture = $value;
                    $folder = 'uploads/xrays';
                    $file_name = $this->storeImage($picture, $folder);
                    $xrays->file_name = $file_name;
                    $xrays->type = 'patient_cases';
                    $xrays->p_case_id = $p_case_id;
                    $xrays->save();
                }
            }
        }

        $this->updateStartDateTimeInString();

        $this->response['message'] = 'Patient case created successfully!';
        $this->response['data'] = $patient_cases;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function store_case_status_workload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);


        if ($validator->fails()) {
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status);
        }

        $patient_case_status = CasesStatusUser::where('id', $request->id)->first();
        if (!$patient_case_status) {
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = 'Case Status Not Found!';
            return response()->json($this->response, $this->status);
        } else {
            $patient_case_status->work_start_datetime = $request->work_start_datetime;
            $patient_case_status->work_end_datetime = $request->work_end_datetime;
            try {
                $patient_case_status->save();
                $this->response['message'] = 'Updated Status Workload Successfully!';
                $this->response['data'] = $patient_case_status;
                $this->response['status'] = $this->status;
                return response()->json($this->response, $this->status);
            } catch (Exception $e) {
                $this->status = 500;
                $this->response['message'] = 'Error Updating Status Workload!';
                $this->response['data'] = $patient_case_status;
                $this->response['status'] = $this->status;
                return response()->json($this->response, $this->status);
            }
        }
    }

    public function detail($guid)
    {

        $patient_cases = PatientCase::with(['users', 'images', 'xrays', 'created_user', 'case_plans', 'case_status_users', 'case_status_users.user_detail:id,first_name,last_name,username,profile_pic', 'case_status_users.cases_status_users_comments', 'planner', 'qa', 'post_processing'])
            // ->when($this->role_name, function($q){
            //     if($this->role_name == 'post_processing'){
            //         $q->whereIn('status', [8, 9, 10]);
            //     }else if($this->role_name != 'super_admin' && $this->role_name != 'case_submission'){
            //         $q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id);
            //     }
            // })
            ->where('guid', $guid)->first();

        if (empty($patient_cases)) {
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);
        }
        $this->response['message'] = 'Patient case detail!';
        $this->response['data'] = $patient_cases;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function update(Request $request, $guid)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'extraction' => 'nullable',
            'attachments' => 'nullable',
            'case_id' => 'required|unique:patient_cases,id',
            'age' => 'required',
            'gender' => 'required',
            'ipr' => 'nullable',
            'chief_complaint' => 'required',
            'treatment_plan' => 'required',
            'is_priority' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status);
        }

        $patient_cases = PatientCase::where('guid', $guid)
            ->when((!empty($this->role_name) && $this->role_name != 'super_admin'), function ($query) {
                $query->where('created_by', $this->user_id)->orWhere('client_id', $this->user_id);
            })
            ->first();

        // $patient_cases = PatientCase::where('created_by', $this->user_id)->orWhere('client_id', $this->user_id)->where('guid', $guid)->first();
        if (empty($patient_cases)) {
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);
        }

        $patient_cases->name = $request->name;
        if (isset($request->email) && !empty($request->email)) {
            $patient_cases->email = $request->email;
        }
        if (isset($request->extraction)) {
            $patient_cases->extraction = $request->extraction;
        }
        if (isset($request->attachments)) {
            $patient_cases->attachments = $request->attachments;
        }
        if (isset($request->ipr)) {
            $patient_cases->ipr = $request->ipr;
        }

        if (isset($request->software_id)) {
            $patient_cases->software_id = $request->software_id;
        }

        if (isset($request->patient_location)) {
            $patient_cases->patient_location = $request->patient_location;
        }
        if (isset($request->case_type)) {
            $patient_cases->case_type = $request->case_type;
        }
        if (isset($request->arch)) {
            $patient_cases->arch = $request->arch;
        }



        $patient_cases->case_id = $request->case_id;
        $patient_cases->age = $request->age;
        $patient_cases->gender = $request->gender;
        $patient_cases->chief_complaint = $request->chief_complaint;
        $patient_cases->treatment_plan = $request->treatment_plan;

        $stl_upper_file = $patient_cases->stl_upper_file;
        $stl_lower_file = $patient_cases->stl_lower_file;
        $stl_byte_scan_file = $patient_cases->stl_byte_scan_file;
        // $ipr = $patient_cases->ipr;
        if (isset($request->is_priority) && !empty($request->is_priority)) {
            $patient_cases->is_priority = $request->is_priority;
        }

        if (isset($request->expected_time) && !empty($request->expected_time)) {
            $patient_cases->expected_time = $request->expected_time;
        }
        if (isset($request->tooth_label_format) && !empty($request->tooth_label_format)) {
            $patient_cases->tooth_label_format = $request->tooth_label_format;
        }
        if (isset($request->setup_type) && !empty($request->setup_type)) {
            $patient_cases->setup_type = $request->setup_type;
        }

        $patient_cases->case_version = ((int)$patient_cases->case_version + 1);
        $scan_version = $patient_cases->scan_version;

        if ($request->hasFile('stl_upper_file')) {
            $picture = $request->file('stl_upper_file');
            $folder = 'uploads/stl';
            $stl_upper_file = $this->storeImage($picture, $folder);
            $scan_version = ($patient_cases->scan_version + 1);
        }
        if ($request->hasFile('stl_lower_file')) {
            $picture = $request->file('stl_lower_file');
            $folder = 'uploads/stl';
            $stl_lower_file = $this->storeImage($picture, $folder);
            $scan_version = ($patient_cases->scan_version + 1);
        }
        if ($request->hasFile('stl_byte_scan_file')) {
            $picture = $request->file('stl_byte_scan_file');
            $folder = 'uploads/stl';
            $stl_byte_scan_file = $this->storeImage($picture, $folder);
            $scan_version = ($patient_cases->scan_version + 1);
        }

        $patient_cases->scan_version = $scan_version;
        // if($request->hasFile('ipr')){
        //     $picture = $request->file('ipr');
        //     $folder = 'uploads/pdf'; 
        //     $ipr = $this->storeImage($picture, $folder);
        // }
        $patient_cases->stl_upper_file = $stl_upper_file;
        $patient_cases->stl_lower_file = $stl_lower_file;
        $patient_cases->stl_byte_scan_file = $stl_byte_scan_file;
        // $patient_cases->ipr = $ipr;
        $patient_cases->save();

        $p_case_id = $patient_cases->id;
        // save patient images files
        if (isset($request->image_files) && !empty($request->image_files)) {
            foreach ($request->image_files as $key => $value) {
                if (is_file($value)) {
                    $images = new Image();
                    $picture = $value;
                    $folder = 'uploads/images';
                    $file_name = $this->storeImage($picture, $folder);
                    $images->file_name = $file_name;
                    $images->type = 'patient_cases';
                    $images->p_case_id = $p_case_id;
                    $images->save();
                }
            }
        }
        // save the patient xrays files 
        if (isset($request->xrays_files) && !empty($request->xrays_files)) {
            foreach ($request->xrays_files as $key => $value) {
                if (is_file($value)) {
                    $xrays = new Xray();
                    $picture = $value;
                    $folder = 'uploads/xrays';
                    $file_name = $this->storeImage($picture, $folder);
                    $xrays->file_name = $file_name;
                    $xrays->type = 'patient_cases';
                    $xrays->p_case_id = $p_case_id;
                    $xrays->save();
                }
            }
        }


        // $changeFields = $patient_cases->getChanges();
        // if (!empty($changeFields)) {
        //     foreach ($changeFields as $key => $changeField){
        //         if(!empty($changeField) && $key != 'created_at' && $key != 'updated_at'){
        //             $this->activityLog->addLog(auth()->user(), ucfirst($this->role_name), "updated", $key.' => '.$changeField, $changeFields->id, '', 'patient_cases');
        //         }
        //     }
        // }


        $this->response['message'] = 'Patient case updated successfully!';
        $this->response['data'] = $patient_cases;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function destroy($guid)
    {
        $patient_cases = PatientCase::where('guid', $guid)->first();
        if (empty($patient_cases)) {
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);
        }
        PatientCase::where('id', $patient_cases->id)->delete();

        $this->response['message'] = 'Patient case deleted successfully!';
        $this->response['data'] = $patient_cases;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function case_assign_to(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'p_case_id' => 'required|numeric',
            'user_id' => 'required|numeric',
            'p_case_status' => 'required|numeric',

        ]);

        if ($validator->fails()) {
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status);
        }
        $user = User::findOrFail($request->user_id);

        $patient_cases = PatientCase::findOrFail($request->p_case_id);
        if (empty($patient_cases)) {
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);
        }
        $patient_cases->assign_to = $request->user_id;
        $patient_cases->status = $request->p_case_status;

        if (!empty($user) && !empty($user->roles) && !empty($user->roles[0]) && $user->roles[0]['name'] && $user->roles[0]['name'] == 'quality_check') {
            $patient_cases->qa_id = $user->id;
        }
        if (!empty($user) && !empty($user->roles) && !empty($user->roles[0]) && $user->roles[0]['name'] && $user->roles[0]['name'] == 'treatment_planner') {
            $patient_cases->planner_id = $user->id;
        }
        if (!empty(auth()->user()->roles) && !empty(auth()->user()->roles) && !empty(auth()->user()->roles[0]) && auth()->user()->roles[0]['name'] && auth()->user()->roles[0]['name'] == 'treatment_planner') {
            $patient_cases->qa_id = auth()->user()->id;
        }
        $patient_cases->save();

        $cases_status_user = new CasesStatusUser();
        $cases_status_user->p_case_id = $patient_cases->id;
        $cases_status_user->user_id = auth()->user()->id;
        $cases_status_user->case_status = $patient_cases->status;
        $cases_status_user->save();

        $this->response['message'] = 'Patient case assigned successfully!';
        $this->response['data'] = $patient_cases;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function rejected_by_quality_check(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'p_case_id' => 'required|numeric',
            'user_id' => 'required|numeric',

        ]);

        if ($validator->fails()) {
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status);
        }
        $user = User::findOrFail($request->user_id);

        $patient_cases = PatientCase::findOrFail($request->p_case_id);
        if (empty($patient_cases)) {
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);
        }
        $patient_cases->assign_to = $request->user_id;
        $patient_cases->status = 2;

        if (!empty($user) && !empty($user->roles) && !empty($user->roles[0]) && $user->roles[0]['name'] && $user->roles[0]['name'] == 'treatment_planner') {
            $patient_cases->planner_id = $user->id;
        }
        if (!empty(auth()->user()->roles) && !empty(auth()->user()->roles) && !empty(auth()->user()->roles[0]) && auth()->user()->roles[0]['name'] && auth()->user()->roles[0]['name'] == 'quality_check') {
            $patient_cases->qa_id = auth()->user()->id;
        }
        $patient_cases->save();

        $cases_status_user = new CasesStatusUser();
        $cases_status_user->p_case_id = $patient_cases->id;
        $cases_status_user->user_id = auth()->user()->id;
        $cases_status_user->case_status = $patient_cases->status;
        $cases_status_user->save();

        $this->response['message'] = 'Patient case assigned successfully!';
        $this->response['data'] = $patient_cases;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function update_patient_case_status(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'p_case_id' => 'required|numeric',
            'user_id' => 'required|numeric',
            'case_status' => 'required|numeric'

        ]);

        if ($validator->fails()) {
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status);
        }
        $user = null;
        if (!empty($request->user_id)) {
            $user = User::findOrFail($request->user_id);
        }

        if (empty($user)) {
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);
        }


        $patient_cases = PatientCase::findOrFail($request->p_case_id);
        if (empty($patient_cases)) {
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);
        }
        $patient_cases->assign_to = $request->user_id;
        $patient_cases->status = $request->case_status;

        if ($request->case_status == 8 || $request->case_status == 9 || $request->case_status == 10 || $request->case_status == 13 || $request->case_status == 14 || $request->case_status == 15 || $request->case_status == 17) {
            $patient_cases->start_date_time = date('Y-m-d H:i:s');
        }

        if ($request->case_status == 8) {
            $patient_cases->case_version = ((int)$patient_cases->case_version + 1);
        }

        if (!empty($user) && !empty($user->roles) && !empty($user->roles[0]) && $user->roles[0]['name'] && $user->roles[0]['name'] == 'treatment_planner') {
            $patient_cases->planner_id = $user->id;
        }
        if (!empty(auth()->user()->roles) && !empty(auth()->user()->roles) && !empty(auth()->user()->roles[0]) && auth()->user()->roles[0]['name'] && auth()->user()->roles[0]['name'] == 'quality_check') {
            $patient_cases->qa_id = auth()->user()->id;
        }

        if (!empty(auth()->user()->roles) && !empty(auth()->user()->roles) && !empty(auth()->user()->roles[0]) && auth()->user()->roles[0]['name'] && auth()->user()->roles[0]['name'] == 'post_processing') {
            $patient_cases->post_processing_id = auth()->user()->id;
        }

        if (isset($request->stl_file_by_post_processing) && !empty($request->stl_file_by_post_processing)) {
            if ($request->hasFile('stl_file_by_post_processing')) {
                $file_name = '';
                $picture = $request->file('stl_file_by_post_processing');
                $folder = 'uploads/stl';
                $file_name = $this->storeImage($picture, $folder);
                $patient_cases->stl_file_by_post_processing = $file_name;
            } else {
                $patient_cases->stl_file_by_post_processing = $request->stl_file_by_post_processing;
            }
        }
        if (isset($request->container_file_by_post_processing) && !empty($request->container_file_by_post_processing)) {
            if ($request->hasFile('container_file_by_post_processing')) {
                $file_name = '';
                $picture = $request->file('container_file_by_post_processing');
                $folder = 'uploads/stl';
                $file_name = $this->storeImage($picture, $folder);
                $patient_cases->container_file_by_post_processing = $file_name;

                // $patient_cases->scan_version = ($patient_cases->scan_version + 1);
            } else {
                $patient_cases->container_file_by_post_processing = $request->container_file_by_post_processing;
            }
        }
        if (isset($request->stl_file_by_post_processing_we_transfer_link)) {
            $patient_cases->stl_file_by_post_processing_we_transfer_link = $request->stl_file_by_post_processing_we_transfer_link;
        }



        $patient_cases->save();


        // $cases_status_user = CasesStatusUser::where('p_case_id', $request->p_case_id)->where('user_id', $request->user_id)->where('case_status', $request->case_status)->first();
        // if(!$cases_status_user){
        $cases_status_user = new CasesStatusUser();
        // }
        $cases_status_user->p_case_id = $request->p_case_id;
        $cases_status_user->user_id = $request->user_id;
        $cases_status_user->case_status = $request->case_status;
        $cases_status_user->save();

        $cases_status_user_id = $cases_status_user->id;

        if (isset($request->comments) && !empty($request->comments)) {
            $cases_status_users_comment = new CasesStatusUsersComment();
            $cases_status_users_comment->pcsu_id = $cases_status_user_id;
            $cases_status_users_comment->comments = $request->comments;
            $cases_status_users_comment->case_status = $request->case_status;
            $cases_status_users_comment->save();
        }

        $this->updateStartDateTimeInString();

        $this->response['message'] = 'Patient case assigned successfully!';
        $this->response['data'] = $patient_cases;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function getCasesByStatus($status = 1)
    {
        $patient_cases = [];
        $patient_cases = PatientCase::with(['users', 'images', 'xrays', 'created_user', 'case_plans', 'case_status_users', 'case_status_users.cases_status_users_comments'])->when($this->role_name, function ($q) {
            if ($this->role_name != 'super_admin' && $this->role_name != 'case_submission') {
                $q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id);
            }
        })->where('status', $status)->paginate('10');

        if (empty($patient_cases)) {
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);
        }
        $this->response['message'] = 'Patient cases list!';
        $this->response['data'] = $patient_cases;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function verified_by_client($guid)
    {
        $patient_cases = PatientCase::where('guid', $guid)->first();

        if (empty($patient_cases)) {
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);
        }

        $patient_cases->verified_by_client = 1;
        $patient_cases->start_date_time = date('Y-m-d H:i:s');
        $patient_cases->save();

        $this->updateStartDateTimeInString();

        $this->response['message'] = 'Verified by client successfully!';
        $this->response['data'] = $patient_cases;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function completed_cases()
    {

        $completed_cases = PatientCase::with(['created_user', 'planner'])->where('status', 18)->where(function ($q) {
            $q->where(function ($query) {
                $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
            });
            // $q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->Orwhere('client_id', auth()->user()->id);
        })->orderBy('id', 'DESC')->orderBy('is_priority', 'DESC')->get();

        $this->response['message'] = 'Completed cases list!';
        $this->response['data'] = $completed_cases;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function casesHistories()
    {
        $user = User::with(['user_cases_history', 'user_cases_history.cases_status_users_comments.'])->where('id', auth()->user()->id)->first();
        $this->response['message'] = 'Completed cases list!';
        $this->response['data'] = $user;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function cases_histories()
    {

        $role_name = $this->role_name;
        request()['page'] = 1;
        if (isset(request()->current_page) && !empty(request()->current_page)) {
            request()['page'] = request()->current_page;
        }

        $pagination = (isset(request()->paginate) && request()->paginate == 'yes') ? 1 : 0;
        $per_page = (isset(request()->per_page) && !empty(request()->per_page)) ? request()->per_page : 30;

        $search = '';

        $patient_name = null;
        $clinic_name = null;
        $case_type = null;
        $case_completed = null;
        $date_from = null;
        $date_to = null;

        if (isset(request()->search) && !empty(request()->search)) {
            $search = request()->search;
        }
        if (isset(request()->patient_name) && !empty(request()->patient_name)) {
            $patient_name = request()->patient_name;
        }
        if (isset(request()->clinic_name) && !empty(request()->clinic_name)) {
            $clinic_name = request()->clinic_name;
        }
        if (isset(request()->case_type) && !empty(request()->case_type)) {
            $case_type = request()->case_type;
        }
        if (isset(request()->case_completed) && !empty(request()->case_completed)) {
            $case_completed = request()->case_completed;
        }

        if (isset(request()->date_from) && !empty(request()->date_to)) {
            $date_from = request()->date_from;
            $date_to = request()->date_to;
        }

        $timeLimit = Carbon::now()->addHours(8);



        $cases = PatientCase::select('id', 'guid', 'name', 'case_id', 'gender', 'status', 'is_priority', 'start_date_time', 'created_at')
            ->whereHas('case_status_users')
            ->when(!empty($search), function ($q) use ($search) {
                $q->where('case_id', 'LIKE', '%' . $search . '%');
            })
            ->when(!empty($patient_name), function ($q) use ($patient_name) {
                $q->where('name', 'LIKE', '%' . $patient_name . '%');
            })
            ->when(!empty($clinic_name), function ($q) use ($clinic_name) {
                $q->whereHas('users', function ($query) use ($clinic_name) {
                    $query->where('username', 'LIKE', '%' . $clinic_name . '%');
                });
            })
            ->when(!empty($case_type), function ($q) use ($case_type) {
                $q->where('case_type', $case_type);
            })
            ->when(!empty($case_completed), function ($q) use ($case_completed) {
                $q->where('status', $case_completed);
            })
            ->when((isset(request()->status)), function ($q) {
                $q->where('status', request()->status);
            })
            ->when(!empty($role_name), function ($q) use ($role_name) {
                $q->whereRelation('case_status_users', 'user_id', auth()->user()->id);
            })
            ->orderBy('id', 'desc')->paginate($per_page);

        $this->response['message'] = 'Cases history list!';
        $this->response['data'] = $cases;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function checkCaseIdUnique($case_id)
    {
        if (!empty($case_id)) {
            $case_exists = PatientCase::where('case_id', $case_id)->first();

            if (isset(request()->debug) && request()->debug == 1) {
                dd($case_exists);
            }
            if (!empty($case_exists)) {
                // Case ID exists in the database
                $this->response['message'] = 'Case ID exists in the database!';
                $this->response['data'] = false;
                $this->response['status'] = false;
                return response()->json($this->response, $this->status);
            } else {
                $this->response['message'] = 'Case ID does not exist in the database!';
                $this->response['data'] = true;
                $this->response['status'] = true;
                return response()->json($this->response, $this->status);
            }
        }

        $this->response['message'] = 'Case id required!';
        $this->response['data'] = [];
        $this->response['status'] = false;
        return response()->json($this->response, $this->status);
    }

    public function updateStartDateTimeInString()
    {
        $cases = PatientCase::all();
        if (!empty($cases)) {
            foreach ($cases as $key => $value) {
                $case = PatientCase::where('id', $value->id)->first();
                if (!empty($case) && !empty($case->start_date_time)) {
                    $result = $this->addHours($case);
                    $case->start_date_time_timestamp_string = strtotime($result);
                    $case->save();
                }
            }
        }

        $this->response['message'] = 'Time update successfull!';
        $this->response['data'] = [];
        $this->response['status'] = true;
        return response()->json($this->response, $this->status);
    }

    public function addHours($case)
    {
        $originalDateTime = $case->start_date_time;

        // Parse the original date-time
        $carbonDateTime = Carbon::parse($originalDateTime);

        // Add 8 hours
        $hours = (int)$case->expected_time ?? 0;
        $newDateTime = $carbonDateTime->addHours($hours);

        // Output the result
        return $newDateTime->toDateTimeString(); // Outputs: 2024-11-22 08:00:59

    }
}

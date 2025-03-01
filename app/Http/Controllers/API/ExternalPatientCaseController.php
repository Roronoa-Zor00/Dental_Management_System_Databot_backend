<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ExternalPatientCase;
use App\Repositories\ActivityLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExternalPatientCaseController extends Controller
{
    private $activityLog;

    /**
     * @var array
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


        $patient_cases = [];
        $cases = ExternalPatientCase::select(
            'id', 'case_id', 'name', 'guid', 'status', 'software_id', 'client_id', 'case_datetime', 'created_at', 'created_by'
        )
        ->with([
            'created_user' => function ($query) {
                $query->select('id', 'guid', 'full_name'); // Removed 'first_name', 'last_name' if they don't exist
            }
        ])->with([
            'software' => function ($query) {
                $query->select('id', 'guid', 'name'); // No 'first_name' here
            }
        ])->with([
            'client' => function ($query) {
                $query->select('id', 'guid', 'first_name','last_name'); // Remove 'first_name' if not in 'clients' table
            }
        ])->orderBy('id', 'DESC');
    

        if (!empty($pagination) && $pagination == 1) {
            $patient_cases = $cases->paginate($per_page);
        } else {
            $patient_cases = $cases->get();
        }

        if ($patient_cases->isEmpty()) {
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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'case_id' => 'required|unique:patient_cases',
            'status' => 'required',
            'software_id' => 'required',
            'client_id' => 'required',
            'case_datetime' => 'required'
        ]);

        if ($validator->fails()) {
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status);
        }

        $case = new ExternalPatientCase();
        $case->name = $request->name;
        $case->case_id = $request->case_id;
        $case->status = $request->status;
        $case->software_id = $request->software_id;
        $case->client_id = $request->client_id;
        $case->case_datetime = $request->case_datetime;
        $case->updated_at = now();
        $case->created_at = now();
        $case->created_by = auth()->id();

        $case->save();

        $this->response['message'] = 'Case created successfully!';
        $this->response['data'] = $case;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function destroy($guid)
    {
        $case = ExternalPatientCase::where('guid', $guid)->first();
        if (empty($case)) {
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);
        }
        ExternalPatientCase::where('id', $case->id)->delete();

        $this->response['message'] = 'Case deleted successfully!';
        $this->response['data'] = $case;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function detail($case_id)
    {
        $case = ExternalPatientCase::where('guid', $case_id)->first();
        if (empty($case)) {
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Case not found';
            return response()->json($this->response, $this->status);
        }

        $this->response['message'] = 'Case found successfully!';
        $this->response['data'] = $case;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function update(Request $request, $guid)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'case_id' => 'required|unique:patient_cases',
            'status' => 'required',
            'software_id' => 'required',
            'client_id' => 'required',
            'case_datetime' => 'required'
        ]);

        if ($validator->fails()) {
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status);
        }

        $case = ExternalPatientCase::where('guid', $guid)->first();
        if (empty($case)) {
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);
        }

        $case->name = $request->name;
        $case->case_id = $request->case_id;
        $case->status = $request->status;
        $case->software_id = $request->software_id;
        $case->client_id = $request->client_id;
        $case->case_datetime = $request->case_datetime;
        $case->updated_at = now();
        $case->save();

        $this->response['message'] = 'Case updated successfully!';
        $this->response['data'] = $case;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

}
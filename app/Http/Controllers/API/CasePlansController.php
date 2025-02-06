<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ImageStorageTrait;
use Illuminate\Support\Facades\Validator;
use App\Models\CasePlan;
use App\Models\PatientCase;

class CasePlansController extends Controller
{
     use ImageStorageTrait;
    
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
        $case_plans = [];
            $case_plans = CasePlan::when($this->role_name, function($q){
                                    if($this->role_name != 'super_admin' && $this->role_name != 'case_submission'){
                                        $q->where('created_by', auth()->user()->id);
                                    }
                                })->orderBy('id', 'DESC')->paginate('10');
        
        if(empty($case_plans)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);      
        }
        $this->response['message'] = 'Case plans list!';
        $this->response['data'] = $case_plans;
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
            'p_case_id' => 'required',
            'case_plans' => 'required'
        ]);

        if($validator->fails()){
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status);
        }


        $case_plans = [];
        if(isset($request->case_plans) && !empty($request->case_plans)){
            $case_plans = json_decode($request->case_plans, true);

            $ipr_chart_files = $request->ipr_chart_files ?? [];
        }
        if(!empty($case_plans)){
            foreach ($case_plans as $key => $value) {
                if(!empty($value)){
                   $case_plans = new CasePlan();
                   $p_case_id = 0;
                   if(isset($request->p_case_id) && !empty($request->p_case_id) && is_numeric($request->p_case_id)){
                        $patient_case = PatientCase::where('id', $request->p_case_id)->first();
                        if(!empty($patient_case)){
                            $p_case_id = $patient_case->id;
                        }
                    }else{
                        $patient_case = PatientCase::where('guid', $request->p_case_id)->first();
                        if(!empty($patient_case)){
                            $p_case_id = $patient_case->id;
                        }
                    }
                   if(!empty($patient_case))
                    $case_plans->p_case_id = $p_case_id;
                    $case_plans->text_notes = $value['text_notes'];
                    $case_plans->simulation_link_url = $value['simulation_link_url'];
                    $case_plans->created_by = auth()->user()->id;
                    $ipr = '';
                    // save the ipr_charts files 
                    if(isset($ipr_chart_files) && !empty($ipr_chart_files) && isset($ipr_chart_files[$key]) && !empty($ipr_chart_files[$key])){
                        $file = $ipr_chart_files[$key];
                        if(is_file($file)){
                            $picture = $file;
                            $folder = 'uploads/pdf'; 
                            $ipr = $this->storeImage($picture, $folder);
                        }
                    }
                    $case_plans->ipr_chart = $ipr;
                    if(isset($value->status)){
                        $case_plans->status = $value->status;
                    }
                    $case_plans->save();
                }
            }
        }
        

        
        $this->response['message'] = 'Case plan created successfully!';
        $this->response['data'] = $case_plans;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);

    }

    public function detail($guid){

        $case_plans = CasePlan::where('guid', $guid)->first();
        
        if(empty($case_plans)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);      
        }
        $this->response['message'] = 'Case plan detail!';
        $this->response['data'] = $case_plans;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);

    }
    public function update(Request $request, $guid=0){

        $validator = Validator::make($request->all(), [
            'p_case_id' => 'required',
            'text_notes' => 'required',
            'simulation_link_url' => 'required',
        ]);
  
        if($validator->fails()){
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status); 
        }

        $case_plans = CasePlan::where('guid', $guid)->first();
        if(empty($case_plans)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);      
        }

        $case_plans->text_notes = $request->text_notes;
        $case_plans->simulation_link_url = $request->simulation_link_url;
        
        if(isset($request->ipr_chart) && !empty($request->ipr_chart)){
            $file = $request->ipr_chart;
            if(is_file($file)){
                $picture = $file;
                $folder = 'uploads/pdf'; 
                $ipr = $this->storeImage($picture, $folder);
                $case_plans->ipr_chart = $ipr;
            }
        }

        $case_plans->save();
    
        $this->response['message'] = 'Case plan updated successfully!';
        $this->response['data'] = $case_plans;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function destroy($guid){
        $case_plans = CasePlan::where('guid', $guid)->first();
        if(empty($case_plans)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);      
        }
        CasePlan::where('id', $case_plans->id)->delete();

        $this->response['message'] = 'Case plan deleted successfully!';
        $this->response['data'] = $case_plans;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);

    }

}

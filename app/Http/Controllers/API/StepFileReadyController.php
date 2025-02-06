<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\StepFileReady;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StepFileReadyController extends Controller
{
    
    /**
     * @var array
     */
    protected $response = [];
    protected $status = 200;
    
    public function index(){
        $step_file_readys = StepFileReady::paginate('10');
        if(empty($step_file_readys)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);      
        }
        $this->response['message'] = 'Step file ready list!';
        $this->response['data'] = $step_file_readys;
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
            'error' => 'required',
            'status' => 'required',
        ]);

        if($validator->fails()){
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status);
        }
        
        $step_file_readys = new StepFileReady();
        $step_file_readys->p_case_id = $request->p_case_id;
        $step_file_readys->error = $request->error;
        $step_file_readys->status = $request->status;
        $step_file_readys->created_by = auth()->user()->id;
        $step_file_readys->save();
        
        $this->response['message'] = 'Step file ready created successfully!';
        $this->response['data'] = $step_file_readys;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);

    }

    public function detail($guid){

        $step_file_readys = StepFileReady::where('guid', $guid)->first();
        
        if(empty($step_file_readys)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);      
        }
        $this->response['message'] = 'Step file ready detail!';
        $this->response['data'] = $step_file_readys;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);

    }
    public function update(Request $request, $guid){

        $validator = Validator::make($request->all(), [
            'p_case_id' => 'required',
            'error' => 'required',
            'status' => 'required',
        ]);
        
        
        if($validator->fails()){
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status); 
        }
        
        $step_file_readys = StepFileReady::where('guid', $guid)->first();
        if(empty($step_file_readys)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);     
        }
        
        $step_file_readys->p_case_id = $request->p_case_id;
        $step_file_readys->error = $request->error;
        $step_file_readys->status = $request->status;
        $step_file_readys->created_by = auth()->user()->id;
        $step_file_readys->save();

    
        $this->response['message'] = 'Step file ready updated successfully!';
        $this->response['data'] = $step_file_readys;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function destroy($guid){
        $step_file_readys = StepFileReady::where('guid', $guid)->first();
        if(empty($step_file_readys)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);      
        }
        StepFileReady::where('id', $step_file_readys->id)->delete();

        $this->response['message'] = 'Step file ready deleted successfully!';
        $this->response['data'] = $step_file_readys;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);

    }

}

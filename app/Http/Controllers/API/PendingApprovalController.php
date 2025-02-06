<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PendingApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PendingApprovalController extends Controller
{
    
     /**
     * @var array
     */
    protected $response = [];
    protected $status = 200;
    
    public function index(){
        $pending_approvals = PendingApproval::paginate('10');
        if(empty($pending_approvals)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);      
        }
        $this->response['message'] = 'Pending approvals list!';
        $this->response['data'] = $pending_approvals;
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
            'comments' => 'required',
            'status' => 'required',
        ]);
        
        if($validator->fails()){
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status);
        }
        
        $pending_approvals = new PendingApproval();
        $pending_approvals->p_case_id = $request->p_case_id;
        $pending_approvals->simulation_link_url = $request->simulation_link_url;
        $pending_approvals->ipr_chart = $request->ipr_chart;
        $pending_approvals->comments = $request->comments;
        $pending_approvals->status = $request->status;
        $pending_approvals->created_by = auth()->user()->id;
        $pending_approvals->save();
        
        $this->response['message'] = 'Pending approvals created successfully!';
        $this->response['data'] = $pending_approvals;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);

    }

    public function detail($guid){

        $pending_approvals = PendingApproval::where('guid', $guid)->first();
        
        if(empty($pending_approvals)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);      
        }
        $this->response['message'] = 'Pending approval detail!';
        $this->response['data'] = $pending_approvals;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);

    }
    public function update(Request $request, $guid){

        $validator = Validator::make($request->all(), [
            'p_case_id' => 'required',
            'simulation_link_url' => 'required',
            'ipr_chart' => 'required',
            'comments' => 'required',
            'status' => 'required',
        ]);
  
        if($validator->fails()){
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status); 
        }
        
        $pending_approvals = PendingApproval::where('guid', $guid)->first();
        if(empty($pending_approvals)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);     
        }
        
        $pending_approvals->p_case_id = $request->p_case_id;
        $pending_approvals->simulation_link_url = $request->simulation_link_url;
        $pending_approvals->ipr_chart = $request->ipr_chart;
        $pending_approvals->comments = $request->comments;
        $pending_approvals->status = $request->status;
        $pending_approvals->created_by = auth()->user()->id;
        $pending_approvals->save();

    
        $this->response['message'] = 'Pending approvals updated successfully!';
        $this->response['data'] = $pending_approvals;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function destroy($guid){
        $pending_approvals = PendingApproval::where('guid', $guid)->first();
        if(empty($pending_approvals)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);      
        }
        PendingApproval::where('id', $pending_approvals->id)->delete();

        $this->response['message'] = 'Pending approvals deleted successfully!';
        $this->response['data'] = $pending_approvals;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);

    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ModificationReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ImageStorageTrait;


class ModificationReceivedController extends Controller
{
    
    use ImageStorageTrait;

    /**
     * @var array
     */
    protected $response = [];
    protected $status = 200;
    
    public function index(){
        $modification_receiveds = ModificationReceived::paginate('10');
        if(empty($modification_receiveds)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);      
        }
        $this->response['message'] = 'Modification receiveds list!';
        $this->response['data'] = $modification_receiveds;
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
            'case_plan_id' => 'required',
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
        
        $modification_receiveds = new ModificationReceived();
        $modification_receiveds->case_plan_id = $request->case_plan_id;
        $simulation_link_url = '';
        if(isset($request->simulation_link_url) && !empty($request->simulation_link_url)){
            $simulation_link_url = $request->simulation_link_url;
        }

        $ipr_chart = '';
        if($request->hasFile('ipr_chart')){
            $picture = $request->file('ipr_chart');
            $folder = 'uploads/pdf'; 
            $ipr_chart = $this->storeImage($picture, $folder);
        }
        $modification_receiveds->$ipr_chart = $ipr_chart;

        $modification_receiveds->simulation_link_url = $simulation_link_url;
        $modification_receiveds->ipr_chart = $request->ipr_chart;
        $modification_receiveds->comments = $request->comments;
        $modification_receiveds->status = $request->status;
        $modification_receiveds->created_by = auth()->user()->id;
        $modification_receiveds->save();
        
        $this->response['message'] = 'Modification received created successfully!';
        $this->response['data'] = $modification_receiveds;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);

    }

    public function detail($guid){

        $modification_receiveds = ModificationReceived::where('guid', $guid)->first();
        
        if(empty($modification_receiveds)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);      
        }
        $this->response['message'] = 'Modification received detail!';
        $this->response['data'] = $modification_receiveds;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);

    }
    public function update(Request $request, $guid){

        $validator = Validator::make($request->all(), [
            'case_plan_id' => 'required',
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
        
        $modification_receiveds = ModificationReceived::where('guid', $guid)->first();
        if(empty($modification_receiveds)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);     
        }
        
        $modification_receiveds->case_plan_id = $request->case_plan_id;

        if(isset($request->simulation_link_url) && !empty($request->simulation_link_url)){
            $modification_receiveds->simulation_link_url = $request->simulation_link_url;
        }
        if($request->hasFile('ipr_chart')){
            $ipr_chart = '';
            $picture = $request->file('ipr_chart');
            $folder = 'uploads/pdf'; 
            $ipr_chart = $this->storeImage($picture, $folder);
            $modification_receiveds->$ipr_chart = $ipr_chart;
        }
        $modification_receiveds->comments = $request->comments;
        $modification_receiveds->status = $request->status;
        $modification_receiveds->created_by = auth()->user()->id;
        $modification_receiveds->save();

    
        $this->response['message'] = 'Modification received updated successfully!';
        $this->response['data'] = $modification_receiveds;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function destroy($guid){
        $modification_receiveds = ModificationReceived::where('guid', $guid)->first();
        if(empty($modification_receiveds)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);      
        }
        ModificationReceived::where('id', $modification_receiveds->id)->delete();

        $this->response['message'] = 'Modification received deleted successfully!';
        $this->response['data'] = $modification_receiveds;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);

    }

}

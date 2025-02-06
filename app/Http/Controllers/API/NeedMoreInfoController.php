<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\NeedMoreInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NeedMoreInfoController extends Controller
{
    
    /**
     * @var array
     */
    protected $response = [];
    protected $status = 200;
    
    public function index(){
        $need_more_infos = NeedMoreInfo::paginate('10');
        if(empty($need_more_infos)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);      
        }
        $this->response['message'] = 'Need more infos list!';
        $this->response['data'] = $need_more_infos;
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
            'notes' => 'required',
            'status' => 'required',
        ]);

        if($validator->fails()){
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status);
        }
        
        $need_more_infos = new NeedMoreInfo();
        $need_more_infos->p_case_id = $request->p_case_id;
        $need_more_infos->notes = $request->notes;
        $need_more_infos->status = $request->status;
        $need_more_infos->created_by = auth()->user()->id;
        $need_more_infos->save();
        
        $this->response['message'] = 'Need more info created successfully!';
        $this->response['data'] = $need_more_infos;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);

    }

    public function detail($guid){

        $need_more_infos = NeedMoreInfo::where('guid', $guid)->first();
        
        if(empty($need_more_infos)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);      
        }
        $this->response['message'] = 'Need more info detail!';
        $this->response['data'] = $need_more_infos;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);

    }
    public function update(Request $request, $guid){

        $validator = Validator::make($request->all(), [
            'p_case_id' => 'required',
            'notes' => 'required',
            'status' => 'required',
        ]);
        
        
        if($validator->fails()){
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status); 
        }
        
        $need_more_infos = NeedMoreInfo::where('guid', $guid)->first();
        if(empty($need_more_infos)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);     
        }
        
        $need_more_infos->p_case_id = $request->p_case_id;
        $need_more_infos->notes = $request->notes;
        $need_more_infos->status = $request->status;
        $need_more_infos->created_by = auth()->user()->id;
        $need_more_infos->save();

    
        $this->response['message'] = 'Need more info updated successfully!';
        $this->response['data'] = $need_more_infos;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function destroy($guid){
        $need_more_infos = NeedMoreInfo::where('guid', $guid)->first();
        if(empty($need_more_infos)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);      
        }
        NeedMoreInfo::where('id', $need_more_infos->id)->delete();

        $this->response['message'] = 'Need more info deleted successfully!';
        $this->response['data'] = $need_more_infos;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);

    }

}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Software;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class SoftwareController extends Controller
{

    protected $response = [];
    protected $status = 200;

    public function index()
    {
        $softwares = Software::orderBy('name', 'ASC')->get();
        if (empty($softwares)) {
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);
        }
        $this->response['message'] = 'Softwares list!';
        $this->response['data'] = $softwares;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:softwares'
        ]);

        if ($validator->fails()) {
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status);
        }

        $software = new Software();
        $software->name = $request->name;

        $software->save();

        $this->response['message'] = 'Software created successfully!';
        $this->response['data'] = $software;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function update(Request $request, $guid)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:softwares,id',
        ]);

        if ($validator->fails()) {
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status);
        }

        $software = Software::where('guid', $guid)->first();
        if (empty($software)) {
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);
        }

        $software->name = $request->name;
        $software->save();

        $this->response['message'] = 'Software updated successfully!';
        $this->response['data'] = $software;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function destroy($guid)
    {
        $software = Software::where('guid', $guid)->first();
        if (empty($software)) {
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);
        }
        Software::where('id', $software->id)->delete();

        $this->response['message'] = 'Software deleted successfully!';
        $this->response['data'] = $software;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function detail($software_id)
    {
        // print_r('Detail list');
        $software = Software::where('id', $software_id)->first();
        if (empty($software)) {
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Software not found';
            return response()->json($this->response, $this->status);
        }

        $this->response['message'] = 'Software found successfully!';
        $this->response['data'] = $software;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }
}

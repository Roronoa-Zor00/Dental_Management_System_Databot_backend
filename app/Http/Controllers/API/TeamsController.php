<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ImageStorageTrait;
use Illuminate\Support\Facades\Validator;
use App\Models\Team;
use App\Models\User;


class TeamsController extends Controller
{
    
    use ImageStorageTrait;
    
     /**
     * @var array
     */
    protected $response = [];
    protected $status = 200;
    
    public function index(){
        $teams = Team::orderBy('id', 'DESC')->get();
        if(empty($teams)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);      
        }
        $this->response['message'] = 'Teams list!';
        $this->response['data'] = $teams;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    /**
     * Register a teams.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:teams',
        ]);
  
        if($validator->fails()){
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status);
        }
        
        $teams = new Team();
        $teams->name = $request->name;
        $teams->created_by = auth()->user()->id;
        $logo = '';
        if($request->hasFile('logo')){
            $picture = $request->file('logo');
            $folder = 'uploads/images'; 
            $logo = $this->storeImage($picture, $folder);
        }
        $teams->logo = $logo;
        $teams->save();

        $this->response['message'] = 'Team created successfully!';
        $this->response['data'] = $teams;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);

    }

    public function detail($guid){

        $teams = Team::with('users')->where('guid', $guid)->first();
        
        if(empty($teams)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);      
        }
        $this->response['message'] = 'Team detail!';
        $this->response['data'] = $teams;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);

    }
    public function update(Request $request, $guid){

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:teams,id',
        ]);
  
        if($validator->fails()){
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status); 
        }
        
        $teams = Team::where('guid', $guid)->first();
        if(empty($teams)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);     
        }
        
        $teams->name = $request->name;
        $teams->created_by = auth()->user()->id;
        $logo = '';
        if($request->hasFile('logo')){
            $picture = $request->file('logo');
            $folder = 'uploads/images'; 
            $logo = $this->storeImage($picture, $folder);
        }
        $teams->logo = $logo;
        $teams->save();

    
        $this->response['message'] = 'Team updated successfully!';
        $this->response['data'] = $teams;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function destroy($guid){
        $teams = Team::where('guid', $guid)->first();
        if(empty($teams)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);      
        }
        Team::where('id', $teams->id)->delete();

        $this->response['message'] = 'Team deleted successfully!';
        $this->response['data'] = $teams;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function assignUserToTeams(Request $request){

        $validator = Validator::make($request->all(), [
            'team_id' => 'required|numeric',
            'user_id' => 'required|array'

        ]);
  
        if($validator->fails()){
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status); 
        }
        $teams = Team::findOrFail($request->team_id);
        if(empty($teams)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);  
        }

        if(isset($request->user_id) && !empty($request->user_id)){
            foreach ($request->user_id as $key => $value) {
                $user = User::findOrFail($value);
                if(!empty($user)){
                    $user->team_id = $request->team_id;
                    $user->Save();
                }
            }
        }
        $teams = Team::findOrFail($request->team_id);
        

        $this->response['message'] = 'Team assign successfully!';
        $this->response['data'] = $teams;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }

    public function get_teams_detail($team_id){

        $teams = Team::where('id', $team_id)->with('users')->first();

        $this->response['message'] = 'Team Create successfully!';
        $this->response['data'] = $teams;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }
    
    public function removeTeamFromUser(Request $request){

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric'

        ]);
  
        if($validator->fails()){
            $this->status = 422;
            $this->response['status'] = $this->status;
            $this->response['success'] = false;
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, $this->status); 
        }
        $user = User::findOrFail($request->user_id);
        if(empty($user)){
            $this->status = 400;
            $this->response['status'] = $this->status;
            $this->response['success'] = true;
            $this->response['message'] = 'Record not found';
            return response()->json($this->response, $this->status);  
        }

        $user->team_id = 0;
        $user->Save();
   

        $this->response['message'] = 'User remove from team successfully!';
        $this->response['data'] = $user;
        $this->response['status'] = $this->status;
        return response()->json($this->response, $this->status);
    }
    

}

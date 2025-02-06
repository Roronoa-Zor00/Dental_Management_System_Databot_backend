<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PatientCase;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    
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

    public function adminDashboard(Request $request){

        $role_names = $this->role_name;
        $start_date = date('Y-m-d');  

        $date_d = strtotime($start_date);
        $date_d = strtotime("-1 day", $date_d);
        $end_date_d = date('Y-m-d', $date_d);

        $date = strtotime($start_date);
        $date = strtotime("-7 day", $date);
        $end_date = date('Y-m-d', $date);

        $date_m = strtotime($start_date);
        $date_m = strtotime("-30 day", $date_m);
        $end_date_m = date('Y-m-d', $date_m);

        $date_y = strtotime($start_date);
        $date_y = strtotime("-365 day", $date_y);
        $end_date_y = date('Y-m-d', $date_y);

        $daily_date = [$end_date_d, $start_date];
        $weekly_date = [$end_date, $start_date];
        $monthly_date = [$end_date_m, $start_date];
        $yearly_date = [$end_date_y, $start_date];
        
        // dd($daily_date, $weekly_date, $monthly_date, $yearly_date);
        $patient_cases = [];
        $patient_cases = User::withCount(['created_user_cases', 'assign_to_casses', 'planner_casses', 'qa_cases', 'post_processing_cases', 'my_cases', 'sub_client_cases'])->withOut(['permissions', 'roles'])->where('id', auth()->user()->id)->first();
        
        $patient_cases->makeHidden('permissions');
        $patient_cases->makeHidden('roles');
        
        $this->getCasesCounts($patient_cases);
        if(!empty($patient_cases)){
            $this->getWeeklyCasesCount($patient_cases);
            
            // weekly 
            $this->getWeeklyCasesCount($patient_cases, 0);
            $this->getWeeklyCasesCount($patient_cases, 1);
            $this->getWeeklyCasesCount($patient_cases, 2);
            $this->getWeeklyCasesCount($patient_cases, 3);
            $this->getWeeklyCasesCount($patient_cases, 4);
            $this->getWeeklyCasesCount($patient_cases, 5);
            $this->getWeeklyCasesCount($patient_cases, 6);
            $this->getWeeklyCasesCount($patient_cases, 7);
            $this->getWeeklyCasesCount($patient_cases, 8);
            $this->getWeeklyCasesCount($patient_cases, 9);
            $this->getWeeklyCasesCount($patient_cases, 10);
            $this->getWeeklyCasesCount($patient_cases, 11);
            $this->getWeeklyCasesCount($patient_cases, 12);
            $this->getWeeklyCasesCount($patient_cases, 13);
            $this->getWeeklyCasesCount($patient_cases, 14);
            $this->getWeeklyCasesCount($patient_cases, 15);
            $this->getWeeklyCasesCount($patient_cases, 16);
            $this->getWeeklyCasesCount($patient_cases, 17);
            $this->getWeeklyCasesCount($patient_cases, 18);

            // // monthly 
            // $this->getMonthlyCasesCount($patient_cases, 0);
            // $this->getMonthlyCasesCount($patient_cases, 1);
            // $this->getMonthlyCasesCount($patient_cases, 2);
            // $this->getMonthlyCasesCount($patient_cases, 3);
            // $this->getMonthlyCasesCount($patient_cases, 4);
            // $this->getMonthlyCasesCount($patient_cases, 5);
            // $this->getMonthlyCasesCount($patient_cases, 6);
            // $this->getMonthlyCasesCount($patient_cases, 7);
            // $this->getMonthlyCasesCount($patient_cases, 8);
            // $this->getMonthlyCasesCount($patient_cases, 9);
            // $this->getMonthlyCasesCount($patient_cases, 10);
            // $this->getMonthlyCasesCount($patient_cases, 11);
            // $this->getMonthlyCasesCount($patient_cases, 12);
            // $this->getMonthlyCasesCount($patient_cases, 13);
            // $this->getMonthlyCasesCount($patient_cases, 14);
            // $this->getMonthlyCasesCount($patient_cases, 15);
            // $this->getMonthlyCasesCount($patient_cases, 16);


            // Get the current date and 30 days ago
            $startDate = Carbon::now()->subDays(330);
            $endDate = Carbon::now();
            // weekly 
            $this->getWeeklyCasesCounts($patient_cases, 0, $startDate, $endDate);
            $this->getWeeklyCasesCounts($patient_cases, 1, $startDate, $endDate);
            $this->getWeeklyCasesCounts($patient_cases, 2, $startDate, $endDate);
            $this->getWeeklyCasesCounts($patient_cases, 3, $startDate, $endDate);
            $this->getWeeklyCasesCounts($patient_cases, 4, $startDate, $endDate);
            $this->getWeeklyCasesCounts($patient_cases, 5, $startDate, $endDate);
            $this->getWeeklyCasesCounts($patient_cases, 6, $startDate, $endDate);
            $this->getWeeklyCasesCounts($patient_cases, 7, $startDate, $endDate);
            $this->getWeeklyCasesCounts($patient_cases, 8, $startDate, $endDate);
            $this->getWeeklyCasesCounts($patient_cases, 9, $startDate, $endDate);
            $this->getWeeklyCasesCounts($patient_cases, 10, $startDate, $endDate);
            $this->getWeeklyCasesCounts($patient_cases, 11, $startDate, $endDate);
            $this->getWeeklyCasesCounts($patient_cases, 12, $startDate, $endDate);
            $this->getWeeklyCasesCounts($patient_cases, 13, $startDate, $endDate);
            $this->getWeeklyCasesCounts($patient_cases, 14, $startDate, $endDate);
            $this->getWeeklyCasesCounts($patient_cases, 15, $startDate, $endDate);
            $this->getWeeklyCasesCounts($patient_cases, 16, $startDate, $endDate);
            $this->getWeeklyCasesCounts($patient_cases, 17, $startDate, $endDate);
            $this->getWeeklyCasesCounts($patient_cases, 18, $startDate, $endDate);

        }
        // $this->getDailyCounts($patient_cases, $daily_date, $type = 'daily');
        // $this->getWeeklyCounts($patient_cases, $weekly_date, $type = 'weekly');
        $this->getMonthlyCounts($patient_cases, $monthly_date, $type = 'monthly');
        // $this->getYearlyCounts($patient_cases, $yearly_date, $type = 'yearly');

        // if(isset(request()->debug) && request()->debug == 1){
            $this->getCountsByGroups($patient_cases, 0);
            $this->getCountsByGroups($patient_cases, 1);
            $this->getCountsByGroups($patient_cases, 2);
            $this->getCountsByGroups($patient_cases, 3);
            $this->getCountsByGroups($patient_cases, 4);
            $this->getCountsByGroups($patient_cases, 5);
            $this->getCountsByGroups($patient_cases, 6);
            $this->getCountsByGroups($patient_cases, 7);
            $this->getCountsByGroups($patient_cases, 8);
            $this->getCountsByGroups($patient_cases, 9);
            $this->getCountsByGroups($patient_cases, 10);
            $this->getCountsByGroups($patient_cases, 11);
            $this->getCountsByGroups($patient_cases, 12);
            $this->getCountsByGroups($patient_cases, 13);
            $this->getCountsByGroups($patient_cases, 14);
            $this->getCountsByGroups($patient_cases, 15);
            $this->getCountsByGroups($patient_cases, 16);
            $this->getCountsByGroups($patient_cases, 17);
            $this->getCountsByGroups($patient_cases, 18);
            // dd($this->getCountsByGroups());
            $this->getCasesCountByTeamsMonthly($patient_cases, 0);
            $this->getCasesCountByTeamsMonthly($patient_cases, 1);
            $this->getCasesCountByTeamsMonthly($patient_cases, 2);
            $this->getCasesCountByTeamsMonthly($patient_cases, 3);
            $this->getCasesCountByTeamsMonthly($patient_cases, 4);
            $this->getCasesCountByTeamsMonthly($patient_cases, 5);
            $this->getCasesCountByTeamsMonthly($patient_cases, 6);
            $this->getCasesCountByTeamsMonthly($patient_cases, 7);
            $this->getCasesCountByTeamsMonthly($patient_cases, 8);
            $this->getCasesCountByTeamsMonthly($patient_cases, 9);
            $this->getCasesCountByTeamsMonthly($patient_cases, 10);
            $this->getCasesCountByTeamsMonthly($patient_cases, 11);
            $this->getCasesCountByTeamsMonthly($patient_cases, 12);
            $this->getCasesCountByTeamsMonthly($patient_cases, 13);
            $this->getCasesCountByTeamsMonthly($patient_cases, 14);
            $this->getCasesCountByTeamsMonthly($patient_cases, 15);
            $this->getCasesCountByTeamsMonthly($patient_cases, 16);
            $this->getCasesCountByTeamsMonthly($patient_cases, 17);
            $this->getCasesCountByTeamsMonthly($patient_cases, 18);

            $this->getCaseCountsByClientSubClients($patient_cases);
            $this->getCaseCountsByClientSubClients($patient_cases, 0);
            $this->getCaseCountsByClientSubClients($patient_cases, 1);
            $this->getCaseCountsByClientSubClients($patient_cases, 2);
            $this->getCaseCountsByClientSubClients($patient_cases, 3);
            $this->getCaseCountsByClientSubClients($patient_cases, 4);
            $this->getCaseCountsByClientSubClients($patient_cases, 5);
            $this->getCaseCountsByClientSubClients($patient_cases, 6);
            $this->getCaseCountsByClientSubClients($patient_cases, 7);
            $this->getCaseCountsByClientSubClients($patient_cases, 8);
            $this->getCaseCountsByClientSubClients($patient_cases, 9);
            $this->getCaseCountsByClientSubClients($patient_cases, 10);
            $this->getCaseCountsByClientSubClients($patient_cases, 11);
            $this->getCaseCountsByClientSubClients($patient_cases, 12);
            $this->getCaseCountsByClientSubClients($patient_cases, 13);
            $this->getCaseCountsByClientSubClients($patient_cases, 14);
            $this->getCaseCountsByClientSubClients($patient_cases, 15);
            $this->getCaseCountsByClientSubClients($patient_cases, 16);
            $this->getCaseCountsByClientSubClients($patient_cases, 17);
            $this->getCaseCountsByClientSubClients($patient_cases, 18);


            $this->getCaseCountsByClientSubClientsDaily($patient_cases);
            $this->getCaseCountsByClientSubClientsDaily($patient_cases, 0);
            $this->getCaseCountsByClientSubClientsDaily($patient_cases, 1);
            $this->getCaseCountsByClientSubClientsDaily($patient_cases, 2);
            $this->getCaseCountsByClientSubClientsDaily($patient_cases, 3);
            $this->getCaseCountsByClientSubClientsDaily($patient_cases, 4);
            $this->getCaseCountsByClientSubClientsDaily($patient_cases, 5);
            $this->getCaseCountsByClientSubClientsDaily($patient_cases, 6);
            $this->getCaseCountsByClientSubClientsDaily($patient_cases, 7);
            $this->getCaseCountsByClientSubClientsDaily($patient_cases, 8);
            $this->getCaseCountsByClientSubClientsDaily($patient_cases, 9);
            $this->getCaseCountsByClientSubClientsDaily($patient_cases, 10);
            $this->getCaseCountsByClientSubClientsDaily($patient_cases, 11);
            $this->getCaseCountsByClientSubClientsDaily($patient_cases, 12);
            $this->getCaseCountsByClientSubClientsDaily($patient_cases, 13);
            $this->getCaseCountsByClientSubClientsDaily($patient_cases, 14);
            $this->getCaseCountsByClientSubClientsDaily($patient_cases, 15);
            $this->getCaseCountsByClientSubClientsDaily($patient_cases, 16);
            $this->getCaseCountsByClientSubClientsDaily($patient_cases, 17);
            $this->getCaseCountsByClientSubClientsDaily($patient_cases, 18);


            
            // weekly 
            $this->getWeeklyCasesCountSubclient($patient_cases);
            $this->getWeeklyCasesCountSubclient($patient_cases, 0);
            $this->getWeeklyCasesCountSubclient($patient_cases, 1);
            $this->getWeeklyCasesCountSubclient($patient_cases, 2);
            $this->getWeeklyCasesCountSubclient($patient_cases, 3);
            $this->getWeeklyCasesCountSubclient($patient_cases, 4);
            $this->getWeeklyCasesCountSubclient($patient_cases, 5);
            $this->getWeeklyCasesCountSubclient($patient_cases, 6);
            $this->getWeeklyCasesCountSubclient($patient_cases, 7);
            $this->getWeeklyCasesCountSubclient($patient_cases, 8);
            $this->getWeeklyCasesCountSubclient($patient_cases, 9);
            $this->getWeeklyCasesCountSubclient($patient_cases, 10);
            $this->getWeeklyCasesCountSubclient($patient_cases, 11);
            $this->getWeeklyCasesCountSubclient($patient_cases, 12);
            $this->getWeeklyCasesCountSubclient($patient_cases, 13);
            $this->getWeeklyCasesCountSubclient($patient_cases, 14);
            $this->getWeeklyCasesCountSubclient($patient_cases, 15);
            $this->getWeeklyCasesCountSubclient($patient_cases, 16);
            $this->getWeeklyCasesCountSubclient($patient_cases, 17);
            $this->getWeeklyCasesCountSubclient($patient_cases, 18);

            // Monthly 
            $this->getMonthlyCasesCountSubclients($patient_cases);
            $this->getMonthlyCasesCountSubclients($patient_cases, 0);
            $this->getMonthlyCasesCountSubclients($patient_cases, 1);
            $this->getMonthlyCasesCountSubclients($patient_cases, 2);
            $this->getMonthlyCasesCountSubclients($patient_cases, 3);
            $this->getMonthlyCasesCountSubclients($patient_cases, 4);
            $this->getMonthlyCasesCountSubclients($patient_cases, 5);
            $this->getMonthlyCasesCountSubclients($patient_cases, 6);
            $this->getMonthlyCasesCountSubclients($patient_cases, 7);
            $this->getMonthlyCasesCountSubclients($patient_cases, 8);
            $this->getMonthlyCasesCountSubclients($patient_cases, 9);
            $this->getMonthlyCasesCountSubclients($patient_cases, 10);
            $this->getMonthlyCasesCountSubclients($patient_cases, 11);
            $this->getMonthlyCasesCountSubclients($patient_cases, 12);
            $this->getMonthlyCasesCountSubclients($patient_cases, 13);
            $this->getMonthlyCasesCountSubclients($patient_cases, 14);
            $this->getMonthlyCasesCountSubclients($patient_cases, 15);
            $this->getMonthlyCasesCountSubclients($patient_cases, 16);
            $this->getMonthlyCasesCountSubclients($patient_cases, 17);
            $this->getMonthlyCasesCountSubclients($patient_cases, 18);

            $this->getAvgAllCases($patient_cases);
            $this->getAvgAllCountsByCreatedCase($patient_cases);
            
            
            $this->newAndModificationCases($patient_cases);
            
            
        // }
        // if(isset(request()->debug) && request()->debug == 1){
            $this->getRatioChartData($patient_cases, $request);
        // }
        

        if(empty($patient_cases)){
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

    public function getDailyCounts($patient_cases, $date, $type){

        $role_names = $this->role_name;
        $data = [];
        $patient_cases['cases_count_'.$type] = PatientCase::select(
                                            DB::raw('DATE(created_at) as date'),
                                            DB::raw('count(*) as count')
                                        )
                                    ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                                    ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                                        if($type == 'daily'){
                                                            return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                                        }else{
                                                            return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                                        }
                                                    })
                                            ->groupBy('date')
                                            ->orderBy('date', 'desc')
                                            ->get();
        $patient_cases['cases_0_count_'.$type] = PatientCase::select(
                                                            DB::raw('DATE(created_at) as date'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 0)
                                    ->when($role_names, function($q) use($role_names){
                                        if($role_names == 'post_processing'){
                                            $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                        }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                            if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                            }else{
                                                $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                            }
                                        }
                                    })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('date')
                                ->orderBy('date', 'desc')
                                ->get();
        $patient_cases['cases_1_count_'.$type] = PatientCase::select(
                                            DB::raw('DATE(created_at) as date'),
                                            DB::raw('count(*) as count')
                                        )->where('status', 1)
                                    ->when($role_names, function($q) use($role_names){
                                        if($role_names == 'post_processing'){
                                            $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                        }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                            if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                            }else{
                                                $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                            }
                                        }
                                    })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('date')
                                ->orderBy('date', 'desc')
                                ->get();
        $patient_cases['cases_2_count_'.$type] = PatientCase::select(
                                            DB::raw('DATE(created_at) as date'),
                                            DB::raw('count(*) as count')
                                        )->where('status', 2)
                                        ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('date')
                                ->orderBy('date', 'desc')
                                ->get();
        $patient_cases['cases_3_count_'.$type] = PatientCase::select(
                                            DB::raw('DATE(created_at) as date'),
                                            DB::raw('count(*) as count')
                                        )->where('status', 3)
                                    ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('date')
                                ->orderBy('date', 'desc')
                                ->get();
        $patient_cases['cases_4_count_'.$type] = PatientCase::select(
                                            DB::raw('DATE(created_at) as date'),
                                            DB::raw('count(*) as count')
                                        )->where('status', 4)
                                    ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('date')
                                ->orderBy('date', 'desc')
                                ->get();
        $patient_cases['cases_5_count_'.$type] = PatientCase::select(
                                            DB::raw('DATE(created_at) as date'),
                                            DB::raw('count(*) as count')
                                        )->where('status', 5)
                                    ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('date')
                                ->orderBy('date', 'desc')
                                ->get();
        $patient_cases['cases_6_count_'.$type] = PatientCase::select(
                                            DB::raw('DATE(created_at) as date'),
                                            DB::raw('count(*) as count')
                                        )->where('status', 6)
                                    ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('date')
                                ->orderBy('date', 'desc')
                                ->get();
        $patient_cases['cases_7_count_'.$type] = PatientCase::select(
                                            DB::raw('DATE(created_at) as date'),
                                            DB::raw('count(*) as count')
                                        )->where('status', 7)
                                    ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('date')
                                ->orderBy('date', 'desc')
                                ->get();
        $patient_cases['cases_8_count_'.$type] = PatientCase::select(
                                            DB::raw('DATE(created_at) as date'),
                                            DB::raw('count(*) as count')
                                        )->where('status', 8)
                                    ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('date')
                                ->orderBy('date', 'desc')
                                ->get();
        $patient_cases['cases_9_count_'.$type] = PatientCase::select(
                                            DB::raw('DATE(created_at) as date'),
                                            DB::raw('count(*) as count')
                                        )->where('status', 9)
                                    ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->count();
        $patient_cases['cases_10_count_'.$type] = PatientCase::select(
                                            DB::raw('DATE(created_at) as date'),
                                            DB::raw('count(*) as count')
                                        )->where('status', 10)
                                ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('date')
                                ->orderBy('date', 'desc')
                                ->get();
        $patient_cases['cases_11_count_'.$type] = PatientCase::select(
                                            DB::raw('DATE(created_at) as date'),
                                            DB::raw('count(*) as count')
                                        )->where('status', 11)
                                ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('date')
                                ->orderBy('date', 'desc')
                                ->get();
        $patient_cases['cases_12_count_'.$type] = PatientCase::select(
                                            DB::raw('DATE(created_at) as date'),
                                            DB::raw('count(*) as count')
                                        )->where('status', 12)
                                    ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('date')
                                ->orderBy('date', 'desc')
                                ->get();
        $patient_cases['cases_13_count_'.$type] = PatientCase::select(
                                            DB::raw('DATE(created_at) as date'),
                                            DB::raw('count(*) as count')
                                        )->where('status', 13)
                                    ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('date')
                                ->orderBy('date', 'desc')
                                ->get();
        $patient_cases['cases_14_count_'.$type] = PatientCase::select(
                                            DB::raw('DATE(created_at) as date'),
                                            DB::raw('count(*) as count')
                                        )->where('status', 14)
                                ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('date')
                                ->orderBy('date', 'desc')
                                ->get();
        $patient_cases['cases_15_count_'.$type] = PatientCase::select(
                                            DB::raw('DATE(created_at) as date'),
                                            DB::raw('count(*) as count')
                                        )->where('status', 18)
                                ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('date')
                                ->orderBy('date', 'desc')
                                ->get();
        $patient_cases['cases_16_count_'.$type] = PatientCase::select(
                                            DB::raw('DATE(created_at) as date'),
                                            DB::raw('count(*) as count')
                                        )->where('status', 16)
                                ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('date')
                                ->orderBy('date', 'desc')
                                ->get();

        return $patient_cases;
    }


    public function getWeeklyCounts($patient_cases, $date, $type){

        $role_names = $this->role_name;

        $data = [];
        $patient_cases['cases_count_'.$type] = PatientCase::where('created_at', '>=', Carbon::now()->subWeek())
                                   ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                                    ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                                        if($type == 'daily'){
                                                            return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                                        }else{
                                                            return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                                        }
                                                    })
                                            ->orderBy('created_at', 'asc')
                                            ->get();
        $patient_cases['cases_0_count_'.$type] = PatientCase::where('created_at', '>=', Carbon::now()->subWeek())->where('status', 0)
                                    ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->orderBy('created_at', 'asc')
                                ->get();
        $patient_cases['cases_1_count_'.$type] = PatientCase::where('created_at', '>=', Carbon::now()->subWeek())->where('status', 1)
                                    ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->orderBy('created_at', 'asc')
                                ->get();
        $patient_cases['cases_2_count_'.$type] = PatientCase::where('created_at', '>=', Carbon::now()->subWeek())->where('status', 2)
                                    ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->orderBy('created_at', 'asc')
                                ->get();
        $patient_cases['cases_3_count_'.$type] = PatientCase::where('created_at', '>=', Carbon::now()->subWeek())->where('status', 3)
                                   ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->orderBy('created_at', 'asc')
                                ->get();
        $patient_cases['cases_4_count_'.$type] = PatientCase::where('created_at', '>=', Carbon::now()->subWeek())->where('status', 4)
                                    ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->orderBy('created_at', 'asc')
                                ->get();
        $patient_cases['cases_5_count_'.$type] = PatientCase::where('created_at', '>=', Carbon::now()->subWeek())->where('status', 5)
                                    ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->orderBy('created_at', 'asc')
                                ->get();
        $patient_cases['cases_6_count_'.$type] = PatientCase::where('created_at', '>=', Carbon::now()->subWeek())->where('status', 6)
                                    ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->orderBy('created_at', 'asc')
                                ->get();
        $patient_cases['cases_7_count_'.$type] = PatientCase::where('created_at', '>=', Carbon::now()->subWeek())->where('status', 7)
                                    ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->orderBy('created_at', 'asc')
                                ->get();
        $patient_cases['cases_8_count_'.$type] = PatientCase::where('created_at', '>=', Carbon::now()->subWeek())->where('status', 8)
                                    ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->orderBy('created_at', 'asc')
                                ->get();
        $patient_cases['cases_9_count_'.$type] = PatientCase::where('created_at', '>=', Carbon::now()->subWeek())->where('status', 9)
                                    ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->orderBy('created_at', 'asc')
                                ->count();
        $patient_cases['cases_10_count_'.$type] = PatientCase::where('created_at', '>=', Carbon::now()->subWeek())->where('status', 10)
                                    ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->orderBy('created_at', 'asc')
                                ->get();
        $patient_cases['cases_11_count_'.$type] = PatientCase::where('created_at', '>=', Carbon::now()->subWeek())->where('status', 11)
                                    ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->orderBy('created_at', 'asc')
                                ->get();
        $patient_cases['cases_12_count_'.$type] = PatientCase::where('created_at', '>=', Carbon::now()->subWeek())->where('status', 12)
                                    ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->orderBy('created_at', 'asc')
                                ->get();
        $patient_cases['cases_13_count_'.$type] = PatientCase::where('created_at', '>=', Carbon::now()->subWeek())->where('status', 13)
                                        ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->orderBy('created_at', 'asc')
                                ->get();
        $patient_cases['cases_14_count_'.$type] = PatientCase::where('created_at', '>=', Carbon::now()->subWeek())->where('status', 14)
                                       ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->orderBy('created_at', 'asc')
                                ->get();
        $patient_cases['cases_15_count_'.$type] = PatientCase::where('created_at', '>=', Carbon::now()->subWeek())->where('status', 18)
                                        ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->orderBy('created_at', 'asc')
                                ->get();
        $patient_cases['cases_16_count_'.$type] = PatientCase::where('created_at', '>=', Carbon::now()->subWeek())->where('status', 16)
                                        ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->orderBy('created_at', 'asc')
                                ->get();

        return $patient_cases;
    }

    public function getMonthlyCounts($patient_cases, $date, $type){

        $role_names = $this->role_name;
        $data = [];
        $patient_cases['cases_count_'.$type] = PatientCase::select(
                                                    DB::raw('YEAR(created_at) as year'),
                                                    DB::raw('MONTH(created_at) as month'),
                                                    DB::raw('count(*) as count')
                                                )
                                            ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                                    ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                                        if($type == 'daily'){
                                                            return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                                        }else{
                                                            return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                                        }
                                                    })
                                            ->groupBy('year', 'month')
                                            ->orderBy('year', 'desc')
                                            ->orderBy('month', 'desc')
                                            ->get();
        $patient_cases['cases_0_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('MONTH(created_at) as month'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 0)
                                        ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year', 'month')
                                ->orderBy('year', 'desc')
                                ->orderBy('month', 'desc')
                                ->get();
        $patient_cases['cases_1_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('MONTH(created_at) as month'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 1)
                                        ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year', 'month')
                                ->orderBy('year', 'desc')
                                ->orderBy('month', 'desc')
                                ->get();
        $patient_cases['cases_2_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('MONTH(created_at) as month'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 2)
                                        ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year', 'month')
                                ->orderBy('year', 'desc')
                                ->orderBy('month', 'desc')
                                ->get();
        $patient_cases['cases_3_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('MONTH(created_at) as month'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 3)
                                    ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year', 'month')
                                ->orderBy('year', 'desc')
                                ->orderBy('month', 'desc')
                                ->get();
        $patient_cases['cases_4_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('MONTH(created_at) as month'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 4)
                                    ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year', 'month')
                                ->orderBy('year', 'desc')
                                ->orderBy('month', 'desc')
                                ->get();
        $patient_cases['cases_5_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('MONTH(created_at) as month'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 5)
                                   ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year', 'month')
                                ->orderBy('year', 'desc')
                                ->orderBy('month', 'desc')
                                ->get();
        $patient_cases['cases_6_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('MONTH(created_at) as month'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 6)
                                        ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year', 'month')
                                ->orderBy('year', 'desc')
                                ->orderBy('month', 'desc')
                                ->get();
        $patient_cases['cases_7_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('MONTH(created_at) as month'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 7)
                                        ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year', 'month')
                                ->orderBy('year', 'desc')
                                ->orderBy('month', 'desc')
                                ->get();
        $patient_cases['cases_8_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('MONTH(created_at) as month'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 8)
                                        ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year', 'month')
                                ->orderBy('year', 'desc')
                                ->orderBy('month', 'desc')
                                ->get();
        $patient_cases['cases_9_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('MONTH(created_at) as month'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 9)
                                            ->when($role_names, function($q) use($role_names){
                                                if($role_names == 'post_processing'){
                                                    $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                                }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                    if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                        $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                        //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                    }else{
                                                        $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                    }
                                                }
                                            })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->count();
        $patient_cases['cases_10_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('MONTH(created_at) as month'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 10)
                                                ->when($role_names, function($q) use($role_names){
                                                    if($role_names == 'post_processing'){
                                                        $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                                    }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                        if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                            $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                            //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                        }else{
                                                            $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                        }
                                                    }
                                                })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year', 'month')
                                ->orderBy('year', 'desc')
                                ->orderBy('month', 'desc')
                                ->get();
        $patient_cases['cases_11_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('MONTH(created_at) as month'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 11)
                                                ->when($role_names, function($q) use($role_names){
                                                    if($role_names == 'post_processing'){
                                                        $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                                    }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                        if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                            $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                            //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                        }else{
                                                            $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                        }
                                                    }
                                                })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year', 'month')
                                ->orderBy('year', 'desc')
                                ->orderBy('month', 'desc')
                                ->get();
        $patient_cases['cases_12_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('MONTH(created_at) as month'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 12)
                                                    ->when($role_names, function($q) use($role_names){
                                                        if($role_names == 'post_processing'){
                                                            $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                                        }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                            if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                                $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                                //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                            }else{
                                                                $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                            }
                                                        }
                                                    })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year', 'month')
                                ->orderBy('year', 'desc')
                                ->orderBy('month', 'desc')
                                ->get();
        $patient_cases['cases_13_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('MONTH(created_at) as month'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 13)
                                                ->when($role_names, function($q) use($role_names){
                                                    if($role_names == 'post_processing'){
                                                        $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                                    }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                        if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                            $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                            //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                        }else{
                                                            $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                        }
                                                    }
                                                })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year', 'month')
                                ->orderBy('year', 'desc')
                                ->orderBy('month', 'desc')
                                ->get();
        $patient_cases['cases_14_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('MONTH(created_at) as month'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 14)
                                                ->when($role_names, function($q) use($role_names){
                                                    if($role_names == 'post_processing'){
                                                        $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                                    }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                        if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                            $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                            //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                        }else{
                                                            $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                        }
                                                    }
                                                })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year', 'month')
                                ->orderBy('year', 'desc')
                                ->orderBy('month', 'desc')
                                ->get();
        $patient_cases['cases_15_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('MONTH(created_at) as month'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 18)
                                        ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year', 'month')
                                ->orderBy('year', 'desc')
                                ->orderBy('month', 'desc')
                                ->get();
        $patient_cases['cases_16_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('MONTH(created_at) as month'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 16)
                                    ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year', 'month')
                                ->orderBy('year', 'desc')
                                ->orderBy('month', 'desc')
                                ->get();

        return $patient_cases;
    }

    public function getYearlyCounts($patient_cases, $date, $type){

        $role_names = $this->role_name;
        $data = [];
        $patient_cases['cases_count_'.$type] = PatientCase::select(
                                            DB::raw('YEAR(created_at) as year'),
                                            DB::raw('count(*) as count')
                                        )

                                        ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                                    ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                                        if($type == 'daily'){
                                                            return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                                        }else{
                                                            return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                                        }
                                                    })
                                            ->groupBy('year')
                                            ->orderBy('year', 'desc')
                                            ->get();
        $patient_cases['cases_0_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 0)
                                        ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year')
                                ->orderBy('year', 'desc')
                                ->get();

        $patient_cases['cases_1_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 1)
                                        ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year')
                                ->orderBy('year', 'desc')
                                ->get();
        $patient_cases['cases_2_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 2)
                                        ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year')
                                ->orderBy('year', 'desc')
                                ->get();
        $patient_cases['cases_3_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 3)
                                            ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year')
                                ->orderBy('year', 'desc')
                                ->get();
        $patient_cases['cases_4_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 4)
                                           ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year')
                                ->orderBy('year', 'desc')
                                ->get();
        $patient_cases['cases_5_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 5)
                                            ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year')
                                ->orderBy('year', 'desc')
                                ->get();
        $patient_cases['cases_6_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 6)
                                        ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year')
                                ->orderBy('year', 'desc')
                                ->get();
        $patient_cases['cases_7_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 7)
                                            ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year')
                                ->orderBy('year', 'desc')
                                ->get();
        $patient_cases['cases_8_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 8)
                                        ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year')
                                ->orderBy('year', 'desc')
                                ->get();
        $patient_cases['cases_9_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 9)
                                        ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->count();
        $patient_cases['cases_10_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 10)
                                                ->when($role_names, function($q) use($role_names){
                                                        if($role_names == 'post_processing'){
                                                            $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                                        }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                            if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                                $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                                //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                            }else{
                                                                $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                            }
                                                        }
                                                    })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year')
                                ->orderBy('year', 'desc')
                                ->get();
        $patient_cases['cases_11_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 11)
                                                ->when($role_names, function($q) use($role_names){
                                                    if($role_names == 'post_processing'){
                                                        $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                                    }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                        if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                            $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                            //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                        }else{
                                                            $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                        }
                                                    }
                                                })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year')
                                ->orderBy('year', 'desc')
                                ->get();
        $patient_cases['cases_12_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 12)
                                            ->when($role_names, function($q) use($role_names){
                                                if($role_names == 'post_processing'){
                                                    $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                                }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                    if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                        $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                        //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                    }else{
                                                        $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                    }
                                                }
                                            })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year')
                                ->orderBy('year', 'desc')
                                ->get();
        $patient_cases['cases_13_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 13)
                                        ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year')
                                ->orderBy('year', 'desc')
                                ->get();
        $patient_cases['cases_14_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 14)
                                        ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year')
                                ->orderBy('year', 'desc')
                                ->get();
        $patient_cases['cases_15_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 18)
                                            ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year')
                                ->orderBy('year', 'desc')
                                ->get();
        $patient_cases['cases_16_count_'.$type] = PatientCase::select(
                                                            DB::raw('YEAR(created_at) as year'),
                                                            DB::raw('count(*) as count')
                                                        )->where('status', 16)
                                            ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                ->when((!empty($type) && !is_null($type)), function($query) use($date, $type){
                                    if($type == 'daily'){
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }else{
                                        return $query->whereBetween(DB::raw('date(created_at)'), [$date]);
                                    }
                                })
                                ->groupBy('year')
                                ->orderBy('year', 'desc')
                                ->get();

        return $patient_cases;
    }



    public function getCasesCounts($patient_cases){
        
        $role_names = $this->role_name;

        $patient_cases['cases_count'] = PatientCase::when($role_names, function($q) use($role_names){
                                    if($role_names == 'post_processing'){
                                        $q->where('verified_by_client', 1);
                                    }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                        $q->where(function($query){
                                            $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->Orwhere('client_id', auth()->user()->id);
                                        });
                                    }
                                    })->count();
        $patient_cases['cases_0_count'] = PatientCase::where('status', 0)
                                           ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })->count();
        $patient_cases['cases_1_count'] = PatientCase::where('status', 1)
                                                    ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })->count();
        $patient_cases['cases_2_count'] = PatientCase::where('status', 2)
                                                ->when($role_names, function($q) use($role_names){
                                                    if($role_names == 'post_processing'){
                                                        $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                                    }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                        if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                            $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                            //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                        }else{
                                                            $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                        }
                                                    }
                                                })
                                                ->count();
        $patient_cases['cases_3_count'] = PatientCase::where('status', 3)
                                                       ->when($role_names, function($q) use($role_names){
                                                        if($role_names == 'post_processing'){
                                                            $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                                        }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                            if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                                $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                                //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                            }else{
                                                                $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                            }
                                                        }
                                                    })
                                                ->count();
        $patient_cases['cases_4_count'] = PatientCase::where('status', 4)
                                            ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })->count();
        $patient_cases['cases_5_count'] = PatientCase::where('status', 5)
                                            ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                            ->count();
        $patient_cases['cases_6_count'] = PatientCase::where('status', 6)
                                            ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })
                                            ->count();
        $patient_cases['cases_7_count'] = PatientCase::where('status', 7)
                                                ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })->count();
        $patient_cases['cases_8_count'] = PatientCase::where('status', 8)
                                            ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })->count();
        $patient_cases['cases_9_count'] = PatientCase::where('status', 9)
                                            ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })->count();
        $patient_cases['cases_10_count'] = PatientCase::where('status', 10)
                                                ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })->count();
        $patient_cases['cases_11_count'] = PatientCase::where('status', 11)
                                               ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })->count();
        $patient_cases['cases_12_count'] = PatientCase::where('status', 12)
                                            ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })->count();
        $patient_cases['cases_13_count'] = PatientCase::where('status', 13)
                                            ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })->count();
        $patient_cases['cases_14_count'] = PatientCase::where('status', 14)
                                            ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })->count();
        $patient_cases['cases_15_count'] = PatientCase::where('status', 15)
                                            ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })->count();
        $patient_cases['cases_16_count'] = PatientCase::where('status', 16)
                                               ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })->count();

        return $patient_cases;
    }


    public function getWeeklyCasesCount($patient_cases, $count = null){

        $role_names = $this->role_name;

        if(is_null($count)){
            // $patient_cases['cases_count_weekly']
            $patient_case = PatientCase::where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
                                                        ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })->orderBy('created_at', 'asc')->get();
            $grouped_patient_cases = $patient_case->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('l'); // Group by day name
            });
            $daily_patient_cases_counts = [];
            foreach ($grouped_patient_cases as $day => $products) {
                $daily_patient_cases_counts[] = [
                    'day' => $day,
                    'count' => $products->count()
                ];
            }
            $patient_cases['cases_count_weekly'] = $daily_patient_cases_counts;

        }else{

            $patient_case = PatientCase::where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
                                                            ->where('status', $count)
                                                            ->when($role_names, function($q) use($role_names){
                                                                if($role_names == 'post_processing'){
                                                                    $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                                                }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                                    if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                                        $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                                        //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                                    }else{
                                                                        $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                                    }
                                                                }
                                                            })->orderBy('created_at', 'asc')->get();
            
            $grouped_patient_cases = $patient_case->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('l'); // Group by day name
            });
            $daily_patient_cases_counts = [];
            foreach ($grouped_patient_cases as $day => $products) {
                $daily_patient_cases_counts[] = [
                    'day' => $day,
                    'count' => $products->count()
                ];
            }
            $patient_cases['cases_'.$count.'_count_weekly'] = $daily_patient_cases_counts;
        }
        return $patient_cases;

    }

    public function getMonthlyCasesCount($patient_cases, $count = null){

        $role_names = $this->role_name;

        if(is_null($count)){
            $patient_case = PatientCase::where('created_at', '>=', Carbon::now()->subYear())
                                                        ->when($role_names, function($q) use($role_names){
                                                    if($role_names == 'post_processing'){
                                                        $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                                    }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                        if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                            $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                            //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                        }else{
                                                            $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                        }
                                                    }
                                                })->orderBy('created_at', 'asc')->get();
            $grouped_patient_cases = $patient_case->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('F'); // Group by month name
            });
            $daily_patient_cases_counts = [];
            foreach ($grouped_patient_cases as $month => $products) {
                $daily_patient_cases_counts[] = [
                    'month' => $month,
                    'count' => $products->count()
                ];
            }
            $patient_cases['cases_count_monthly'] = $daily_patient_cases_counts;

        }else{

            $patient_case = PatientCase::where('created_at', '>=', Carbon::now()->subYear())
                                                            ->where('status', $count)
                                                            ->when($role_names, function($q) use($role_names){
                                                                if($role_names == 'post_processing'){
                                                                    $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                                                }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                                    if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                                        $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                                        //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                                    }else{
                                                                        $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                                    }
                                                                }
                                                            })->orderBy('created_at', 'asc')->get();
            
            $grouped_patient_cases = $patient_case->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('F'); // Group by month name
            });
            $daily_patient_cases_counts = [];
            foreach ($grouped_patient_cases as $month => $products) {
                $daily_patient_cases_counts[] = [
                    'month' => $month,
                    'count' => $products->count()
                ];
            }
            $patient_cases['cases_'.$count.'_count_monthly'] = $daily_patient_cases_counts;
        }
        return $patient_cases;

    }




    // get 30 days counts detail
    public function getWeeklyCasesCounts($patient_cases, $count = null, $start_date=null, $end_date=null){

        $role_names = $this->role_name;

        if(is_null($count)){
            // $patient_cases['cases_count_weekly']
            $patient_case = PatientCase::where('created_at','<=', $start_date)
                                            ->where('created_at', '>=', $end_date)
                                                        ->when($role_names, function($q) use($role_names){
                                            if($role_names == 'post_processing'){
                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                }else{
                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                }
                                            }
                                        })->orderBy('created_at', 'asc')->get();
            $grouped_patient_cases = $patient_case->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('l'); // Group by day name
            });
            $daily_patient_cases_counts = [];
            foreach ($grouped_patient_cases as $day => $products) {
                $daily_patient_cases_counts[] = [
                    'day' => $day,
                    'count' => $products->count()
                ];
            }
            $patient_cases['cases_count_months'] = $daily_patient_cases_counts;

        }else{

            $patient_case = PatientCase::where('created_at', '<=', $start_date)
                                        ->where('created_at', '>=', $end_date)
                                                            ->where('status', $count)
                                                            // ->when($role_names, function($q) use($role_names){
                                                            //     if($role_names == 'post_processing'){
                                                            //         $q->whereIn('status', [0])->where('verified_by_client', 1);
                                                            //     }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                            //         $q->where(function($query){
                                                            //             $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->Orwhere('client_id', auth()->user()->id);
                                                            //         });
                                                            //     }
                                                            // })
                                                            ->orderBy('created_at', 'asc')->get();
            
            $grouped_patient_cases = $patient_case->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('l'); // Group by day name
            });
            $daily_patient_cases_counts = [];
            foreach ($grouped_patient_cases as $day => $products) {
                $daily_patient_cases_counts[] = [
                    'day' => $day,
                    'count' => $products->count()
                ];
            }
            $patient_cases['cases_'.$count.'_count_months'] = $daily_patient_cases_counts;
        }
        return $patient_cases;

    }

    public function getCountsByGroups($patient_cases, $count){

        $role_names = $this->role_name;

        // Get today's date
        $today = Carbon::today();

        // Count products for the last 30 days grouped by team_id and day
        $daily_counts = PatientCase::join('users', 'patient_cases.created_by', '=', 'users.id')
            ->where('patient_cases.status', $count)
            ->whereNull('patient_cases.deleted_at')
            ->where('users.team_id','<>', 0)
            ->where('patient_cases.created_at', '>=', $today->subDays(7))
            ->selectRaw('users.team_id, DATE(patient_cases.created_at) as day, COUNT(patient_cases.id) as daily_count')
            ->groupBy('users.team_id', 'day')
            ->orderBy('day', 'asc')
            ->get();

        $patient_cases['teams_cases_'.$count.'_counts_daily'] = $daily_counts;

        return $patient_cases;
    }

    public function getCasesCountByTeams($patient_cases){

        $role_names = $this->role_name;

        // Count patient_cases grouped by group_id
        $teams_counts = PatientCase::join('users', 'patient_cases.created_by', '=', 'users.id')
            ->join('teams', 'users.team_id', '=', 'teams.id')
            ->whereNull('patient_cases.deleted_at')
            ->where('users.team_id','<>', 0)
            ->selectRaw('users.team_id, COUNT(patient_cases.id) as cases_count')
            ->groupBy('users.team_id')
            ->get();
        $patient_cases['cases_counts_by_teams'] = $teams_counts;
        return $patient_cases;

    }

    public function getCasesCountByTeamsMonthly($patient_cases, $count){

        $role_names = $this->role_name;

        // Count patient_cases grouped by team_id and month
        $teams_counts = PatientCase::join('users', 'patient_cases.created_by', '=', 'users.id')
            ->join('teams', 'users.team_id', '=', 'teams.id')
            ->selectRaw('users.team_id, patient_cases.created_at as month_date, YEAR(patient_cases.created_at) as year, COUNT(patient_cases.id) as case_count')
                ->whereNull('patient_cases.deleted_at')
                ->where('users.team_id','<>', 0)
            ->where('patient_cases.status', $count)
            ->groupBy('users.team_id', 'month_date', 'year')
            ->orderBy('year', 'asc')
            ->orderByRaw('MONTH(patient_cases.created_at)')
            ->get();

        $patient_cases['teams_cases_'.$count.'_counts_monthly'] = $teams_counts;
        return $patient_cases;

    }


    public function getCaseCountsByClientSubClients($patient_cases, $count=null){

        $role_names = $this->role_name;

        if(is_null($count)){

            // Count patient_cases created by client_id or sub_client_id, grouped by month, including user details
            $clients_monthly_cases_counts = PatientCase::join('users', function ($join) {
                    $join->on('patient_cases.client_id', '=', 'users.id')
                         ->orOn('patient_cases.sub_client_id', '=', 'users.id');
                })
                ->selectRaw('users.username, users.email, YEAR(patient_cases.created_at) as year, MONTHNAME(patient_cases.created_at) as month_name, COUNT(patient_cases.id) as cases_count')
                ->where(function ($query) {
                    $query->whereNotNull('patient_cases.client_id')
                          ->orWhereNotNull('patient_cases.sub_client_id');
                })
                ->groupBy('users.username', 'users.email', 'year', 'month_name')
                ->orderBy('year', 'asc')
                ->orderByRaw('MIN(MONTH(patient_cases.created_at))')
                ->get();

            $patient_cases['clients_cases_counts_monthly'] = $clients_monthly_cases_counts;

        }else{
            // Count patient_cases created by client_id or sub_client_id, grouped by month, including user details
            $clients_monthly_cases_counts = PatientCase::join('users', function ($join) {
                    $join->on('patient_cases.client_id', '=', 'users.id')
                         ->orOn('patient_cases.sub_client_id', '=', 'users.id');
                })
                ->selectRaw('users.username, users.email, YEAR(patient_cases.created_at) as year, MONTHNAME(patient_cases.created_at) as month_name, COUNT(patient_cases.id) as cases_count')
                ->where(function ($query) {
                    $query->whereNotNull('patient_cases.client_id')
                          ->orWhereNotNull('patient_cases.sub_client_id');
                })
                ->where('patient_cases.status', $count)
                ->groupBy('users.username', 'users.email', 'year', 'month_name')
                ->orderBy('year', 'asc')
                ->orderByRaw('MIN(MONTH(patient_cases.created_at))')
                ->get();

            $patient_cases['clients_cases_'.$count.'_counts_monthly'] = $clients_monthly_cases_counts;
        }
        return $patient_cases;
    
    }


    public function getCaseCountsByClientSubClientsDaily($patient_cases, $count=null){

        $role_names = $this->role_name;

        if(is_null($count)){

            // Get the start and end dates for the last month
            $startDate = Carbon::now()->startOfMonth()->subMonth()->startOfDay();
            $endDate = Carbon::now()->startOfMonth()->subDay()->endOfDay();

            // Count patient_cases created by client_id or sub_client_id, grouped by day, including user details for the last month
            $clients_daily_cases_counts = PatientCase::join('users', function ($join) {
                    $join->on('patient_cases.client_id', '=', 'users.id')
                         ->orOn('patient_cases.sub_client_id', '=', 'users.id');
                })
                ->selectRaw('users.username, users.email, DATE(patient_cases.created_at) as date, COUNT(patient_cases.id) as cases_count')
                ->whereBetween('patient_cases.created_at', [$startDate, $endDate])
                ->where(function ($query) {
                    $query->whereNotNull('patient_cases.client_id')
                          ->orWhereNotNull('patient_cases.sub_client_id');
                })
                ->groupBy('users.username', 'users.email', 'date')
                ->orderBy('date', 'asc')
                ->get();

            $patient_cases['clients_cases_counts_daily'] = $clients_daily_cases_counts;

        }else{
            // Get the start and end dates for the last month
            $startDate = Carbon::now()->startOfMonth()->subMonth()->startOfDay();
            $endDate = Carbon::now()->startOfMonth()->subDay()->endOfDay();

            // Count patient_cases created by client_id or sub_client_id, grouped by day, including user details for the last month
            $clients_daily_cases_counts = PatientCase::join('users', function ($join) {
                    $join->on('patient_cases.client_id', '=', 'users.id')
                         ->orOn('patient_cases.sub_client_id', '=', 'users.id');
                })
                ->selectRaw('users.username, users.email, DATE(patient_cases.created_at) as date, COUNT(patient_cases.id) as cases_count')
                ->whereBetween('patient_cases.created_at', [$startDate, $endDate])
                ->where('patient_cases.status', $count)
                ->where(function ($query) {
                    $query->whereNotNull('patient_cases.client_id')
                          ->orWhereNotNull('patient_cases.sub_client_id');
                })
                ->groupBy('users.username', 'users.email', 'date')
                ->orderBy('date', 'asc')
                ->get();

            $patient_cases['clients_cases_'.$count.'_counts_daily'] = $clients_daily_cases_counts;
        }
        return $patient_cases;

    }


    public function getWeeklyCasesCountSubclient($patient_cases, $count = null){

        $role_names = $this->role_name;


        if(is_null($count)){
            $patient_case = PatientCase::where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
                                                        ->when($role_names, function($q) use($role_names){
                                                            if($role_names == 'post_processing'){
                                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                                }else{
                                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                                }
                                                            }
                                                        })->orderBy('created_at', 'asc')->get();
            $grouped_patient_cases = $patient_case->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('l'); // Group by day name
            });
            $daily_patient_cases_counts = [];
            foreach ($grouped_patient_cases as $day => $products) {
                $daily_patient_cases_counts[] = [
                    'day' => $day,
                    'count' => $products->count()
                ];
            }
            $patient_cases['subclient_cases_count_weekly'] = $daily_patient_cases_counts;

        }else{

            $patient_case = PatientCase::where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
                                                            ->where('status', $count)
                                                            ->when($role_names, function($q) use($role_names){
                                                                if($role_names == 'post_processing'){
                                                                    $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                                                }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                                    if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                                        $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                                        //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                                    }else{
                                                                        $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                                    }
                                                                }
                                                            })->orderBy('created_at', 'asc')->get();
            
            $grouped_patient_cases = $patient_case->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('l'); // Group by day name
            });
            $daily_patient_cases_counts = [];
            foreach ($grouped_patient_cases as $day => $products) {
                $daily_patient_cases_counts[] = [
                    'day' => $day,
                    'count' => $products->count()
                ];
            }
            $patient_cases['subclient_cases_'.$count.'_count_weekly'] = $daily_patient_cases_counts;
        }
        return $patient_cases;

    }


    public function getMonthlyCasesCountSubclients($patient_cases, $count = null){

        $role_names = $this->role_name;


        if(is_null($count)){
            $patient_case = PatientCase::where('created_at', '>=', Carbon::now()->subYear())
                                                        ->when($role_names, function($q) use($role_names){
                                                            if($role_names == 'post_processing'){
                                                                $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                                            }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                                if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                                    //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                                }else{
                                                                    $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                                }
                                                            }
                                                        })->orderBy('created_at', 'asc')->get();
            $grouped_patient_cases = $patient_case->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('F'); // Group by month name
            });
            $daily_patient_cases_counts = [];
            foreach ($grouped_patient_cases as $month => $products) {
                $daily_patient_cases_counts[] = [
                    'month' => $month,
                    'count' => $products->count()
                ];
            }
            $patient_cases['subclient_cases_count_monthly'] = $daily_patient_cases_counts;

        }else{

            $patient_case = PatientCase::where('created_at', '>=', Carbon::now()->subYear())
                                                            ->where('status', $count)
                                                            ->when($role_names, function($q) use($role_names){
                                                                if($role_names == 'post_processing'){
                                                                    $q->whereIn('status', [9, 10, 13, 14, 15, 17]);
                                                                }else if($role_names != 'super_admin' && $role_names != 'case_submission'){
                                                                    if(auth()->user()->email == 'drshakeelahmed_vk@yahoo.com' && !empty(auth()->user()->client_id)){
                                                                        $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)
                                                            ->orWhere('assign_to', auth()->user()->id)
                                                            ->orWhere('client_id', auth()->user()->id)
                                                            ->orWhere('created_by', auth()->user()->client_id);
                                                    });
                                                                        //$q->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id)->orWhere('created_by', auth()->user()->client_id);
                                                                    }else{
                                                                        $q->where(function ($query) {
                                                        $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->orWhere('client_id', auth()->user()->id);
                                                    });
                                                                    }
                                                                }
                                                            })->orderBy('created_at', 'asc')->get();
            
            $grouped_patient_cases = $patient_case->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('F'); // Group by month name
            });
            $daily_patient_cases_counts = [];
            foreach ($grouped_patient_cases as $month => $products) {
                $daily_patient_cases_counts[] = [
                    'month' => $month,
                    'count' => $products->count()
                ];
            }
            $patient_cases['subclient_cases_'.$count.'_count_monthly'] = $daily_patient_cases_counts;
        }
        return $patient_cases;

    }

    public function getAvgAllCases($patient_cases){

        $role_names = $this->role_name;


        $products = DB::table('patient_cases')
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get();

        // Calculate total count for all statuses
        $totalCount = $products->sum('count');
        
        
                

        // Get the count for status 8
        //$status8Count = $products->firstWhere('status', 8)->count ?? 1; // Default to 1 to avoid division by zero

        // Calculate the average count and divide by the count of status 8
        //if(request()->debug && request()->debug == 1){
            
            $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
            $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
            
            $status8Count = DB::table('patient_cases')
                ->whereIn('status', [8, 13, 14, 17])
                ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
                ->count();
                
            // $status8Count = DB::table('patient_cases')
            //     // ->select('status', DB::raw('count(*) as count'))
            //     ->whereIn('status', [8, 13, 14, 17])
            //     // ->groupBy('status')
            //     ->get()->count();
                
                $averageCount = (($status8Count / $totalCount) * 100);

          //  dd($totalCount, $status8Count, $status8Counts, $averageCount);
        //}
        
        //$averageCount = $totalCount / $status8Count;
        
        // $averageCount = $totalCount / $status8Count;
        $patient_cases['over_all_ratio_count'] = $averageCount;
        return $patient_cases;

    }

    public function getAvgAllCountsByCreatedCase($patient_cases){

        $role_names = $this->role_name;
             
        $products = DB::table('patient_cases')
                ->join('users', 'patient_cases.created_by', '=', 'users.id')
                ->select('patient_cases.created_by', 'patient_cases.status', DB::raw('count(*) as count'))
                ->groupBy('created_by', 'status')
                ->get()
                ->groupBy('created_by');

        // Initialize an array to hold the average counts per user
        $averageCounts = [];

        foreach ($products as $userId => $statuses) {
            // Calculate total count for all statuses for this user
            $totalCount = $statuses->sum('count');

            // Get the count for status 8 for this user
            $status8Count = $statuses->firstWhere('status', 8)->count ?? 1; // Default to 1 to avoid division by zero

            // Calculate the average count and divide by the count of status 8
            $averageCount = $totalCount / $status8Count;

            $users = User::select('id', 'first_name', 'last_name', 'username', 'team_id')->where('id',$userId)->first();
            if(!empty($users)){
                $users->makeHidden('permissions');
                $users->makeHidden('roles');

                $users['average_count'] = $averageCount;
                $averageCounts[] = $users;
            }
            // Store the result in the array
            // $averageCounts[$userId] = $averageCount;
        }

        // Return the array of average counts per user
        $patient_cases['created_by_cases_ratio_count'] = $averageCounts;

        return $patient_cases;
    }
    
    public function newAndModificationCases($patient_cases){
        $data = [];
        $patient_cases['new_cases_count'] = PatientCase::when($this->role_name, function($q){
                                    if($this->role_name == 'post_processing'){
                                        $q->where('verified_by_client', 1);
                                    }else if($this->role_name != 'super_admin' && $this->role_name != 'case_submission'){
                                        $q->where(function($query){
                                            $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->Orwhere('client_id', auth()->user()->id);
                                        });
                                    }
                                    })->where('status', 1)->count();
        $patient_cases['modification_cases_count'] = PatientCase::when($this->role_name, function($q){
                                        if($this->role_name == 'post_processing'){
                                            $q->where('verified_by_client', 1);
                                        }else if($this->role_name != 'super_admin' && $this->role_name != 'case_submission'){
                                            $q->where(function($query){
                                                $query->where('created_by', auth()->user()->id)->orWhere('assign_to', auth()->user()->id)->Orwhere('client_id', auth()->user()->id);
                                            });
                                        }
                                        })->whereIn('status', [8,13,14,17])->count();
    }
    
    
    public function getRatioChartData($patient_cases, $request)
    {

        $start_date = date('Y-m-d');  

        $date_d = strtotime($start_date);
        $date_d = strtotime("-1 day", $date_d);
        $end_date_d = date('Y-m-d', $date_d);

        // Retrieve filters from the user
        $startDate = $request->input('start_date', $start_date);
        $endDate = $request->input('end_date', $end_date_d);
        $status_filter = $request->input('statuses', range(1, 20)); // Default to all statuses

        // Build the query
        $query = PatientCase::query();

        // Filter by date range if provided
        if ($startDate && $endDate) {
            $query->whereBetween('start_date_time', [$startDate, $endDate]);
        }

        // Filter by status if provided
        $query->whereIn('status', $status_filter);

        // Group by status and count cases
        $data = $query
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->get();

        // Fill missing statuses with 0 count
        $status_data = collect($status_filter)->map(function ($status) use ($data) {
            return [
                'status' => $status,
                'total' => $data->firstWhere('status', $status)->total ?? 0,
            ];
        });

        $patient_cases['ratio_chart'] = $status_data;
        // return $status_data;

    }

}

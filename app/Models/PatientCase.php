<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\SoftDeletes;


class PatientCase extends Model
{
    use HasFactory, Uuids, SoftDeletes;


    protected $table = 'patient_cases';
    protected $primaryKey = 'id';

    protected $fillable = [
                'guid',
                'name',
                'email',
                'extraction',
                'attachments',
                'case_id',
                'age',
                'gender',
                'ipr',
                'chief_complaint',
                'treatment_plan',
                'stl_upper_file',
                'stl_lower_file',
                'stl_byte_scan_file',
                'created_by',
                'status',
                'assign_to',
                'created_by_admin',
                'planner_id',
                'qa_id',
                'is_priority',
                'post_processing_id',
                'stl_file_by_post_processing',
                'expected_time',
                'tooth_label_format',
                'case_version',
                'setup_type',
                'scan_version',
                'container_file_by_post_processing',
                'sub_client_id',
                'client_id',
                'verified_by_client',
                'phone',
                'start_date_time',
                'start_date_time_timestamp_string',
                'stl_file_by_post_processing_we_transfer_link',
                'patient_location',
                'case_type',
                'arch'
        ];



    public function images(){
        return $this->hasMany(Image::class, 'p_case_id');
    }
    public function xrays(){
        return $this->hasMany(Xray::class, 'p_case_id');
    }
    public function created_user(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    
    public function case_plans(){
        return $this->hasMany(CasePlan::class, 'p_case_id', 'id');
    }
    public function users(){
        return $this->belongsTo(User::class, 'assign_to', 'id');
    }

    public function case_status_users(){
        return $this->hasMany(CasesStatusUser::class, 'p_case_id', 'id')->orderBy('id', 'DESC');
    }

    public function planner(){
        return $this->belongsTo(User::class, 'planner_id', 'id');
    }
    public function qa(){
        return $this->belongsTo(User::class, 'qa_id', 'id');
    }
    public function post_processing(){
        return $this->belongsTo(User::class, 'post_processing_id', 'id');
    }

}


// 0 => 'pending', 1 => 'treatment_planning', 2 => 'quality_checking', 3 => 'treatment_planning_upload', 4 => 'pending_step_files', 5 => 'step_files_uploaded'

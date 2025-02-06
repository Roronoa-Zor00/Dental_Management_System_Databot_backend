<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject; 
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, SoftDeletes, Uuids, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'guid',
        'username',
        'first_name',
        'last_name',
        'email',
        'password',
        'mobile_number',
        'phone',
        'profile_pic',
        'email_verified_at',
        'status',
        'company_id',
        'added_by',
        'is_account_owner',
        'is_active',
        'last_login',
        'client_id',
        'sub_client_id',
        'clinic_name',
        'team_id',
        'country_name',
        'monthly_total_cases',
        'monthly_target_cases',
        'extra_cases',
        'full_name',
        'is_8_hours_enabled',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $appends = ['role_name', 'notifications_admin_count', 'notifications_case_submission_count', 'permissions'];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array{
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // protected $with = ['teams'];

    protected $withCount = ['created_user_cases', 'assign_to_casses', 'planner_casses', 'qa_cases', 'post_processing_cases', 'my_cases', 'sub_client_cases', 'in_process_cases', 'pending_approval_cases', 'step_filea_ready_cases', 'need_more_info_cases', 'need_mofication_cases', 'completed_cases', 'notifications'];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }
 
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }

    public function getRoleNameAttribute() {
        $role = $this->roles->pluck('name');
        // dd($this->roles()->getPermissions());
        if(!empty($role) && isset($role[0]))
            return $role[0];
        return 0; 

    }

    public function getPermissionsAttribute() {
        return $this->roles->map(function ($role) {
            return $role->permissions;
        })->collapse()->pluck('name')->unique();
    }

    public function teams(){
        return $this->belongsTo(Team::class, 'team_id', 'id');
    }

    public function created_user_cases(){
        return $this->belongsTo(PatientCase::class, 'id', 'created_by');
    }
    public function assign_to_casses(){
        return $this->belongsTo(PatientCase::class, 'id', 'assign_to');
    }
    public function planner_casses(){
        return $this->belongsTo(PatientCase::class, 'id', 'planner_id');
    }
    public function qa_cases(){
        return $this->belongsTo(PatientCase::class, 'id', 'qa_id');
    }
    public function post_processing_cases(){
        return $this->belongsTo(PatientCase::class, 'id', 'post_processing_id');
    }

    public function my_cases(){
        return $this->hasMany(PatientCase::class, 'created_by', 'id');
    }
    public function sub_client_cases(){
        return $this->hasMany(PatientCase::class, 'client_id', 'id');
    }

    public function in_process_cases(){
        return $this->hasMany(PatientCase::class, 'created_by', 'id')->whereIn('status', [2, 3]);
    }
    public function pending_approval_cases(){
        return $this->hasMany(PatientCase::class, 'created_by', 'id')->whereIn('status', [7]);
    }
    public function step_filea_ready_cases(){
        return $this->hasMany(PatientCase::class, 'created_by', 'id')->whereIn('status', [11, 12]);
    }
    public function need_more_info_cases(){
        return $this->hasMany(PatientCase::class, 'created_by', 'id')->whereIn('status', [4]);
    }
    public function need_mofication_cases(){
        return $this->hasMany(PatientCase::class, 'created_by', 'id')->whereIn('status', [8]);
    }
    public function completed_cases(){
        return $this->hasMany(PatientCase::class, 'created_by', 'id')->whereIn('status', [15]);
    }
    
    public function notifications(){
        return $this->belongsTo(Notification::class, 'id', 'user_id')->where('is_read', 0);
    }
    
    
    public function getNotificationsAdminCountAttribute(){
        
        return Notification::where('is_admin_id', 0)->where('is_read_admin', 0)->count();
    }
    
    
    public function getNotificationsCaseSubmissionCountAttribute(){
        return Notification::where('is_admin_id', 0)->where('is_read_case_submission', 0)->count();
    }
    
    
    public function user_cases_history(){
        return $this->belongsTo(CasesStatusUser::class, 'id', 'user_id');
    }
    
}



// ALTER TABLE `users` ADD `team_id` INT(11) NOT NULL DEFAULT '0' AFTER `remember_token`;

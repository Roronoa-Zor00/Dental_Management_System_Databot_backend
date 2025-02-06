<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class CasesStatusUser extends Model
{
    use HasFactory, Uuids;

    protected $table = 'cases_status_users';
    protected $primaryKey = 'id';

    protected $fillable = [
                    'guid',
                    'p_case_id',
                    'user_id',
                    'case_status',
                    'status',
                    'work_start_datetime',
                    'work_end_datetime'
        ];

    // protected $with = ['user_detail'];

    public function user_detail(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    public function cases_status_users_comments(){
        return $this->hasMany(CasesStatusUsersComment::class, 'pcsu_id', 'id')->orderBy('id', 'DESC');
    }
    
    public function case_detail(){
        return $this->belongsTo(PatientCase::class, 'p_case_id', 'id');
    }
    
}

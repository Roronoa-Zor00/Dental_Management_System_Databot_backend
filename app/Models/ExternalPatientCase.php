<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;


class ExternalPatientCase extends Model
{
    use HasFactory, Uuids;

    protected $table = 'external_patient_cases';
    protected $primaryKey = 'id';

    protected $fillable = [
        'guid',
        'case_id',
        'name',
        'created_by',
        'status',
        'client_id',
        'case_datetime',
        'software_id'
    ];

    public function created_user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function software()
    {
        return $this->belongsTo(Software::class, 'software_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id', 'id');
    }
}




// 0 => 'pending', 1 => 'treatment_planning', 2 => 'quality_checking', 3 => 'treatment_planning_upload', 4 => 'pending_step_files', 5 => 'step_files_uploaded'

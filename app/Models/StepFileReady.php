<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class StepFileReady extends Model
{
    use HasFactory, Uuids;

    protected $table = 'step_file_readies';
    protected $primaryKey = 'id';

    protected $fillable = [
            'guid',
            'p_case_id',
            'error',
            'created_by',
            'status',
        ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Xray extends Model
{
    use HasFactory, Uuids;

    protected $table = 'xrays';
    protected $primaryKey = 'id';

    protected $fillable = [
                'guid',
                'file_name',
                'type',
                'order',
                'status',  
                'p_case_id'   
        ];
}

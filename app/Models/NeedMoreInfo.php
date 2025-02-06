<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class NeedMoreInfo extends Model
{
    use HasFactory, Uuids;

    protected $table = 'need_more_infos';
    protected $primaryKey = 'id';

    protected $fillable = [
                'guid',
                'p_case_id',
                'notes',
                'created_by',
                'status'
        ];
}

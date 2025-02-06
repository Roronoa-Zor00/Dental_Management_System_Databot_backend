<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class ModificationReceived extends Model
{
    use HasFactory, Uuids;

    protected $table = 'modification_receiveds';
    protected $primaryKey = 'id';

    protected $fillable = [
            'guid',
            'case_plan_id',
            'simulation_link_url',
            'ipr_chart',
            'comments',
            'created_by',
            'status',
        ];

}




// ALTER TABLE `modification_receiveds` CHANGE `p_case_id` `case_plan_id` BIGINT(20) UNSIGNED NOT NULL;

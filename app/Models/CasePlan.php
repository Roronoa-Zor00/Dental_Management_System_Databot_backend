<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class CasePlan extends Model
{
        use HasFactory, Uuids;

        protected $table = 'case_plans';
        protected $primaryKey = 'id';

        protected $fillable = [
                'guid',
                'p_case_id',
                'ipr_chart',
                'simulation_link_url',
                'text_notes',
                'status',
                'created_by'

        ];
}

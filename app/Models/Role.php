<?php

namespace App\Models;

use App\Traits\Uuids;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role AS BaseRole;
use Illuminate\Database\Eloquent\SoftDeletes;


class Role extends BaseRole
{
    use HasFactory, SoftDeletes, Uuids;

    protected $dates = ['deleted_at'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s.v');
    }
}



// Super admin
// client
// sub-client
// case-submission-department
// quality check
// post processing
// treatment planner
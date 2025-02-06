<?php

namespace App\Models;

use App\Traits\Uuids;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Guard;
use Spatie\Permission\Models\Permission AS BasePermission;


class Permission extends BasePermission
{
    use HasFactory, SoftDeletes, Uuids;


    protected $dates = ['deleted_at'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s.v');
    }

    public static function findByGuardName($guardName = null)
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $permissions = [];
        foreach (static::getPermissions() as $permission):

            if ($permission->guard_name === $guardName) {

                 $permissions[] = $permission->name;

            }

        endforeach;

        return $permissions;
    }
}

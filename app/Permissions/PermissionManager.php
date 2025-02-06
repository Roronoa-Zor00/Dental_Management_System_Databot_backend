<?php namespace App\Permissions;


class PermissionManager
{
    /**
     * @var Module
     */
    public function filter_permissions($permissions, $filter_checked_permissions = true)
    {
        if (!$permissions || !is_array($permissions)) {
            return [];
        }
        $checked_permissions = [];
        $unchecked_permissions = [];
        foreach ($permissions as $key => $permission):
            if ($permissions[$key] == 'true') {
                $checked_permissions[] = $key;
            } else {
                $unchecked_permissions[] = $key;
            }
        endforeach;
        if ($filter_checked_permissions)
            return $checked_permissions;
        else
            return $unchecked_permissions;

    }
}

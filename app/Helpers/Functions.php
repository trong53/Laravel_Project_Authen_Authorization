<?php
use App\Models\Group;

// get array of all groups's ID 
function groupsID() : array 
{
    $groupsID = Group::select('id')->get();
    $groupsID_array = [];

    if ($groupsID->count() > 0) {
        foreach ($groupsID as $item) {
            $groupsID_array[] = $item->id;
        }
    }
    return $groupsID_array;
}


// check if permission is in permissions array of groups table - GroupController - permission.blade.php
function isPermission(array $permissionsArray = [], string $module = '', string $role = 'read') : bool
{
    if (!empty($permissionsArray[$module])) {
        return in_array($role, $permissionsArray[$module]);
    }
    return false;
}

<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class GroupPolicy
{
    use HandlesAuthorization;

    private $permissionsArray = [];

    public function __construct()
    {
        $permissions = Auth::user()->group->permissions;
        if (!empty($permissions)) {
            $this->permissionsArray = json_decode($permissions, true);
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return isPermission($this->permissionsArray, 'groups', 'read');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Group $group)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return isPermission($this->permissionsArray, 'groups', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Group $group)
    {
        if ($group->user_id == $user->id) {
            return isPermission($this->permissionsArray, 'groups', 'update');
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Group $group)
    {
        if ($group->user_id == $user->id) {
            return isPermission($this->permissionsArray, 'groups', 'delete');
        }
        return false;
    }

        /**
     * Determine whether the user can set up the permissions of the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function permission(User $user, Group $group)
    {
        if ($group->user_id == $user->id) {
            return isPermission($this->permissionsArray, 'groups', 'permission');
        }
        return false;
    }
}

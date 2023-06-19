<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

use Illuminate\Support\Facades\Auth;

class PostPolicy
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
        return isPermission($this->permissionsArray, 'posts', 'read');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Post $post)
    {
        if ($post->user_id == $user->id) {
            return isPermission($this->permissionsArray, 'posts', 'read');
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create()
    {
        return isPermission($this->permissionsArray, 'posts', 'create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Post $post)
    {
        if ($post->user_id == $user->id) {
            return isPermission($this->permissionsArray, 'posts', 'update');
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Post $post)
    {
        if ($post->user_id == $user->id) {
            return isPermission($this->permissionsArray, 'posts', 'delete');
        }
        return false;
    }
        /**
     * Determine whether the user can view the trash model.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function trash()
    {
        return isPermission($this->permissionsArray, 'posts', 'delete');
    }

            /**
     * Determine whether the user can view the posts of trashed Users.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function postsOfTrashedUsers(User $user)
    {
        if ($user->group->name === 'Administration') {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Post $post)
    {
        if ($post->user_id == $user->id) {
            return isPermission($this->permissionsArray, 'posts', 'delete');
        }
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Post $post)
    {
        if ($post->user_id == $user->id) {
            return isPermission($this->permissionsArray, 'posts', 'delete');
        }
        return false;
    }
    // public function before(User $user) {
    //     if ($user->group->name === 'Administration') {
    //         return true;
    //     }
    // }
}

<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->isSuperAdminAccount()
            || $user->isManagerAccount();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $model)
    {
        if ($user->id == $model->id) {
            return true;
        }

        if ($user->isSuperAdminAccount()) {
            return true;
        }

        // check whether the user is an employee of the current manager's branch
        if ($user->isManagerAccount()) {
            return isset($user->ManageBranch->id)
                && isset($model->WorkForBranch->id)
                && (($model->WorkForBranch->id ?? null)  == ($user->ManageBranch->id ?? null));
        }

        // check whether the model is either a manager or a counter workin under the same branch.
        if ($user->isCounterAccount()) {
            return (isset($user->WorkForBranch->id) && isset($model->WorkForBranch->id) && ($user->WorkForBranch->id == $model->WorkForBranch->id))

                || (isset($user->WorkForBranch->id) && isset($model->WorkForBranch->id) && ($user->WorkForBranch->id == $model->ManageBranch->id));
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->isSuperAdminAccount()
            || $user->isManagerAccount();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, User $model)
    {
        if ($user->isSuperAdminAccount() || $model->id == $user->id) {
            return true;
        }

        // check whether the user is an employee of the current manager's branch
        if ($user->isManagerAccount()) {
            return isset($user->ManageBranch->id)
                && isset($model->WorkForBranch->id)
                && (($model->WorkForBranch->id ?? null)  == ($user->ManageBranch->id ?? null));
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $model)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, User $model)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, User $model)
    {
        return false;
    }
}

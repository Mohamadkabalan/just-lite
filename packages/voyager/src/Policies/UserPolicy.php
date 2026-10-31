<?php

namespace TCG\Voyager\Policies;

use TCG\Voyager\Contracts\User;

class UserPolicy extends BasePolicy
{
    /**
     * Determine if the given model can be viewed by the user.
     *
     * @param \TCG\Voyager\Contracts\User $user
     * @param  $model
     *
     * @return bool
     */
    public function read(User $user, $model)
    {
        // Does this record belong to the current user?
        $current = $user->id === $model->id;

        return $current || $this->checkPermission($user, $model, 'read');
    }

    /**
     * Determine if the given model can be edited by the user.
     *
     * @param \TCG\Voyager\Contracts\User $user
     * @param  $model
     *
     * @return bool
     */
    public function edit(User $user, $model)
    {
        // Does this record belong to the current user?
        $current = $user->id === $model->id;

        // PATCH 1.8.0.5: changing another user (their password, email or role) is
        // as powerful as changing roles, so it also needs edit_roles.
        return $current || ($this->checkPermission($user, $model, 'edit') && $this->canManageRoles($user));
    }

    /**
     * Determine if the given user can add users.
     *
     * @param \TCG\Voyager\Contracts\User $user
     * @param  $model
     *
     * @return bool
     */
    public function add(User $user, $model)
    {
        // PATCH 1.8.0.5: a new user gets a role, so adding one needs edit_roles too.
        return $this->checkPermission($user, $model, 'add') && $this->canManageRoles($user);
    }

    /**
     * Determine if the given model can be deleted by the user.
     *
     * @param \TCG\Voyager\Contracts\User $user
     * @param  $model
     *
     * @return bool
     */
    public function delete(User $user, $model)
    {
        // PATCH 1.8.0.5: deleting other users needs edit_roles too.
        return parent::delete($user, $model) && $this->canManageRoles($user);
    }

    /**
     * Determine if the given user can change a user a role.
     *
     * @param \TCG\Voyager\Contracts\User $user
     * @param  $model
     *
     * @return bool
     */
    public function editRoles(User $user, $model)
    {
        // Does this record belong to another user?
        $another = $user->id != $model->id;

        return $another && $user->hasPermission('edit_users') && $this->canManageRoles($user);
    }

    /**
     * PATCH 1.8.0.5: only users who may edit roles may manage other users.
     * Upstream let edit_users alone change anyone's role and password, so any
     * role with edit_users could promote itself to admin.
     *
     * @param \TCG\Voyager\Contracts\User $user
     *
     * @return bool
     */
    protected function canManageRoles(User $user)
    {
        return $user->hasPermission('edit_roles');
    }
}

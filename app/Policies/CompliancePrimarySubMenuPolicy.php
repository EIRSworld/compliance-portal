<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\CompliancePrimarySubMenu;
use App\Models\User;

class CompliancePrimarySubMenuPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any CompliancePrimarySubMenu');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CompliancePrimarySubMenu $complianceprimarysubmenu): bool
    {
        return $user->checkPermissionTo('view CompliancePrimarySubMenu');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create CompliancePrimarySubMenu');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CompliancePrimarySubMenu $complianceprimarysubmenu): bool
    {
        return $user->checkPermissionTo('update CompliancePrimarySubMenu');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CompliancePrimarySubMenu $complianceprimarysubmenu): bool
    {
        return $user->checkPermissionTo('delete CompliancePrimarySubMenu');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CompliancePrimarySubMenu $complianceprimarysubmenu): bool
    {
        return $user->checkPermissionTo('{{ restorePermission }}');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CompliancePrimarySubMenu $complianceprimarysubmenu): bool
    {
        return $user->checkPermissionTo('{{ forceDeletePermission }}');
    }
}

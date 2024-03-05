<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\ComplianceMenu;
use App\Models\User;

class ComplianceMenuPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any ComplianceMenu');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ComplianceMenu $compliancemenu): bool
    {
        return $user->checkPermissionTo('view ComplianceMenu');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create ComplianceMenu');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ComplianceMenu $compliancemenu): bool
    {
        return $user->checkPermissionTo('update ComplianceMenu');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ComplianceMenu $compliancemenu): bool
    {
        return $user->checkPermissionTo('delete ComplianceMenu');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ComplianceMenu $compliancemenu): bool
    {
        return $user->checkPermissionTo('{{ restorePermission }}');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ComplianceMenu $compliancemenu): bool
    {
        return $user->checkPermissionTo('{{ forceDeletePermission }}');
    }
}

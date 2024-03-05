<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\UploadDocument;
use App\Models\User;

class UploadDocumentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any UploadDocument');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UploadDocument $uploaddocument): bool
    {
        return $user->checkPermissionTo('view UploadDocument');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create UploadDocument');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UploadDocument $uploaddocument): bool
    {
        return $user->checkPermissionTo('update UploadDocument');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UploadDocument $uploaddocument): bool
    {
        return $user->checkPermissionTo('delete UploadDocument');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UploadDocument $uploaddocument): bool
    {
        return $user->checkPermissionTo('{{ restorePermission }}');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UploadDocument $uploaddocument): bool
    {
        return $user->checkPermissionTo('{{ forceDeletePermission }}');
    }
}

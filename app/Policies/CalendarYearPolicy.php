<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\CalendarYear;
use App\Models\User;

class CalendarYearPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any CalendarYear');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CalendarYear $calendaryear): bool
    {
        return $user->checkPermissionTo('view CalendarYear');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create CalendarYear');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CalendarYear $calendaryear): bool
    {
        return $user->checkPermissionTo('update CalendarYear');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CalendarYear $calendaryear): bool
    {
        return $user->checkPermissionTo('delete CalendarYear');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CalendarYear $calendaryear): bool
    {
        return $user->checkPermissionTo('{{ restorePermission }}');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CalendarYear $calendaryear): bool
    {
        return $user->checkPermissionTo('{{ forceDeletePermission }}');
    }
}

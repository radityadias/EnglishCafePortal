<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\WorkingTime;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkingTimePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:WorkingTime');
    }

    public function view(AuthUser $authUser, WorkingTime $workingTime): bool
    {
        return $authUser->can('View:WorkingTime');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:WorkingTime');
    }

    public function update(AuthUser $authUser, WorkingTime $workingTime): bool
    {
        return $authUser->can('Update:WorkingTime');
    }

    public function delete(AuthUser $authUser, WorkingTime $workingTime): bool
    {
        return $authUser->can('Delete:WorkingTime');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:WorkingTime');
    }

    public function restore(AuthUser $authUser, WorkingTime $workingTime): bool
    {
        return $authUser->can('Restore:WorkingTime');
    }

    public function forceDelete(AuthUser $authUser, WorkingTime $workingTime): bool
    {
        return $authUser->can('ForceDelete:WorkingTime');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:WorkingTime');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:WorkingTime');
    }

    public function replicate(AuthUser $authUser, WorkingTime $workingTime): bool
    {
        return $authUser->can('Replicate:WorkingTime');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:WorkingTime');
    }

}
<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\AttendanceRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttendanceRequestPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:AttendanceRequest');
    }

    public function view(AuthUser $authUser, AttendanceRequest $attendanceRequest): bool
    {
        return $authUser->can('View:AttendanceRequest');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:AttendanceRequest');
    }

    public function update(AuthUser $authUser, AttendanceRequest $attendanceRequest): bool
    {
        return $authUser->can('Update:AttendanceRequest');
    }

    public function delete(AuthUser $authUser, AttendanceRequest $attendanceRequest): bool
    {
        return $authUser->can('Delete:AttendanceRequest');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:AttendanceRequest');
    }

    public function restore(AuthUser $authUser, AttendanceRequest $attendanceRequest): bool
    {
        return $authUser->can('Restore:AttendanceRequest');
    }

    public function forceDelete(AuthUser $authUser, AttendanceRequest $attendanceRequest): bool
    {
        return $authUser->can('ForceDelete:AttendanceRequest');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:AttendanceRequest');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:AttendanceRequest');
    }

    public function replicate(AuthUser $authUser, AttendanceRequest $attendanceRequest): bool
    {
        return $authUser->can('Replicate:AttendanceRequest');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:AttendanceRequest');
    }

}
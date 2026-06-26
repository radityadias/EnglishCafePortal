<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Instance;
use Illuminate\Auth\Access\HandlesAuthorization;

class InstancePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Instance');
    }

    public function view(AuthUser $authUser, Instance $instance): bool
    {
        return $authUser->can('View:Instance');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Instance');
    }

    public function update(AuthUser $authUser, Instance $instance): bool
    {
        return $authUser->can('Update:Instance');
    }

    public function delete(AuthUser $authUser, Instance $instance): bool
    {
        return $authUser->can('Delete:Instance');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Instance');
    }

    public function restore(AuthUser $authUser, Instance $instance): bool
    {
        return $authUser->can('Restore:Instance');
    }

    public function forceDelete(AuthUser $authUser, Instance $instance): bool
    {
        return $authUser->can('ForceDelete:Instance');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Instance');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Instance');
    }

    public function replicate(AuthUser $authUser, Instance $instance): bool
    {
        return $authUser->can('Replicate:Instance');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Instance');
    }

}
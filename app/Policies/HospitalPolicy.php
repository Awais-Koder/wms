<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Hospital;
use Illuminate\Auth\Access\HandlesAuthorization;

class HospitalPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Hospital');
    }

    public function view(AuthUser $authUser, Hospital $hospital): bool
    {
        return $authUser->can('View:Hospital');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Hospital');
    }

    public function update(AuthUser $authUser, Hospital $hospital): bool
    {
        return $authUser->can('Update:Hospital');
    }

    public function delete(AuthUser $authUser, Hospital $hospital): bool
    {
        return $authUser->can('Delete:Hospital');
    }

    public function restore(AuthUser $authUser, Hospital $hospital): bool
    {
        return $authUser->can('Restore:Hospital');
    }

    public function forceDelete(AuthUser $authUser, Hospital $hospital): bool
    {
        return $authUser->can('ForceDelete:Hospital');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Hospital');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Hospital');
    }

    public function replicate(AuthUser $authUser, Hospital $hospital): bool
    {
        return $authUser->can('Replicate:Hospital');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Hospital');
    }

}
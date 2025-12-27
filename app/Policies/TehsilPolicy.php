<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Tehsil;
use Illuminate\Auth\Access\HandlesAuthorization;

class TehsilPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Tehsil');
    }

    public function view(AuthUser $authUser, Tehsil $tehsil): bool
    {
        return $authUser->can('View:Tehsil');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Tehsil');
    }

    public function update(AuthUser $authUser, Tehsil $tehsil): bool
    {
        return $authUser->can('Update:Tehsil');
    }

    public function delete(AuthUser $authUser, Tehsil $tehsil): bool
    {
        return $authUser->can('Delete:Tehsil');
    }

    public function restore(AuthUser $authUser, Tehsil $tehsil): bool
    {
        return $authUser->can('Restore:Tehsil');
    }

    public function forceDelete(AuthUser $authUser, Tehsil $tehsil): bool
    {
        return $authUser->can('ForceDelete:Tehsil');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Tehsil');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Tehsil');
    }

    public function replicate(AuthUser $authUser, Tehsil $tehsil): bool
    {
        return $authUser->can('Replicate:Tehsil');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Tehsil');
    }

}
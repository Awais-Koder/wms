<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Waste;
use Illuminate\Auth\Access\HandlesAuthorization;

class WastePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Waste');
    }

    public function view(AuthUser $authUser, Waste $waste): bool
    {
        return $authUser->can('View:Waste');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Waste');
    }

    public function update(AuthUser $authUser, Waste $waste): bool
    {
        return $authUser->can('Update:Waste');
    }

    public function delete(AuthUser $authUser, Waste $waste): bool
    {
        return $authUser->can('Delete:Waste');
    }

    public function restore(AuthUser $authUser, Waste $waste): bool
    {
        return $authUser->can('Restore:Waste');
    }

    public function forceDelete(AuthUser $authUser, Waste $waste): bool
    {
        return $authUser->can('ForceDelete:Waste');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Waste');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Waste');
    }

    public function replicate(AuthUser $authUser, Waste $waste): bool
    {
        return $authUser->can('Replicate:Waste');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Waste');
    }

}
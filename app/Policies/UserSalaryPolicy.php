<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\UserSalary;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserSalaryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:UserSalary');
    }

    public function view(AuthUser $authUser, UserSalary $userSalary): bool
    {
        return $authUser->can('View:UserSalary');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:UserSalary');
    }

    public function update(AuthUser $authUser, UserSalary $userSalary): bool
    {
        return $authUser->can('Update:UserSalary');
    }

    public function delete(AuthUser $authUser, UserSalary $userSalary): bool
    {
        return $authUser->can('Delete:UserSalary');
    }

    public function restore(AuthUser $authUser, UserSalary $userSalary): bool
    {
        return $authUser->can('Restore:UserSalary');
    }

    public function forceDelete(AuthUser $authUser, UserSalary $userSalary): bool
    {
        return $authUser->can('ForceDelete:UserSalary');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:UserSalary');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:UserSalary');
    }

    public function replicate(AuthUser $authUser, UserSalary $userSalary): bool
    {
        return $authUser->can('Replicate:UserSalary');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:UserSalary');
    }

}
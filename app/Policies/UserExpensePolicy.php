<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\UserExpense;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserExpensePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:UserExpense');
    }

    public function view(AuthUser $authUser, UserExpense $userExpense): bool
    {
        return $authUser->can('View:UserExpense');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:UserExpense');
    }

    public function update(AuthUser $authUser, UserExpense $userExpense): bool
    {
        return $authUser->can('Update:UserExpense');
    }

    public function delete(AuthUser $authUser, UserExpense $userExpense): bool
    {
        return $authUser->can('Delete:UserExpense');
    }

    public function restore(AuthUser $authUser, UserExpense $userExpense): bool
    {
        return $authUser->can('Restore:UserExpense');
    }

    public function forceDelete(AuthUser $authUser, UserExpense $userExpense): bool
    {
        return $authUser->can('ForceDelete:UserExpense');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:UserExpense');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:UserExpense');
    }

    public function replicate(AuthUser $authUser, UserExpense $userExpense): bool
    {
        return $authUser->can('Replicate:UserExpense');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:UserExpense');
    }

}
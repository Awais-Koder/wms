<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\HospitalPayment;
use Illuminate\Auth\Access\HandlesAuthorization;

class HospitalPaymentPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:HospitalPayment');
    }

    public function view(AuthUser $authUser, HospitalPayment $hospitalPayment): bool
    {
        return $authUser->can('View:HospitalPayment');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:HospitalPayment');
    }

    public function update(AuthUser $authUser, HospitalPayment $hospitalPayment): bool
    {
        return $authUser->can('Update:HospitalPayment');
    }

    public function delete(AuthUser $authUser, HospitalPayment $hospitalPayment): bool
    {
        return $authUser->can('Delete:HospitalPayment');
    }

    public function restore(AuthUser $authUser, HospitalPayment $hospitalPayment): bool
    {
        return $authUser->can('Restore:HospitalPayment');
    }

    public function forceDelete(AuthUser $authUser, HospitalPayment $hospitalPayment): bool
    {
        return $authUser->can('ForceDelete:HospitalPayment');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:HospitalPayment');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:HospitalPayment');
    }

    public function replicate(AuthUser $authUser, HospitalPayment $hospitalPayment): bool
    {
        return $authUser->can('Replicate:HospitalPayment');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:HospitalPayment');
    }

}
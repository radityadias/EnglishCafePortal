<?php

namespace App\Observers;

use App\Enums\Position;
use App\Jobs\SendEmailSetupPassword;
use App\Models\EmployeeProfile;
use App\Models\InternshipProfile;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
//        if (!$user->hasSetPassword()) {
//            SendEmailSetupPassword::dispatch($user);
//        }
//
//        if ($this->isUserHasProfile($user)) {
//            return;
//        }
//
//        $this->processStoreProfile($user);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }

    public function processStoreProfile(User $user): void
    {
        if ($user->position == Position::Employee) {
            $this->storeEmployeeProfile($user);
        } else {
            $this->storeInternshipProfile($user);
        }
    }

    public function storeEmployeeProfile(User $user): void
    {
        EmployeeProfile::firstOrCreate([
            'user_id' => $user->id
        ]);
    }

    public function storeInternshipProfile(User $user): void
    {
        InternshipProfile::firstOrCreate([
            'user_id' => $user->id
        ]);
    }

    public function isUserHasProfile(User $user): bool
    {
        return is_null($user->employeeProfile | $user->internshipProfile);
    }
}

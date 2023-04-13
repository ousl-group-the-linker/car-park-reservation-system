<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class BookingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        if ($user->isSuperAdminAccount()) return true;
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Booking $booking)
    {
        if ($user->isSuperAdminAccount()) return true;
        if ($user->isManagerAccount() && $user->ManageBranch->id == $booking->Branch->id) return true;
        if ($user->isCounterAccount() && $user->WorkForBranch->id == $booking->Branch->id) return true;
        if ($user->isUserAccount() && $user->id == $booking->Client->id) return true;
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->isCounterAccount();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Booking $booking)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Booking $booking)
    {
        return false;
    }

    /**
     * Determine whether the user can mark the booking as started.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function markAsOnGoing(User $user, Booking $booking)
    {
        if (!$booking->isPending()) {
            return Response::deny("This booking does not permitted to start.");
        }

        if (
            $user->isManagerAccount()
            && $user->ManageBranch->id == $booking->Branch->id
        ) return Response::allow();

        if (
            $user->isCounterAccount()
            && $user->WorkForBranch->id == $booking->Branch->id
        ) return Response::allow();


        return Response::deny("You don't authorization to execute the action.");
    }

    /**
     * Determine whether the user can mark the booking as started.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function markAsFinished(User $user, Booking $booking)
    {
        if ($booking->isOnGoing()) {
            return Response::allow();
        } else {
            return Response::deny("This booking does not permitted to start.");
        }

        if (
            $user->isManagerAccount()
            && $user->ManageBranch->id == $booking->Branch->id
        ) return Response::allow();
        if (
            $user->isCounterAccount()
            && $user->WorkForBranch->id == $booking->Branch->id
        ) return Response::allow();


        return Response::deny("You don't authorization to execute the action.");
    }

    /**
     * Determine whether the user can mark the booking as started.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function markAsCancelled(User $user, Booking $booking)
    {
        if ($booking->isFinished() || $booking->isCancelled()) {
            return Response::deny("This booking does not permitted to cancel.");
        }

        if (
            $user->isManagerAccount()
            && $user->ManageBranch->id == $booking->Branch->id
        ) return Response::allow();

        if (
            $user->isCounterAccount()
            && $user->WorkForBranch->id == $booking->Branch->id
        ) return Response::allow();

        if (
            $user->isUserAccount()
            && $user->id == $booking->Client->id
            && $booking->isPending()
        ) return Response::allow();


        return Response::deny("You don't authorization to execute the action.");
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Booking $booking)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Booking $booking)
    {
        return false;
    }
}

<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Hearing;

class HearingPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Hearing $hearing)
    {
        if ($user->isAdmin()) return true;
        if ($user->isLawyer()) return $user->id === $hearing->legalCase->lawyer_id;
        return $user->isClient() && $user->email === $hearing->legalCase->client->email;
    }

    public function create(User $user)
    {
        // Only Lawyers can create hearings, Admin has view-only access
        return $user->isLawyer();
    }

    public function update(User $user, Hearing $hearing)
    {
        if ($user->isAdmin()) return false; // Admin has view-only access
        // Only the assigned lawyer can update their hearings
        return $user->isLawyer() && $user->id === $hearing->legalCase->lawyer_id;
    }

    public function delete(User $user, Hearing $hearing)
    {
        if ($user->isAdmin()) return false; // Admin has view-only access
        // Only the assigned lawyer can delete their hearings
        return $user->isLawyer() && $user->id === $hearing->legalCase->lawyer_id;
    }
}
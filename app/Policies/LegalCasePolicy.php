<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LegalCase;

class LegalCasePolicy
{
    public function before(User $user, $ability)
    {
        if (in_array($ability, ['create', 'update', 'delete'], true) && ! $user->isLawyer()) {
            return false;
        }
    }

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, LegalCase $case)
    {
        if ($user->isAdmin()) return true;
        // Lawyers can only view cases assigned to them
        if ($user->isLawyer()) return $user->id === $case->lawyer_id;
        // Clients can view their own cases
        return $user->isClient() && $user->email === $case->client->email;
    }

    public function create(User $user)
    {
        // Only Lawyers can create cases, Admin has view-only access
        return $user->isLawyer();
    }

    public function update(User $user, LegalCase $case)
    {
        if ($user->isAdmin()) return false; // Admins have view-only access
        // Only the assigned lawyer can update their cases
        return $user->isLawyer() && $user->id === $case->lawyer_id;
    }

    public function delete(User $user, LegalCase $case)
    {
        if ($user->isAdmin()) return false; // Admins have view-only access
        // Only the assigned lawyer can delete their cases
        return $user->isLawyer() && $user->id === $case->lawyer_id;
    }
}

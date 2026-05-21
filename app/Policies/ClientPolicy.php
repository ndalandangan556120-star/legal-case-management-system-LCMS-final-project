<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Client;

class ClientPolicy
{
    public function viewAny(User $user)
    {
        return $user->isAdmin() || $user->isLawyer();
    }

    public function view(User $user, Client $client)
    {
        if ($user->isAdmin()) return true;
        // Lawyers can view clients they own or clients they have cases with
        if ($user->isLawyer()) {
            return $client->lawyer_id === $user->id || \App\Models\LegalCase::where('lawyer_id', $user->id)
                ->where('client_id', $client->id)
                ->exists();
        }
        return $user->isClient() && $user->email === $client->email;
    }

    public function create(User $user)
    {
        // Only Admins and Lawyers can create clients
        return $user->isAdmin() || $user->isLawyer();
    }

    public function update(User $user, Client $client)
    {
        if ($user->isAdmin()) return true;
        // Lawyers can update clients they own or clients they have cases with
        if ($user->isLawyer()) {
            return $client->lawyer_id === $user->id || \App\Models\LegalCase::where('lawyer_id', $user->id)
                ->where('client_id', $client->id)
                ->exists();
        }
        return false;
    }

    public function delete(User $user, Client $client)
    {
        if ($user->isAdmin()) return true;
        // Lawyers can delete clients they own or clients they have cases with
        if ($user->isLawyer()) {
            return $client->lawyer_id === $user->id || \App\Models\LegalCase::where('lawyer_id', $user->id)
                ->where('client_id', $client->id)
                ->exists();
        }
        return false;
    }
}

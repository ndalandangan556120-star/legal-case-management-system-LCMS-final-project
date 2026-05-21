<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Document;

class DocumentPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Document $document)
    {
        if ($user->isAdmin()) return true;
        if ($user->isLawyer()) return $user->id === $document->legalCase->lawyer_id;
        // Clients can view documents for their own cases
        return $user->isClient() && $user->email === $document->legalCase->client->email;
    }

    public function create(User $user)
    {
        // Only Lawyers can upload documents/attachments
        return $user->isLawyer();
    }

    public function update(User $user, Document $document)
    {
        // Only the assigned lawyer can update their documents
        return $user->isLawyer() && $user->id === $document->legalCase->lawyer_id;
    }

    public function delete(User $user, Document $document)
    {
        // Only the assigned lawyer can delete their documents
        return $user->isLawyer() && $user->id === $document->legalCase->lawyer_id;
    }

    public function download(User $user, Document $document)
    {
        // Same as view - Lawyers, Admins, and clients viewing their own cases can download
        return $this->view($user, $document);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isClient()) {
            $client = Client::where('user_id', $user->id)->first();

            $documents = $client
                ? Document::with(['legalCase.client'])
                    ->whereHas('legalCase', fn($query) => $query->where('client_id', $client->id))
                    ->latest()
                    ->get()
                : collect();
        } elseif ($user->isLawyer()) {
            // Lawyers can only see documents for their assigned cases
            $documents = Document::with(['legalCase.client'])
                ->whereHas('legalCase', fn($query) => $query->where('lawyer_id', $user->id))
                ->latest()
                ->get();
        } else {
            $documents = Document::with(['legalCase.client'])->latest()->get();
        }

        return view('documents.index', compact('documents'));
    }

    public function store(Request $request)
    {
        // Only Lawyers can upload documents
        $this->authorize('create', Document::class);

        $request->validate([
            'case_id' => 'required|exists:cases,id',
            'document' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'name' => 'required|string',
        ]);

        $path = $request->file('document')->store('documents', 'public');

        Document::create([
            'case_id' => $request->case_id,
            'name' => $request->name,
            'file_path' => $path,
            'file_type' => $request->file('document')->getClientOriginalExtension(),
        ]);

        return back()->with('success', 'Document uploaded successfully.');
    }

    public function download(Document $document)
    {
        // Check if user is authorized to download this document
        $this->authorize('download', $document);

        if (! Storage::disk('public')->exists($document->file_path)) {
            return back()->withErrors(['document' => 'Requested file is not available.']);
        }

        return response()->download(
            storage_path('app/public/' . $document->file_path),
            $document->name . '.' . $document->file_type
        );
    }
}

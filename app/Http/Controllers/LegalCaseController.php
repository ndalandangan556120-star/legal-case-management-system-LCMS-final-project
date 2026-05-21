<?php

namespace App\Http\Controllers;

use App\Models\LegalCase;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LegalCaseController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = LegalCase::with(['client', 'lawyer']);

        if ($user->isClient()) {
            $client = Client::where('user_id', $user->id)->first();
            $query->where('client_id', $client->id);
        } elseif ($user->isLawyer()) {
            // Lawyers can only see cases assigned to them
            $query->where('lawyer_id', $user->id);
        }

        // Apply search filter
        if ($search = $request->query('search')) {
            $query->where(function ($sub) use ($search) {
                $sub->where('case_number', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($clientQuery) use ($search) {
                        $clientQuery->where('full_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $cases = $query->latest()->get();

        $caseGroups = collect();
        if ($user->isLawyer()) {
            $caseGroups = $cases->groupBy('client_id')->map(function ($group) {
                $latest = $group->sortByDesc('updated_at')->first();
                $statusCounts = $group->groupBy('status')->map(fn($items) => $items->count());

                return (object) [
                    'client' => $latest->client,
                    'lawyer' => $latest->lawyer,
                    'case_count' => $group->count(),
                    'latest_case' => $latest,
                    'case_type_label' => 'Legal Case',
                    'status_label' => $statusCounts->count() > 1 ? 'Mixed' : $statusCounts->keys()->first(),
                    'status_counts' => $statusCounts,
                    'latest_updated_at' => $latest->updated_at,
                ];
            })->values();
        }

        $totalCases = $cases->count();
        $statusCounts = $cases->groupBy('status')->map(fn($group) => $group->count());
        $statusCounts = [
            'Open' => $statusCounts->get('Open', 0),
            'In Progress' => $statusCounts->get('In Progress', 0),
            'Scheduled' => $statusCounts->get('Scheduled', 0),
            'Closed' => $statusCounts->get('Closed', 0),
            'Pending' => $statusCounts->get('Pending', 0),
            'On Hold' => $statusCounts->get('On Hold', 0),
        ];

        $caseTypeCounts = ['Legal Case' => $cases->count()];
        $recentCases = $cases->sortByDesc('updated_at')->take(5);

        return view('cases.index', compact('cases', 'caseGroups', 'totalCases', 'statusCounts', 'caseTypeCounts', 'recentCases'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', LegalCase::class);
        $currentLawyer = Auth::user();
        $selectedClientId = $request->query('client_id');

        // Lawyers should see clients they own or clients with cases assigned to them
        if ($currentLawyer->isLawyer()) {
            $clients = Client::where(function ($query) use ($currentLawyer) {
                $query->whereHas('cases', function ($query) use ($currentLawyer) {
                    $query->where('lawyer_id', $currentLawyer->id);
                })->orWhere('lawyer_id', $currentLawyer->id);
            })->get();
        } else {
            $clients = Client::all();
        }
        
        $lawyers = User::where('role', 'Lawyer')->get();
        $selectedClient = $clients->firstWhere('id', $selectedClientId);

        return view('cases.create', compact('clients', 'lawyers', 'currentLawyer', 'selectedClientId', 'selectedClient'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', LegalCase::class);
        
        $rules = [
            'case_number' => 'required|unique:cases',
            'title' => 'required',
            'description' => 'required',
            'incident_date' => 'required|date',
            'status' => ['required', Rule::in(LegalCase::statuses())],
            'client_id' => 'required|exists:clients,id',
        ];

        if (Auth::user()->isLawyer()) {
            $rules['lawyer_id'] = ['required', Rule::in([Auth::id()])];
        } else {
            $rules['lawyer_id'] = ['required', 'exists:users,id'];
        }

        $validated = $request->validate($rules);

        if (Auth::user()->isLawyer()) {
            $validated['lawyer_id'] = Auth::id();
        }

        try {
            LegalCase::create($validated);
            return redirect()->route('cases.index')->with('success', 'Case created successfully.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Case creation failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to create case: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(LegalCase $case)
    {
        $this->authorize('update', $case);

        $currentLawyer = Auth::user();
        
        // Lawyers should see clients they own or clients with cases assigned to them
        if ($currentLawyer->isLawyer()) {
            $clients = Client::where(function ($query) use ($currentLawyer) {
                $query->whereHas('cases', function ($query) use ($currentLawyer) {
                    $query->where('lawyer_id', $currentLawyer->id);
                })->orWhere('lawyer_id', $currentLawyer->id);
            })->get();
        } else {
            $clients = Client::all();
        }
        
        $lawyers = User::where('role', 'Lawyer')->get();

        return view('cases.edit', compact('case', 'clients', 'lawyers', 'currentLawyer'));
    }

    public function update(Request $request, LegalCase $case)
    {
        $this->authorize('update', $case);

        $rules = [
            'case_number' => 'required|unique:cases,case_number,' . $case->id,
            'title' => 'required',
            'description' => 'required',
            'incident_date' => 'required|date',
            'status' => ['required', Rule::in(LegalCase::statuses())],
            'client_id' => 'required|exists:clients,id',
        ];

        if (Auth::user()->isLawyer()) {
            $rules['lawyer_id'] = ['required', Rule::in([Auth::id()])];
        } else {
            $rules['lawyer_id'] = ['required', 'exists:users,id'];
        }

        $validated = $request->validate($rules);

        if (Auth::user()->isLawyer()) {
            $validated['lawyer_id'] = Auth::id();
        }

        try {
            $case->update($validated);
            return redirect()->route('cases.show', $case)->with('success', 'Case updated successfully.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Case update failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to update case: ' . $e->getMessage())->withInput();
        }
    }

    public function show(LegalCase $case)
    {
        $this->authorize('view', $case);
        
        $case->load(['client', 'lawyer', 'hearings', 'documents']);
        return view('cases.show', compact('case'));
    }

    public function destroy(LegalCase $case)
    {
        $this->authorize('delete', $case);

        try {
            $case->delete();
            return redirect()->route('cases.index')->with('success', 'Case deleted successfully.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Case delete failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to delete case.');
        }
    }
}

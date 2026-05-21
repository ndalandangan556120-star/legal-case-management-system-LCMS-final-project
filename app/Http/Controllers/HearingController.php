<?php

namespace App\Http\Controllers;

use App\Models\Hearing;
use App\Models\LegalCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HearingController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Hearing::class, 'hearing');
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Hearing::with(['legalCase.client'])->orderBy('hearing_date', 'asc');

        if ($user->isClient()) {
            $client = \App\Models\Client::where('user_id', $user->id)->first();
            $query->whereHas('legalCase', function ($query) use ($client) {
                $query->where('client_id', $client->id);
            });
            $cases = $client ? LegalCase::where('client_id', $client->id)->orderBy('case_number')->get() : collect();
        } elseif ($user->isLawyer()) {
            // Lawyers can only see hearings for their assigned cases
            $query->whereHas('legalCase', function ($query) use ($user) {
                $query->where('lawyer_id', $user->id);
            });
            $cases = LegalCase::where('lawyer_id', $user->id)->with('client')->orderBy('case_number')->get();
        } else {
            $cases = LegalCase::with('client')->orderBy('case_number')->get();
        }

        if ($search = $request->query('search')) {
            $query->where(function ($sub) use ($search) {
                $sub->where('location', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")
                    ->orWhereHas('legalCase', function ($caseQuery) use ($search) {
                        $caseQuery->where('case_number', 'like', "%{$search}%")
                            ->orWhere('title', 'like', "%{$search}%");
                    });
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($caseId = $request->query('case_id')) {
            $query->where('case_id', $caseId);
        }

        if ($dateFrom = $request->query('date_from')) {
            $query->whereDate('hearing_date', '>=', $dateFrom);
        }

        if ($dateTo = $request->query('date_to')) {
            $query->whereDate('hearing_date', '<=', $dateTo);
        }

        $hearings = $query->get();

        $totalHearings = $hearings->count();
        $upcomingHearings = $hearings->where('hearing_date', '>=', now())->take(6);
        $recentHearings = $hearings->sortByDesc('hearing_date')->take(5);
        $completedCount = $hearings->where('status', 'Completed')->count();
        $cancelledCount = $hearings->where('status', 'Cancelled')->count();
        $scheduledCount = $hearings->where('status', 'Scheduled')->count();

        return view('hearings.index', compact(
            'hearings', 'cases', 'totalHearings', 'upcomingHearings', 'recentHearings',
            'completedCount', 'cancelledCount', 'scheduledCount'
        ));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Hearing::class);

        $rules = [
            'case_id' => 'required|exists:cases,id',
            'hearing_date' => 'required|date',
            'location' => 'required|string',
            'notes' => 'nullable|string',
            'status' => ['required', 'in:Scheduled,Completed,Cancelled'],
        ];

        // Additional validation: lawyers can only create hearings for their assigned cases
        if (Auth::user()->isLawyer()) {
            $rules['case_id'] = ['required', 'exists:cases,id', function ($attribute, $value, $fail) {
                $case = \App\Models\LegalCase::find($value);
                if (!$case || $case->lawyer_id !== Auth::id()) {
                    $fail('You can only create hearings for cases assigned to you.');
                }
            }];
        }

        $validated = $request->validate($rules);

        Hearing::create($validated);

        return back()->with('success', 'Hearing scheduled successfully.');
    }

    public function show(Hearing $hearing)
    {
        $hearing->load(['legalCase.client', 'legalCase.lawyer']);
        return view('hearings.show', compact('hearing'));
    }

    public function edit(Hearing $hearing)
    {
        $this->authorize('update', $hearing);

        $cases = LegalCase::with('client')->orderBy('case_number')->get();
        $hearing->load(['legalCase.client']);

        return view('hearings.edit', compact('hearing', 'cases'));
    }

    public function update(Request $request, Hearing $hearing)
    {
        $this->authorize('update', $hearing);

        $validated = $request->validate([
            'case_id' => 'required|exists:cases,id',
            'hearing_date' => 'required|date',
            'location' => 'required|string',
            'notes' => 'nullable|string',
            'status' => ['required', 'in:Scheduled,Completed,Cancelled'],
        ]);

        $hearing->update($validated);

        return redirect()->route('hearings.show', $hearing)->with('success', 'Hearing schedule updated successfully.');
    }

    public function destroy(Hearing $hearing)
    {
        $this->authorize('delete', $hearing);

        try {
            $hearing->delete();
            return redirect()->back()->with('success', 'Hearing deleted successfully.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Hearing delete failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to delete hearing.');
        }
    }
}

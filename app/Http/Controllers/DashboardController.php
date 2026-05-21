<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\LegalCase;
use App\Models\Hearing;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {

            $stats = [
                'total_clients'  => Client::count(),
                'total_cases'    => LegalCase::count(),
                'hearings_today' => Hearing::whereDate('hearing_date', today())->count(),
            ];

            // Doughnut chart — 6 statuses (Admin: lahat ng cases)
            $caseStatusCounts = [
                'Open'        => LegalCase::where('status', 'Open')->count(),
                'In Progress' => LegalCase::where('status', 'In Progress')->count(),
                'Pending'     => LegalCase::where('status', 'Pending')->count(),
                'Closed'      => LegalCase::where('status', 'Closed')->count(),
                'Disposed'    => LegalCase::where('status', 'Disposed')->count(),
                'On Hold'     => LegalCase::where('status', 'On Hold')->count(),
            ];

            // Legacy $caseStatus (para hindi masira ang ibang parte ng view)
            $caseStatus = [
                'open'        => $caseStatusCounts['Open'],
                'in_progress' => $caseStatusCounts['In Progress'],
                'pending'     => $caseStatusCounts['Pending'],
                'closed'      => $caseStatusCounts['Closed'],
                'disposed'    => $caseStatusCounts['Disposed'],
                'on_hold'     => $caseStatusCounts['On Hold'],
                'scheduled'   => LegalCase::where('status', 'Scheduled')->count(),
            ];

            $upcomingHearings = Hearing::with('legalCase')
                ->whereDate('hearing_date', '>=', today())
                ->orderBy('hearing_date')
                ->take(4)
                ->get();

            $recentCases = LegalCase::with(['client', 'lawyer'])
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();

            $tasks = [
                'pending'   => LegalCase::whereIn('status', ['Open', 'In Progress', 'Pending', 'On Hold'])->count(),
                'due_today' => Hearing::whereDate('hearing_date', today())->count(),
                'completed' => LegalCase::where('status', 'Closed')->count(),
                'overdue'   => Hearing::whereDate('hearing_date', '<', today())->count(),
            ];

            $totalCases    = LegalCase::count();
            $activeCases   = $caseStatusCounts['In Progress'];
            $hearingsToday = $stats['hearings_today'];
            $totalClients  = $stats['total_clients'];

        } elseif ($user->isLawyer()) {

            $stats = [
                'total_clients'  => Client::whereHas('cases', fn($q) => $q->where('lawyer_id', $user->id))->count(),
                'total_cases'    => LegalCase::where('lawyer_id', $user->id)->count(),
                'hearings_today' => Hearing::whereHas('legalCase', fn($q) => $q->where('lawyer_id', $user->id))
                                        ->whereDate('hearing_date', today())->count(),
            ];

            // Doughnut chart — 6 statuses (Lawyer: sarili lang na cases)
            $caseStatusCounts = [
                'Open'        => LegalCase::where('lawyer_id', $user->id)->where('status', 'Open')->count(),
                'In Progress' => LegalCase::where('lawyer_id', $user->id)->where('status', 'In Progress')->count(),
                'Pending'     => LegalCase::where('lawyer_id', $user->id)->where('status', 'Pending')->count(),
                'Closed'      => LegalCase::where('lawyer_id', $user->id)->where('status', 'Closed')->count(),
                'Disposed'    => LegalCase::where('lawyer_id', $user->id)->where('status', 'Disposed')->count(),
                'On Hold'     => LegalCase::where('lawyer_id', $user->id)->where('status', 'On Hold')->count(),
            ];

            // Legacy $caseStatus
            $caseStatus = [
                'open'        => $caseStatusCounts['Open'],
                'in_progress' => $caseStatusCounts['In Progress'],
                'pending'     => $caseStatusCounts['Pending'],
                'closed'      => $caseStatusCounts['Closed'],
                'disposed'    => $caseStatusCounts['Disposed'],
                'on_hold'     => $caseStatusCounts['On Hold'],
                'scheduled'   => LegalCase::where('lawyer_id', $user->id)->where('status', 'Scheduled')->count(),
            ];

            $upcomingHearings = Hearing::with('legalCase')
                ->whereHas('legalCase', fn($q) => $q->where('lawyer_id', $user->id))
                ->whereDate('hearing_date', '>=', today())
                ->orderBy('hearing_date')
                ->take(4)
                ->get();

            $recentCases = LegalCase::with(['client', 'lawyer'])
                ->where('lawyer_id', $user->id)
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();

            $tasks = [
                'pending'   => LegalCase::where('lawyer_id', $user->id)->whereIn('status', ['Open', 'In Progress', 'Pending', 'On Hold'])->count(),
                'due_today' => Hearing::whereHas('legalCase', fn($q) => $q->where('lawyer_id', $user->id))->whereDate('hearing_date', today())->count(),
                'completed' => LegalCase::where('lawyer_id', $user->id)->where('status', 'Closed')->count(),
                'overdue'   => Hearing::whereHas('legalCase', fn($q) => $q->where('lawyer_id', $user->id))->whereDate('hearing_date', '<', today())->count(),
            ];

            $totalCases    = $stats['total_cases'];
            $activeCases   = $caseStatusCounts['In Progress'];
            $hearingsToday = $stats['hearings_today'];
            $totalClients  = $stats['total_clients'];

        } else {
            // Client role — walang doughnut chart, hindi na kailangan ng caseStatusCounts
            $client = Client::where('user_id', $user->id)->first();

            $stats = [
                'total_cases'       => $client ? $client->cases()->count() : 0,
                'upcoming_hearings' => $client
                    ? Hearing::whereHas('legalCase', fn($q) => $q->where('client_id', $client->id))
                             ->where('hearing_date', '>=', now())->count()
                    : 0,
            ];

            $caseStatus       = null;
            $caseStatusCounts = null;   // hindi ginagamit sa client view
            $clientCases      = $client ? $client->cases()->with(['hearings', 'lawyer'])->get() : [];

            $totalCases    = $stats['total_cases'];
            $activeCases   = $client ? $client->cases()->where('status', 'In Progress')->count() : 0;
            $hearingsToday = $client
                ? Hearing::whereHas('legalCase', fn($q) => $q->where('client_id', $client->id))
                         ->whereDate('hearing_date', today())->count()
                : 0;
            $totalClients  = 0;

            return view('dashboard', compact(
                'stats', 'caseStatus', 'caseStatusCounts', 'clientCases',
                'totalCases', 'activeCases', 'hearingsToday', 'totalClients'
            ));
        }

        return view('dashboard', compact(
            'stats', 'caseStatus', 'caseStatusCounts',
            'totalCases', 'activeCases', 'hearingsToday', 'totalClients',
            'upcomingHearings', 'recentCases', 'tasks'
        ));
    }
}
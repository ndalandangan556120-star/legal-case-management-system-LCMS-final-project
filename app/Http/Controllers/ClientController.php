<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->isClient()) {
            $client = Client::where('user_id', Auth::id())->first();
            if ($client) {
                return redirect()->route('clients.show', $client->id);
            }
            return redirect()->route('dashboard');
        }

        $search = $request->query('search');

        $clientQuery = Client::withCount([
            'cases as active_cases_count' => function ($query) {
                $query->whereIn('status', ['Open', 'Pending']);
            },
            'cases'
        ]);

        if (Auth::user()->isLawyer()) {
            $clientQuery->where(function ($query) {
                $query->whereIn('id', function ($query) {
                    $query->select('client_id')
                        ->distinct()
                        ->from('cases')
                        ->where('lawyer_id', Auth::id());
                })->orWhere('lawyer_id', Auth::id());
            });
        }

        if ($search) {
            $clientQuery->where(function ($query) use ($search) {
                $query->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $allClients = $clientQuery->orderBy('created_at', 'desc')->get();

        $uniqueClients = $allClients->groupBy(function ($client) {
            return strtolower(trim($client->email));
        })->map(function ($group) {
            $master = $group->sortByDesc('created_at')->first();
            $master->active_cases_count = $group->sum('active_cases_count');
            $master->cases_count = $group->sum('cases_count');
            return $master;
        })->values();

        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 12;
        $paginatedClients = new LengthAwarePaginator(
            $uniqueClients->forPage($page, $perPage)->values(),
            $uniqueClients->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $clientTypeCounts = ['Individual' => 0, 'Corporate' => 0, 'Law Firm' => 0, 'Other' => 0];
        foreach ($uniqueClients as $client) {
            $type = $client->client_type ?? 'Other';
            if (array_key_exists($type, $clientTypeCounts)) {
                $clientTypeCounts[$type]++;
            } else {
                $clientTypeCounts['Other']++;
            }
        }

        $recentClients = $uniqueClients->sortByDesc('created_at')->take(5);
        $uniqueClientCount = $uniqueClients->count();
        $newClients = $uniqueClients->filter(fn($c) => $c->created_at >= now()->subMonth())->count();
        $activeClients = $uniqueClients->filter(fn($c) => $c->active_cases_count > 0)->count();
        $inactiveClients = $uniqueClients->filter(fn($c) => $c->active_cases_count === 0)->count();

        return view('clients.index', [
            'clients'          => $paginatedClients,
            'search'           => $search,
            'totalClients'     => $uniqueClientCount,
            'newClients'       => $newClients,
            'activeClients'    => $activeClients,
            'inactiveClients'  => $inactiveClients,
            'corporateClients' => $clientTypeCounts['Corporate'],
            'clientTypeCounts' => $clientTypeCounts,
            'recentClients'    => $recentClients,
        ]);
    }

    public function create()
    {
        $this->authorize('create', Client::class);
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Client::class);

        $validated = $request->validate([
            'full_name' => 'nullable|string|max:255',
            'email'     => ['required', 'email', 'unique:clients,email', 'unique:users,email'],
            'phone'     => 'nullable|string|max:20',
            'address'   => 'required|string',
        ]);

        // Gumawa ng User account para makapag-login ang client
        $user = User::create([
            'name'     => $validated['full_name'] ?? 'Client',
            'email'    => $validated['email'],
            'password' => Hash::make('password'), // default password
            'role'     => 'Client',
        ]);

        // Gumawa ng Client record na naka-link sa User
        $clientData = [
            'full_name' => $validated['full_name'] ?? null,
            'email'     => $validated['email'],
            'phone'     => $validated['phone'] ?? null,
            'address'   => $validated['address'],
            'user_id'   => $user->id,
        ];

        if (Auth::user()->isLawyer()) {
            $clientData['lawyer_id'] = Auth::id();
        }

        Client::create($clientData);

        return redirect()->route('clients.index')
            ->with('success', 'Client created successfully. Default password is "password".');
    }

    public function show(Client $client, Request $request)
    {
        $this->authorize('view', $client);

        $casesQuery = $client->cases()->with(['hearings.legalCase', 'documents', 'lawyer']);

        if ($search = $request->query('search')) {
            $casesQuery->where(function ($query) use ($search) {
                $query->where('case_number', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $filteredCases = $casesQuery->get();
        $client->setRelation('cases', $filteredCases);

        $hearings = $filteredCases->flatMap(fn($case) => $case->hearings)->sortBy('hearing_date');
        $nextHearing = $hearings->firstWhere('hearing_date', '>=', now());
        $latestHearing = $hearings->sortByDesc('hearing_date')->first();

        return view('clients.show', compact('client', 'nextHearing', 'latestHearing'));
    }
}
<?php

namespace App\Providers;

use App\Models\Document;
use App\Models\LegalCase;
use App\Models\Client;
use App\Models\Hearing;
use App\Policies\DocumentPolicy;
use App\Policies\LegalCasePolicy;
use App\Policies\ClientPolicy;
use App\Policies\HearingPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Document::class => DocumentPolicy::class,
        Client::class => ClientPolicy::class,
        LegalCase::class => LegalCasePolicy::class,
        Hearing::class => HearingPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}

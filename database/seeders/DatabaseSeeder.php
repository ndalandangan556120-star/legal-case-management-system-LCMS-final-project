<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\LegalCase;
use App\Models\Hearing;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@lcms.com',
            'password' => Hash::make('password'),
            'role' => 'Admin',
        ]);

        // Create 3 Lawyers
        $lawyer1 = User::create([
            'name' => 'John Lawyer',
            'email' => 'lawyer1@lcms.com',
            'password' => Hash::make('password'),
            'role' => 'Lawyer',
        ]);

        $lawyer2 = User::create([
            'name' => 'Sarah Attorney',
            'email' => 'lawyer2@lcms.com',
            'password' => Hash::make('password'),
            'role' => 'Lawyer',
        ]);

        $lawyer3 = User::create([
            'name' => 'Mike Counsel',
            'email' => 'lawyer3@lcms.com',
            'password' => Hash::make('password'),
            'role' => 'Lawyer',
        ]);

        // Create Client (Dalandangan example)
        $clientUser = User::create([
            'name' => 'Dalandangan',
            'email' => 'client@lcms.com',
            'password' => Hash::make('password'),
            'role' => 'Client',
        ]);

        $client = Client::create([
            'full_name' => 'Dalandangan',
            'email' => 'client@lcms.com',
            'phone' => '1234567890',
            'address' => '123 Main Street, City',
            'user_id' => $clientUser->id,
        ]);

        // Case 1: Affidavit of Loss
        $case1 = LegalCase::create([
            'case_number' => 'LC-2026-001',
            'title' => 'Affidavit of Loss',
            'description' => 'Client lost important documents and needs affidavit for replacement.',
            'incident_date' => '2026-01-15',
            'status' => 'Open',
            'client_id' => $client->id,
            'lawyer_id' => $lawyer1->id,
        ]);

        // Case 2: Accident Case
        $case2 = LegalCase::create([
            'case_number' => 'LC-2026-002',
            'title' => 'Accident Case',
            'description' => 'Client was involved in a traffic accident and needs legal representation.',
            'incident_date' => '2026-03-20',
            'status' => 'Open',
            'client_id' => $client->id,
            'lawyer_id' => $lawyer1->id,
        ]);

        // Case 3: Property Dispute (Lawyer 2)
        $case3 = LegalCase::create([
            'case_number' => 'LC-2026-003',
            'title' => 'Property Dispute',
            'description' => 'Client involved in property boundary dispute with neighbor requiring legal intervention.',
            'incident_date' => '2026-02-10',
            'status' => 'Open',
            'client_id' => $client->id,
            'lawyer_id' => $lawyer2->id,
        ]);

        // Case 4: Contract Breach (Lawyer 2)
        $case4 = LegalCase::create([
            'case_number' => 'LC-2026-004',
            'title' => 'Contract Breach',
            'description' => 'Client claims breach of contract by service provider requiring legal remedies.',
            'incident_date' => '2026-01-25',
            'status' => 'In Progress',
            'client_id' => $client->id,
            'lawyer_id' => $lawyer2->id,
        ]);

        // Case 5: Employment Dispute (Lawyer 3)
        $case5 = LegalCase::create([
            'case_number' => 'LC-2026-005',
            'title' => 'Employment Dispute',
            'description' => 'Client involved in employment dispute with previous employer regarding severance package.',
            'incident_date' => '2026-04-05',
            'status' => 'Open',
            'client_id' => $client->id,
            'lawyer_id' => $lawyer3->id,
        ]);

        // Case 6: Inheritance Matter (Lawyer 3)
        $case6 = LegalCase::create([
            'case_number' => 'LC-2026-006',
            'title' => 'Inheritance Matter',
            'description' => 'Client seeking legal assistance for inheritance and estate settlement matters.',
            'incident_date' => '2026-03-08',
            'status' => 'Open',
            'client_id' => $client->id,
            'lawyer_id' => $lawyer3->id,
        ]);

        // Add hearings for each case
        Hearing::create([
            'case_id' => $case1->id,
            'hearing_date' => now()->addDays(7),
            'location' => 'District Court Room 4',
            'notes' => 'Initial hearing for affidavit validation.',
            'status' => 'Scheduled',
        ]);

        Hearing::create([
            'case_id' => $case2->id,
            'hearing_date' => now()->addDays(14),
            'location' => 'Regional Trial Court Room 2',
            'notes' => 'Case hearing for accident claim.',
            'status' => 'Scheduled',
        ]);

        // Hearings for Lawyer 2
        Hearing::create([
            'case_id' => $case3->id,
            'hearing_date' => now()->addDays(5),
            'location' => 'District Court Room 1',
            'notes' => 'Property boundary dispute hearing.',
            'status' => 'Scheduled',
        ]);

        Hearing::create([
            'case_id' => $case4->id,
            'hearing_date' => now()->addDays(21),
            'location' => 'Commercial Court Room 3',
            'notes' => 'Contract breach settlement hearing.',
            'status' => 'Scheduled',
        ]);

        // Hearings for Lawyer 3
        Hearing::create([
            'case_id' => $case5->id,
            'hearing_date' => now()->addDays(10),
            'location' => 'Labor Court Room 5',
            'notes' => 'Employment dispute hearing and arbitration.',
            'status' => 'Scheduled',
        ]);

        Hearing::create([
            'case_id' => $case6->id,
            'hearing_date' => now()->addDays(28),
            'location' => 'Probate Court Room 2',
            'notes' => 'Inheritance matter and estate settlement hearing.',
            'status' => 'Scheduled',
        ]);
    }
}

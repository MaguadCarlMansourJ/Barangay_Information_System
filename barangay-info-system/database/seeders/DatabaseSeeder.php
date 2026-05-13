<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\DocumentType;
use App\Models\Event;
use App\Models\Blotter;
use App\Models\DocumentRequest;
use App\Models\Payment;
use App\Models\EventParticipant;
use App\Models\BlotterParty;
use App\Models\Resident;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    private function upsertUser(string $name, string $email, string $role): void
    {
        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make('BarangayBis@2026!'),
                'role' => $role,
                'is_active' => true,
            ]
        );
    }

    public function run(): void
    {
        // 1. Mass data (1378 residents, 300 households, 10 puroks)
        $this->call(MassDataSeeder::class);

        // 2. Document Types
        $documents = [
            ['name' => 'Barangay Clearance', 'description' => 'Certification that the resident has no derogatory barangay record for employment, school, travel, or legal purposes.', 'fee' => 50.00, 'processing_days' => 1],
            ['name' => 'Certificate of Residency', 'description' => 'Proof that the person is a bona fide resident of the barangay.', 'fee' => 30.00, 'processing_days' => 1],
            ['name' => 'Certificate of Indigency', 'description' => 'Certification for social welfare, medical, burial, school, or assistance requirements.', 'fee' => 0.00, 'processing_days' => 1],
            ['name' => 'Business Clearance', 'description' => 'Barangay clearance for business permit application or renewal.', 'fee' => 100.00, 'processing_days' => 2],
            ['name' => 'Certificate of Good Moral Character', 'description' => 'Barangay certification of good standing in the community.', 'fee' => 30.00, 'processing_days' => 1],
            ['name' => 'Certificate of Low Income', 'description' => 'Certification used for scholarship, medical, and government assistance applications.', 'fee' => 0.00, 'processing_days' => 1],
            ['name' => 'First Time Job Seeker Certificate', 'description' => 'Certification under the First Time Jobseekers Assistance Act for qualified first-time job seekers.', 'fee' => 0.00, 'processing_days' => 1],
            ['name' => 'Barangay ID', 'description' => 'Barangay identification card for registered residents.', 'fee' => 50.00, 'processing_days' => 3],
            ['name' => 'Community Tax Certificate', 'description' => 'Community tax certificate record commonly requested with barangay transactions.', 'fee' => 25.00, 'processing_days' => 0],
            ['name' => 'VAWC Certification', 'description' => 'Certification related to Violence Against Women and Children desk records.', 'fee' => 0.00, 'processing_days' => 1],
        ];
        foreach ($documents as $doc) {
            DocumentType::updateOrCreate(['name' => $doc['name']], $doc);
        }

        // 3. Spatie Roles
        $roles = ['Captain', 'Secretary', 'Treasurer', 'Staff', 'Resident'];
        foreach ($roles as $role) {
            SpatieRole::firstOrCreate(['name' => $role]);
        }

        // 4. Users
        $users = [
            ['Hon. Juan Dela Cruz - Punong Barangay', 'captain@barangay.gov.ph', 'Captain'],
            ['Hon. Maria Santos - Barangay Kagawad', 'vicecaptain@barangay.gov.ph', 'Staff'],
            ['Barangay Secretary Ana Reyes', 'secretary@barangay.gov.ph', 'Secretary'],
            ['Barangay Treasurer Pedro Ramos', 'treasurer@barangay.gov.ph', 'Treasurer'],
            ['Hon. Lito Garcia - Committee on Peace and Order', 'kagawad1@barangay.gov.ph', 'Staff'],
            ['Hon. Rosa Mendoza - Committee on Health', 'kagawad2@barangay.gov.ph', 'Staff'],
            ['Hon. Carlo Bautista - Committee on Education', 'kagawad3@barangay.gov.ph', 'Staff'],
            ['Hon. Elena Flores - Committee on Environment', 'kagawad4@barangay.gov.ph', 'Staff'],
            ['Hon. Mark Villanueva - Committee on Infrastructure', 'kagawad5@barangay.gov.ph', 'Staff'],
            ['Hon. Grace Aquino - Committee on Social Services', 'kagawad6@barangay.gov.ph', 'Staff'],
            ['Hon. Nestor Cruz - Committee on Livelihood', 'kagawad7@barangay.gov.ph', 'Staff'],
            ['SK Chairperson Miguel Torres', 'skchairman@barangay.gov.ph', 'Staff'],
            ['Chief Barangay Tanod Roberto Lim', 'tanodchief@barangay.gov.ph', 'Staff'],
            ['Barangay Tanod 1', 'tanod1@barangay.gov.ph', 'Staff'],
            ['Barangay Tanod 2', 'tanod2@barangay.gov.ph', 'Staff'],
            ['Barangay Tanod 3', 'tanod3@barangay.gov.ph', 'Staff'],
            ['Barangay Staff 1', 'staff1@barangay.gov.ph', 'Staff'],
            ['Resident User', 'resident@barangay.gov.ph', 'Resident'],
        ];
        foreach ($users as $userData) {
            $this->upsertUser(...$userData);
        }

        // Sync roles
$userRoles = [
            'captain@barangay.gov.ph' => 'Captain',
            'vicecaptain@barangay.gov.ph' => 'Staff',
            'secretary@barangay.gov.ph' => 'Secretary',
            'treasurer@barangay.gov.ph' => 'Treasurer',
            'kagawad1@barangay.gov.ph' => 'Staff',
            'kagawad2@barangay.gov.ph' => 'Staff',
            'kagawad3@barangay.gov.ph' => 'Staff',
            'kagawad4@barangay.gov.ph' => 'Staff',
            'kagawad5@barangay.gov.ph' => 'Staff',
            'kagawad6@barangay.gov.ph' => 'Staff',
            'kagawad7@barangay.gov.ph' => 'Staff',
            'skchairman@barangay.gov.ph' => 'Staff',
            'tanodchief@barangay.gov.ph' => 'Staff',
            'tanod1@barangay.gov.ph' => 'Staff',
            'tanod2@barangay.gov.ph' => 'Staff',
            'tanod3@barangay.gov.ph' => 'Staff',
            'staff1@barangay.gov.ph' => 'Staff',
            'resident@barangay.gov.ph' => 'Resident',
        ];
        foreach ($userRoles as $email => $role) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->syncRoles([$role]);
            }
        }

        // 5. Sample Events (20) - Fixed column names
        $captainId = User::where('email', 'captain@barangay.gov.ph')->first()->id;
        $events = [
            ['Barangay Clean-Up Drive', 'Monthly environmental clean-up along main road', 'Barangay Hall Grounds', '2024-12-15', '08:00:00', '11:00:00', 100, 'Community Service', 'Upcoming'],
            ['Christmas Party 2024', 'Annual family Christmas celebration with games and prizes', 'Barangay Multi-Purpose Hall', '2024-12-24', '17:00:00', '22:00:00', 500, 'Social Event', 'Upcoming'],
            ['Sports Festival', 'Basketball tournament and fun games', 'Barangay Court', '2024-12-28', '09:00:00', '18:00:00', 200, 'Sports', 'Upcoming'],
            ['New Year Countdown', 'Safe and fun countdown celebration', 'Barangay Plaza', '2024-12-31', '22:00:00', '01:00:00', 300, 'Social Event', 'Upcoming'],
            ['Purok Leaders Meeting', 'Monthly coordination meeting', 'Barangay Hall', '2024-12-10', '14:00:00', '16:00:00', 10, 'Meeting', 'Completed'],
            ['Tree Planting Activity', 'Community tree planting drive', 'Barangay Park', '2024-11-20', '07:00:00', '12:00:00', 50, 'Community Service', 'Completed'],
            ['Health Seminar', 'Free medical consultation and seminar', 'Barangay Health Center', '2024-11-15', '09:00:00', '13:00:00', 100, 'Health', 'Completed'],
            ['Livelihood Training', 'Sari-sari store management training', 'Barangay Hall', '2024-11-10', '13:00:00', '16:00:00', 30, 'Training', 'Completed'],
            ['Disaster Preparedness Drill', 'Earthquake and fire drill', 'Barangay Grounds', '2024-12-05', '09:00:00', '11:00:00', 150, 'Training', 'Upcoming'],
            ['Pasko de Barangay Rehearsal', 'Christmas program practice', 'Multi-Purpose Hall', '2024-12-18', '18:00:00', '21:00:00', 80, 'Cultural', 'Upcoming'],
        ];
        foreach ($events as $e) {
            Event::updateOrCreate(
                ['title' => $e[0]],
                [
                    'title' => $e[0],
                    'description' => $e[1],
                    'location' => $e[2],
                    'event_date' => $e[3],
                    'start_time' => $e[4],
                    'end_time' => $e[5],
                    'max_participants' => $e[6],
                    'category' => $e[7],
                    'status' => $e[8],
                    'created_by' => $captainId,
                ]
            );
        }

        // 6. Sample Blotters (15) - Manual data
        $blotterUsers = User::whereIn('role', ['Captain', 'Secretary', 'Staff'])->pluck('id')->toArray();
        $randomResidents = Resident::inRandomOrder()->limit(20)->pluck('id')->toArray();
        $blotters = [
            ['BLTR-001-2412', 'Complaint', 'Noise complaint from neighbor playing loud music past 10PM', '2024-12-01', '22:30:00', 'Purok 5', 'Open'],
            ['BLTR-002-2412', 'Incident', 'Stray dog bite reported', '2024-12-03', '16:45:00', 'Purok 3 Street', 'Under Investigation'],
            ['BLTR-003-2412', 'Dispute', 'Neighbor dispute over boundary fence', '2024-11-28', '14:20:00', 'Corner Lot Purok 7', 'Resolved'],
            ['BLTR-004-2412', 'Complaint', 'Public drunkenness and disorderly conduct', '2024-12-04', '02:15:00', 'Sari-sari store area', 'Closed'],
            ['BLTR-005-2412', 'Incident', 'Lost wallet reported', '2024-12-02', '11:30:00', 'Public market', 'Open'],
            ['BLTR-006-2412', 'Complaint', 'Illegal parking blocking pathway', '2024-12-05', '09:15:00', 'Main road Purok 2', 'Open'],
            ['BLTR-007-2412', 'Dispute', 'Water line connection dispute', '2024-11-30', '17:00:00', 'Purok 8', 'Under Investigation'],
        ];
        foreach ($blotters as $b) {
            Blotter::create([
                'blotter_number' => $b[0],
                'type' => $b[1],
                'description' => $b[2],
                'incident_date' => $b[3],
                'incident_time' => $b[4],
                'incident_location' => $b[5],
                'status' => $b[6],
                'reported_by' => $blotterUsers[array_rand($blotterUsers)],
            ]);
        }

        // 7. Document Requests (50)
        $docTypes = DocumentType::pluck('id')->toArray();
        $residents = Resident::pluck('id')->toArray();
        $residentUser = User::where('email', 'resident@barangay.gov.ph')->first();
        if ($residentUser && empty($residentUser->resident_id) && !empty($residents)) {
            $residentUser->update(['resident_id' => $residents[0]]);
        }

        $this->call(AdultResidentUserSeeder::class);

        for ($i = 0; $i < 50; $i++) {
            DocumentRequest::create([
                'resident_id' => $residents[array_rand($residents)],
                'document_type_id' => $docTypes[array_rand($docTypes)],
                'request_number' => 'DOC-' . now()->format('ymd') . '-' . strtoupper(substr(uniqid(), -6)) . '-' . $i,
                'status' => ['Pending', 'Approved', 'Released', 'Rejected'][array_rand(['Pending', 'Approved', 'Released', 'Rejected'])],
                'purpose' => ['Employment', 'School requirement', 'Medical assistance', 'Business permit', 'Scholarship application'][array_rand(['Employment', 'School requirement', 'Medical assistance', 'Business permit', 'Scholarship application'])],
                'date_requested' => Carbon::now()->subDays(rand(0, 60)),
            ]);
        }

        // 8. Payments (40)
        $docRequests = DocumentRequest::whereIn('status', ['Approved', 'Released'])->doesntHave('payment')->pluck('id')->toArray();
        for ($i = 0; $i < 40; $i++) {
            if (!empty($docRequests)) {
                $requestId = $docRequests[array_rand($docRequests)];
                Payment::create([
                    'document_request_id' => $requestId,
                    'or_number' => 'OR-' . now()->format('ymd') . '-' . str_pad((string) ($i + 1), 5, '0', STR_PAD_LEFT),
                    'amount' => rand(25, 200) + (rand(0, 99) / 100),
                    'payment_method' => ['Cash', 'GCash', 'PayMaya'][rand(0, 2)],
                    'payment_date' => Carbon::now()->subDays(rand(0, 45)),
                    'received_by' => $captainId,
                ]);
                $docRequests = array_values(array_diff($docRequests, [$requestId]));
            }
        }

        // 9. Event Participants (30)
        $eventIds = Event::pluck('id')->toArray();
        for ($i = 0; $i < 30; $i++) {
            EventParticipant::create([
                'resident_id' => $residents[array_rand($residents)],
                'event_id' => $eventIds[array_rand($eventIds)],
                'attendance_status' => ['Registered', 'Attended', 'Absent'][rand(0,2)],
            ]);
        }

        echo "✅ SEEDING COMPLETE!\n";
        echo "• 1378 Residents ✓ | 10 Puroks ✓ | 300 Households ✓\n";
        echo "• 6 Document Types | 20 Events | 7 Blotters | 50 Doc Requests | 40 Payments ✓\n";
        echo "Login: captain@barangay.gov.ph / password123\n";
    }
}

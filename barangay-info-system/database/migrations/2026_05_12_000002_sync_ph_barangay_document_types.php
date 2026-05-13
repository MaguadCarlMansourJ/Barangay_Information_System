<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
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

        foreach ($documents as $document) {
            DB::table('document_types')->updateOrInsert(
                ['name' => $document['name']],
                array_merge($document, ['updated_at' => now(), 'created_at' => now()])
            );
        }
    }

    public function down(): void
    {
        DB::table('document_types')
            ->whereIn('name', [
                'Certificate of Good Moral Character',
                'Certificate of Low Income',
                'First Time Job Seeker Certificate',
                'Barangay ID',
                'Community Tax Certificate',
                'VAWC Certification',
            ])
            ->delete();
    }
};

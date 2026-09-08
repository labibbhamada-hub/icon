<?php

namespace Database\Seeders;

use App\Models\Conference;
use App\Models\Participant;
use App\Models\Reviewer;
use App\Models\Topic;
use App\Models\User;
use App\Models\ConferenceRegistrationType;
use App\Models\ConferenceSetting;
use App\Models\ConferenceConfiguration;
use App\Models\ImportantDate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DevelopmentConferenceSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Conference
        |--------------------------------------------------------------------------
        */

        $conference = Conference::create([
            'name' => 'BHAMADA ICON 2026',
            'short_name' => 'ICON',
            'year' => 2026,
            'theme' => 'Advancing Interdisciplinary Research and Innovation for Sustainable Development.',
            'venue' => 'Online Conference',
            'city' => 'Slawi',
            'country' => 'Indonesia',
            'start_date' => '2026-08-27',
            'end_date' => '2026-08-27',
            'abstract_deadline' => '2026-08-13',
            'fullpaper_deadline' => '2026-08-22',
            'registration_deadline' => '2026-08-26',
            'status' => 'submission_open',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Conference Setting
        |--------------------------------------------------------------------------
        */

        ConferenceSetting::create([
            'conference_id' => $conference->id,
            'is_active' => true,
            'registration_enabled' => true,
            'submission_enabled' => true,
            'payment_enabled' => true,
            'review_enabled' => true,
            'certificate_enabled' => true,
            'published' => true,
            'maintenance_mode' => false,
            'review_mode' => 'open',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Conference Configuration
        |--------------------------------------------------------------------------
        */

        ConferenceConfiguration::create([
            'conference_id' => $conference->id,
            'chair_name' => 'Conference Chair',
            'chair_title' => 'Chair of BHAMADA ICON 2026',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Registration Type
        |--------------------------------------------------------------------------
        */

        $registrationType = ConferenceRegistrationType::create([
            'conference_id' => $conference->id,
            'name' => 'Presenter',
            'code' => 'PRESENTER',
            'category' => 'presenter',
            'payment_timing' => 'immediate',
            'fee' => 500000,
            'included_papers' => 1,
            'additional_paper_fee' => 250000,
            'currency' => 'IDR',
            'description' => 'Development registration type for conference presenter testing.',
            'benefits' => 'Abstract submission, full paper submission, presentation.',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Topic
        |--------------------------------------------------------------------------
        */

        $topic = Topic::create([
            'conference_id' => $conference->id,
            'name' => 'Digital Technology, Artificial Intelligence and Smart Systems',
            'description' => 'Development topic for submission workflow testing.',
            'icon' => 'bi-cpu',
            'color' => 'primary',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Important Dates
        |--------------------------------------------------------------------------
        */

        ImportantDate::create([
            'conference_id' => $conference->id,
            'title' => 'Abstract Submission',
            'type' => 'abstract_submission',
            'description' => 'Abstract submission period.',
            'date' => '2026-07-29',
            'end_date' => '2026-08-13',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        ImportantDate::create([
            'conference_id' => $conference->id,
            'title' => 'Full Paper Submission',
            'type' => 'full_paper_submission',
            'description' => 'Full paper submission period.',
            'date' => '2026-08-16',
            'end_date' => '2026-08-22',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        ImportantDate::create([
            'conference_id' => $conference->id,
            'title' => 'Conference',
            'type' => 'conference',
            'description' => 'Main conference.',
            'date' => '2026-08-27',
            'end_date' => '2026-08-27',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Participant User
        |--------------------------------------------------------------------------
        */

        $participantUser = User::create([
            'name' => 'Test Participant',
            'email' => 'participant@icon2026.test',
            'password' => Hash::make('password'),
            'role' => 'participant',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Participant
        |--------------------------------------------------------------------------
        */

        Participant::create([
            'user_id' => $participantUser->id,
            'conference_id' => $conference->id,
            'registration_type_id' => $registrationType->id,
            'registration_number' => 'ICON2026-TEST-001',
            'full_name' => 'Test Participant',
            'email' => 'participant@icon2026.test',
            'phone' => '081234567890',
            'institution' => 'Universitas Bhamada',
            'department' => 'Information Technology',
            'country' => 'Indonesia',
            'city' => 'Slawi',
            'participant_type' => 'presenter',
            'attendance_type' => 'online',
            'registration_status' => 'confirmed',
            'registered_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Reviewer User
        |--------------------------------------------------------------------------
        */

        $reviewerUser = User::create([
            'name' => 'Test Reviewer',
            'email' => 'reviewer@icon2026.test',
            'password' => Hash::make('password'),
            'role' => 'reviewer',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Reviewer
        |--------------------------------------------------------------------------
        */

        Reviewer::create([
            'conference_id' => $conference->id,
            'user_id' => $reviewerUser->id,
            'expertise' => 'Artificial Intelligence, Digital Technology, Smart Systems',
            'institution' => 'Universitas Bhamada',
            'bio' => 'Development reviewer for workflow testing.',
            'is_active' => true,
        ]);
    }
}

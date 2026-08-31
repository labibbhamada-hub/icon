<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_required_tables_exist_after_migration(): void
    {
        $tables = [
            'conferences',
            'conference_settings',
            'users',
            'profiles',
            'registrations',
            'topics',
            'speakers',
            'partners',
            'important_dates',
            'submissions',
            'submission_authors',
            'reviewers',
            'reviews',
            'participants',
            'payments',
            'certificates',
            'announcements',
            'contact_messages',
            'conference_configurations',
            'jobs',
            'failed_jobs',
            'sessions',
            'notifications',
            'conference_registration_types',
            'conference_attendance_options',
            'conference_payment_methods',
            'conference_presentation_prices',
            'conference_online_meetings',
        ];

        foreach ($tables as $table) {
            $this->assertTrue(
                Schema::hasTable($table),
                "Table [{$table}] does not exist."
            );
        }
    }
}

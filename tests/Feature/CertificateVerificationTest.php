<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CertificateVerificationTest extends TestCase
{
    use RefreshDatabase;

    private int $participantId;

    private int $conferenceId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->conferenceId = DB::table('conferences')->insertGetId([
            'name' => 'ICON 2026',
            'short_name' => 'ICON',
            'year' => 2026,
            'theme' => 'Innovation and Technology',
            'venue' => 'Test Venue',
            'city' => 'Semarang',
            'country' => 'Indonesia',
            'start_date' => '2026-11-01',
            'end_date' => '2026-11-01',
            'status' => 'registration_open',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->participantId = DB::table('participants')->insertGetId([
            'user_id' => null,
            'conference_id' => $this->conferenceId,
            'registration_type_id' => null,
            'registration_number' => 'TEST-CERT-001',
            'full_name' => 'Certificate Test Participant',
            'email' => 'certificate.test@example.com',
            'phone' => '+628123456789',
            'institution' => 'Test University',
            'department' => 'Informatics',
            'country' => 'Indonesia',
            'city' => 'Semarang',
            'participant_type' => 'regular',
            'attendance_type' => 'offline',
            'registration_status' => 'confirmed',
            'notes' => null,
            'registered_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('certificates')->insert([
            'participant_id' => $this->participantId,
            'conference_id' => $this->conferenceId,
            'submission_id' => null,
            'certificate_number' => 'CERT-ICON-2026-ABC123',
            'type' => 'participant',
            'file_path' => 'certificates/CERT-ICON-2026-ABC123.pdf',
            'issued_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_valid_certificate_number_is_found(): void
    {
        $response = $this->get(
            '/certificate/verify?certificate_number=' .
                urlencode('cert-icon-2026-abc123')
        );

        $response->assertSuccessful();

        $response->assertViewHas(
            'searched',
            true
        );

        $response->assertViewHas(
            'certificate',
            fn($certificate) =>
            $certificate !== null
                && $certificate->certificate_number === 'CERT-ICON-2026-ABC123'
                && $certificate->participant?->full_name === 'Certificate Test Participant'
        );
    }

    public function test_invalid_certificate_number_is_not_found(): void
    {
        $response = $this->get(
            '/certificate/verify?certificate_number=' .
                urlencode('CERT-ICON-2026-NOTFOUND')
        );

        $response->assertSuccessful();

        $response->assertViewHas(
            'searched',
            true
        );

        $response->assertViewHas(
            'certificate',
            null
        );
    }

    public function test_certificate_number_without_search_returns_verification_page(): void
    {
        $response = $this->get('/certificate/verify');

        $response->assertSuccessful();

        $response->assertViewHas(
            'searched',
            false
        );

        $response->assertViewHas(
            'certificate',
            null
        );
    }

    public function test_unissued_certificate_is_not_valid(): void
    {
        DB::table('certificates')->insert([
            'participant_id' => $this->participantId,
            'conference_id' => $this->conferenceId,
            'submission_id' => null,
            'certificate_number' => 'CERT-ICON-2026-UNISSUED',
            'type' => 'participant',
            'file_path' => null,
            'issued_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->get(
            '/certificate/verify?certificate_number=' .
                urlencode('CERT-ICON-2026-UNISSUED')
        );

        $response->assertSuccessful();

        $response->assertViewHas(
            'searched',
            true
        );

        $response->assertViewHas(
            'certificate',
            null
        );
    }
}

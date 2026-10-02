<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\Conference;
use App\Models\ConferenceAttendance;
use App\Models\ConferenceAttendanceOption;
use App\Models\ConferenceConfiguration;
use App\Models\ConferencePaymentMethod;
use App\Models\ConferenceRegistrationType;
use App\Models\ConferenceSetting;
use App\Models\Participant;
use App\Models\Partner;
use App\Models\Payment;
use App\Models\Speaker;
use App\Models\Submission;
use App\Models\SubmissionAuthor;
use App\Models\Topic;
use App\Models\User;
use App\Services\CertificateGenerationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StorageLifecycleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Storage::fake('local');
    }

    private function createAdmin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }

    private function createParticipantUser(): User
    {
        return User::factory()->create([
            'role' => 'participant',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }

    private function createConference(
        bool $withSetting = true
    ): Conference {
        $conference = Conference::create([
            'name' => 'Storage Test Conference',
            'short_name' => 'STORAGE',
            'year' => 2026,
            'theme' => 'Storage Lifecycle Test',
            'venue' => 'Online Conference',
            'city' => 'Slawi',
            'country' => 'Indonesia',
            'start_date' => '2026-12-20',
            'end_date' => '2026-12-20',
            'abstract_deadline' => '2026-12-10',
            'fullpaper_deadline' => '2026-12-15',
            'registration_deadline' => '2026-12-31',
            'status' => 'registration_open',
        ]);

        if ($withSetting) {
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
        }

        return $conference;
    }

    private function createPresenterRegistrationType(
        Conference $conference
    ): ConferenceRegistrationType {
        return ConferenceRegistrationType::create([
            'conference_id' => $conference->id,
            'name' => 'Presenter Storage Test',
            'code' => 'PRESENTER-STORAGE-' . uniqid(),
            'category' => 'presenter',
            'fee' => 250000,
            'currency' => 'IDR',
            'included_papers' => 1,
            'additional_paper_fee' => 0,
            'payment_timing' => 'immediate',
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }

    private function createPresenterParticipant(
        Conference $conference,
        ConferenceRegistrationType $registrationType,
        ?User $user = null
    ): Participant {
        $user ??= $this->createParticipantUser();

        return Participant::create([
            'user_id' => $user->id,
            'conference_id' => $conference->id,
            'registration_type_id' => $registrationType->id,
            'registration_number' => 'REG-STORAGE-' . uniqid(),
            'full_name' => $user->name,
            'email' => $user->email,
            'phone' => '081234567890',
            'institution' => 'Storage Test University',
            'department' => 'IT',
            'country' => 'Indonesia',
            'city' => 'Slawi',
            'participant_type' => 'presenter',
            'attendance_type' => 'online',
            'registration_status' => 'pending',
            'registered_at' => now(),
        ]);
    }

    private function createPaymentMethod(
        Conference $conference
    ): ConferencePaymentMethod {
        return ConferencePaymentMethod::create([
            'conference_id' => $conference->id,
            'type' => 'bank_transfer',
            'name' => 'Bank Transfer',
            'provider' => 'BRI',
            'account_number' => '1234567890',
            'account_name' => 'Storage Test Conference',
            'currency' => 'IDR',
            'instructions' => 'Storage lifecycle test.',
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }

    private function listenAndThrow(
        string $event
    ): void {
        Event::listen(
            $event,
            function () {
                throw new \RuntimeException(
                    'Intentional database failure for storage lifecycle test.'
                );
            }
        );
    }

    private function forgetEvent(
        string $event
    ): void {
        Event::forget($event);
    }

    private function createVerifiedAttendance(
        Conference $conference,
        Participant $participant
    ): ConferenceAttendance {
        $admin = $this->createAdmin();

        return ConferenceAttendance::create([
            'conference_id' => $conference->id,
            'participant_id' => $participant->id,
            'attendance_status' => 'verified',
            'checked_in_at' => now(),
            'verified_at' => now(),
            'verified_by' => $admin->id,
        ]);
    }

    public function test_conference_update_failure_keeps_old_files_and_cleans_new_files(): void
    {
        $conference = $this->createConference();

        $oldLogo = 'conference/logo/old-logo.png';
        $oldBanner = 'conference/banner/old-banner.png';

        Storage::disk('public')->put(
            $oldLogo,
            'OLD LOGO'
        );

        Storage::disk('public')->put(
            $oldBanner,
            'OLD BANNER'
        );

        $conference->update([
            'logo' => $oldLogo,
            'banner' => $oldBanner,
        ]);

        $event = 'eloquent.updating: ' . Conference::class;

        $this->listenAndThrow($event);

        try {
            $response = $this
                ->actingAs($this->createAdmin())
                ->put(
                    route(
                        'admin.conferences.update',
                        $conference
                    ),
                    [
                        'name' => 'Storage Test Conference Updated',
                        'short_name' => 'STORAGE',
                        'year' => '2026',
                        'theme' => 'Storage Lifecycle Test',
                        'venue' => 'Online Conference',
                        'city' => 'Slawi',
                        'country' => 'Indonesia',
                        'start_date' => '2026-12-20',
                        'end_date' => '2026-12-20',
                        'abstract_deadline' => '2026-12-10',
                        'fullpaper_deadline' => '2026-12-15',
                        'registration_deadline' => '2026-12-31',
                        'status' => 'registration_open',
                        'logo' => UploadedFile::fake()->image(
                            'new-logo.png'
                        ),
                        'banner' => UploadedFile::fake()->image(
                            'new-banner.png'
                        ),
                    ]
                );

            $response->assertStatus(500);
        } finally {
            $this->forgetEvent($event);
        }

        Storage::disk('public')->assertExists(
            $oldLogo
        );

        Storage::disk('public')->assertExists(
            $oldBanner
        );

        $this->assertCount(
            1,
            Storage::disk('public')->allFiles(
                'conference/logo'
            )
        );

        $this->assertCount(
            1,
            Storage::disk('public')->allFiles(
                'conference/banner'
            )
        );

        $this->assertSame(
            $oldLogo,
            $conference->fresh()->logo
        );

        $this->assertSame(
            $oldBanner,
            $conference->fresh()->banner
        );
    }

    public function test_conference_update_success_removes_old_files(): void
    {
        $conference = $this->createConference();

        $oldLogo = 'conference/logo/old-logo.png';
        $oldBanner = 'conference/banner/old-banner.png';

        Storage::disk('public')->put(
            $oldLogo,
            'OLD LOGO'
        );

        Storage::disk('public')->put(
            $oldBanner,
            'OLD BANNER'
        );

        $conference->update([
            'logo' => $oldLogo,
            'banner' => $oldBanner,
        ]);

        $this
            ->actingAs($this->createAdmin())
            ->put(
                route(
                    'admin.conferences.update',
                    $conference
                ),
                [
                    'name' => 'Storage Test Conference Updated',
                    'short_name' => 'STORAGE',
                    'year' => '2026',
                    'theme' => 'Storage Lifecycle Test',
                    'venue' => 'Online Conference',
                    'city' => 'Slawi',
                    'country' => 'Indonesia',
                    'start_date' => '2026-12-20',
                    'end_date' => '2026-12-20',
                    'abstract_deadline' => '2026-12-10',
                    'fullpaper_deadline' => '2026-12-15',
                    'registration_deadline' => '2026-12-31',
                    'status' => 'registration_open',
                    'logo' => UploadedFile::fake()->image(
                        'new-logo.png'
                    ),
                    'banner' => UploadedFile::fake()->image(
                        'new-banner.png'
                    ),
                ]
            )
            ->assertRedirect();

        $conference->refresh();

        Storage::disk('public')->assertMissing(
            $oldLogo
        );

        Storage::disk('public')->assertMissing(
            $oldBanner
        );

        Storage::disk('public')->assertExists(
            $conference->logo
        );

        Storage::disk('public')->assertExists(
            $conference->banner
        );
    }

    public function test_conference_delete_cleans_child_files(): void
    {
        $conference = $this->createConference();

        $configuration = ConferenceConfiguration::create([
            'conference_id' => $conference->id,
            'logo' => 'conference-configurations/logos/conference-logo.png',
            'signature_file' => 'conference-configurations/signatures/signature.png',
            'chair_name' => 'Storage Test Chair',
            'chair_title' => 'Chair',
        ]);

        $paymentMethod = $this->createPaymentMethod(
            $conference
        );

        $paymentMethod->update([
            'qr_code_file' =>
            'conference-payment-methods/qr-codes/test-qr.png',
        ]);

        $partner = Partner::create([
            'conference_id' => $conference->id,
            'name' => 'Storage Test Partner',
            'type' => 'sponsor',
            'description' => 'Test partner.',
            'logo' => 'partners/test-partner.png',
            'website' => null,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $speaker = Speaker::create([
            'conference_id' => $conference->id,
            'name' => 'Storage Test Speaker',
            'title' => null,
            'institution' => 'Storage Test University',
            'position' => 'Speaker',
            'bio' => 'Test speaker.',
            'photo' => 'speakers/test-speaker.png',
            'email' => null,
            'linkedin' => null,
            'website' => null,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $conferenceLogo = 'conference/logo/conference-logo.png';
        $conferenceBanner = 'conference/banner/conference-banner.png';

        Storage::disk('public')->put(
            $conferenceLogo,
            'CONFERENCE LOGO'
        );

        Storage::disk('public')->put(
            $conferenceBanner,
            'CONFERENCE BANNER'
        );

        Storage::disk('public')->put(
            $configuration->logo,
            'CONFIGURATION LOGO'
        );

        Storage::disk('public')->put(
            $configuration->signature_file,
            'SIGNATURE'
        );

        Storage::disk('public')->put(
            $paymentMethod->qr_code_file,
            'QR'
        );

        Storage::disk('public')->put(
            $partner->logo,
            'PARTNER'
        );

        Storage::disk('public')->put(
            $speaker->photo,
            'SPEAKER'
        );

        $conference->update([
            'logo' => $conferenceLogo,
            'banner' => $conferenceBanner,
        ]);

        $admin = $this->createAdmin();

        $this
            ->actingAs($admin)
            ->delete(
                route(
                    'admin.conferences.destroy',
                    $conference
                )
            )
            ->assertRedirect(
                route('admin.conferences.index')
            );

        Storage::disk('public')->assertMissing(
            $conferenceLogo
        );

        Storage::disk('public')->assertMissing(
            $conferenceBanner
        );

        Storage::disk('public')->assertMissing(
            $configuration->logo
        );

        Storage::disk('public')->assertMissing(
            $configuration->signature_file
        );

        Storage::disk('public')->assertMissing(
            $paymentMethod->qr_code_file
        );

        Storage::disk('public')->assertMissing(
            $partner->logo
        );

        Storage::disk('public')->assertMissing(
            $speaker->photo
        );

        $this->assertDatabaseMissing(
            'conferences',
            [
                'id' => $conference->id,
            ]
        );
    }

    public function test_conference_configuration_update_failure_keeps_old_file(): void
    {
        $conference = $this->createConference();

        $oldLogo =
            'conference-configurations/logos/old-logo.png';

        Storage::disk('public')->put(
            $oldLogo,
            'OLD CONFIG LOGO'
        );

        $configuration = ConferenceConfiguration::create([
            'conference_id' => $conference->id,
            'logo' => $oldLogo,
            'signature_file' => null,
            'chair_name' => 'Old Chair',
            'chair_title' => 'Old Title',
        ]);

        $event = 'eloquent.updating: ' .
            ConferenceConfiguration::class;

        $this->listenAndThrow($event);

        try {
            $response = $this
                ->actingAs($this->createAdmin())
                ->put(
                    route(
                        'admin.conferences.configuration.update',
                        $conference
                    ),
                    [
                        'chair_name' => 'New Chair',
                        'chair_title' => 'New Title',
                        'logo' => UploadedFile::fake()->image(
                            'new-logo.png'
                        ),
                    ]
                );

            $response->assertStatus(500);
        } finally {
            $this->forgetEvent($event);
        }

        Storage::disk('public')->assertExists(
            $oldLogo
        );

        $this->assertCount(
            1,
            Storage::disk('public')->allFiles(
                'conference-configurations/logos'
            )
        );

        $this->assertSame(
            $oldLogo,
            $configuration->fresh()->logo
        );
    }

    public function test_conference_configuration_update_success_removes_old_logo(): void
    {
        $conference = $this->createConference();

        $oldLogo =
            'conference-configurations/logos/old-logo.png';

        Storage::disk('public')->put(
            $oldLogo,
            'OLD CONFIG LOGO'
        );

        $configuration = ConferenceConfiguration::create([
            'conference_id' => $conference->id,
            'logo' => $oldLogo,
            'signature_file' => null,
            'chair_name' => 'Old Chair',
            'chair_title' => 'Old Title',
        ]);

        $this
            ->actingAs($this->createAdmin())
            ->put(
                route(
                    'admin.conferences.configuration.update',
                    $conference
                ),
                [
                    'chair_name' => 'New Chair',
                    'chair_title' => 'New Title',
                    'logo' => UploadedFile::fake()->image(
                        'new-logo.png'
                    ),
                ]
            )
            ->assertRedirect();

        $configuration->refresh();

        Storage::disk('public')->assertMissing(
            $oldLogo
        );

        Storage::disk('public')->assertExists(
            $configuration->logo
        );
    }

    public function test_payment_method_update_failure_keeps_old_qr(): void
    {
        $conference = $this->createConference();

        $oldQr =
            'conference-payment-methods/qr-codes/old-qr.png';

        Storage::disk('public')->put(
            $oldQr,
            'OLD QR'
        );

        $paymentMethod = $this->createPaymentMethod(
            $conference
        );

        $paymentMethod->update([
            'qr_code_file' => $oldQr,
        ]);

        $event = 'eloquent.updating: ' .
            ConferencePaymentMethod::class;

        $this->listenAndThrow($event);

        try {
            $response = $this
                ->actingAs($this->createAdmin())
                ->put(
                    route(
                        'admin.conferences.payment-methods.update',
                        [
                            $conference,
                            $paymentMethod,
                        ]
                    ),
                    [
                        'type' => 'bank_transfer',
                        'name' => 'Bank Transfer',
                        'provider' => 'BRI',
                        'account_number' => '1234567890',
                        'account_name' => 'Storage Test Conference',
                        'currency' => 'IDR',
                        'instructions' => 'Updated instructions.',
                        'is_active' => true,
                        'sort_order' => 1,
                        'qr_code_file' => UploadedFile::fake()->image(
                            'new-qr.png'
                        ),
                    ]
                );

            $response->assertStatus(500);
        } finally {
            $this->forgetEvent($event);
        }

        Storage::disk('public')->assertExists(
            $oldQr
        );

        $this->assertCount(
            1,
            Storage::disk('public')->allFiles(
                'conference-payment-methods/qr-codes'
            )
        );

        $this->assertSame(
            $oldQr,
            $paymentMethod->fresh()->qr_code_file
        );
    }

    public function test_partner_update_failure_keeps_old_logo(): void
    {
        $conference = $this->createConference();

        $oldLogo = 'partners/old-partner.png';

        Storage::disk('public')->put(
            $oldLogo,
            'OLD PARTNER'
        );

        $partner = Partner::create([
            'conference_id' => $conference->id,
            'name' => 'Storage Partner',
            'type' => 'sponsor',
            'description' => 'Test partner.',
            'logo' => $oldLogo,
            'website' => null,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $event = 'eloquent.updating: ' . Partner::class;

        $this->listenAndThrow($event);

        try {
            $response = $this
                ->actingAs($this->createAdmin())
                ->put(
                    route(
                        'admin.partners.update',
                        $partner
                    ),
                    [
                        'conference_id' => $conference->id,
                        'name' => 'Updated Partner',
                        'type' => 'sponsor',
                        'description' => 'Updated description.',
                        'website' => null,
                        'sort_order' => 1,
                        'is_active' => true,
                        'logo' => UploadedFile::fake()->image(
                            'new-partner.png'
                        ),
                    ]
                );

            $response->assertStatus(500);
        } finally {
            $this->forgetEvent($event);
        }

        Storage::disk('public')->assertExists(
            $oldLogo
        );

        $this->assertCount(
            1,
            Storage::disk('public')->allFiles(
                'partners'
            )
        );

        $this->assertSame(
            $oldLogo,
            $partner->fresh()->logo
        );
    }

    public function test_speaker_update_failure_keeps_old_photo(): void
    {
        $conference = $this->createConference();

        $oldPhoto = 'speakers/old-speaker.png';

        Storage::disk('public')->put(
            $oldPhoto,
            'OLD SPEAKER'
        );

        $speaker = Speaker::create([
            'conference_id' => $conference->id,
            'name' => 'Storage Speaker',
            'title' => null,
            'institution' => 'Storage University',
            'position' => 'Speaker',
            'bio' => 'Test speaker.',
            'photo' => $oldPhoto,
            'email' => null,
            'linkedin' => null,
            'website' => null,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $event = 'eloquent.updating: ' . Speaker::class;

        $this->listenAndThrow($event);

        try {
            $response = $this
                ->actingAs($this->createAdmin())
                ->put(
                    route(
                        'admin.speakers.update',
                        $speaker
                    ),
                    [
                        'conference_id' => $conference->id,
                        'name' => 'Updated Speaker',
                        'title' => null,
                        'institution' => 'Storage University',
                        'position' => 'Updated Position',
                        'bio' => 'Updated speaker.',
                        'email' => null,
                        'linkedin' => null,
                        'website' => null,
                        'sort_order' => 1,
                        'is_active' => true,
                        'photo' => UploadedFile::fake()->image(
                            'new-speaker.png'
                        ),
                    ]
                );

            $response->assertStatus(500);
        } finally {
            $this->forgetEvent($event);
        }

        Storage::disk('public')->assertExists(
            $oldPhoto
        );

        $this->assertCount(
            1,
            Storage::disk('public')->allFiles(
                'speakers'
            )
        );

        $this->assertSame(
            $oldPhoto,
            $speaker->fresh()->photo
        );
    }

    public function test_participant_payment_store_failure_cleans_new_proof_file(): void
    {
        $conference = $this->createConference();

        ConferenceAttendanceOption::create([
            'conference_id' => $conference->id,
            'type' => 'online',
            'sort_order' => 1,
        ]);

        $registrationType =
            $this->createPresenterRegistrationType(
                $conference
            );

        $paymentMethod =
            $this->createPaymentMethod(
                $conference
            );

        $user = $this->createParticipantUser();

        $participant =
            $this->createPresenterParticipant(
                $conference,
                $registrationType,
                $user
            );

        $event = 'eloquent.creating: ' . Payment::class;

        $this->listenAndThrow($event);

        try {
            $response = $this
                ->actingAs($user)
                ->post(
                    route('participant.payments.store'),
                    [
                        'participant_id' =>
                        $participant->id,

                        'payment_method_id' =>
                        $paymentMethod->id,

                        'proof_file' =>
                        UploadedFile::fake()->create(
                            'payment-proof.pdf',
                            100,
                            'application/pdf'
                        ),

                        'paid_at' =>
                        now()->format(
                            'Y-m-d H:i:s'
                        ),

                        'notes' =>
                        'Storage lifecycle failure test.',
                    ]
                );

            $response->assertStatus(500);
        } finally {
            $this->forgetEvent($event);
        }

        $this->assertDatabaseMissing(
            'payments',
            [
                'participant_id' =>
                $participant->id,
            ]
        );

        $this->assertEmpty(
            Storage::disk('local')->allFiles(
                'payments/proofs'
            )
        );
    }

    public function test_admin_payment_delete_failure_keeps_proof_file(): void
    {
        $conference = $this->createConference();

        $registrationType =
            $this->createPresenterRegistrationType(
                $conference
            );

        $participant =
            $this->createPresenterParticipant(
                $conference,
                $registrationType
            );

        $paymentMethod =
            $this->createPaymentMethod(
                $conference
            );

        $proofFile =
            'payments/proofs/admin-delete-test.pdf';

        Storage::disk('local')->put(
            $proofFile,
            'PAYMENT PROOF'
        );

        $payment = Payment::create([
            'participant_id' => $participant->id,
            'payment_method_id' => $paymentMethod->id,
            'payment_code' => 'PAY-STORAGE-DELETE',
            'amount' => 250000,
            'proof_file' => $proofFile,
            'status' => 'pending',
            'paid_at' => now(),
        ]);

        $event = 'eloquent.deleting: ' . Payment::class;

        $this->listenAndThrow($event);

        try {
            $response = $this
                ->actingAs($this->createAdmin())
                ->delete(
                    route(
                        'admin.payments.destroy',
                        $payment
                    )
                );

            $response->assertStatus(500);
        } finally {
            $this->forgetEvent($event);
        }

        $this->assertDatabaseHas(
            'payments',
            [
                'id' => $payment->id,
            ]
        );

        Storage::disk('local')->assertExists(
            $proofFile
        );
    }

    public function test_admin_submission_update_failure_keeps_old_file(): void
    {
        $conference = $this->createConference();

        $registrationType =
            $this->createPresenterRegistrationType(
                $conference
            );

        $participant =
            $this->createPresenterParticipant(
                $conference,
                $registrationType
            );

        $topic = Topic::create([
            'conference_id' => $conference->id,
            'name' => 'Storage Test Topic',
            'description' => 'Storage test topic.',
            'icon' => 'bi-cpu',
            'color' => 'primary',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $oldFile =
            'submissions/papers/old-admin-paper.pdf';

        Storage::disk('local')->put(
            $oldFile,
            'OLD PAPER'
        );

        $submission = Submission::create([
            'conference_id' => $conference->id,
            'participant_id' => $participant->id,
            'topic_id' => $topic->id,
            'submission_code' => 'STORAGE-SUB-' . uniqid(),
            'title' => 'Storage Test Submission',
            'abstract' => 'Storage test abstract.',
            'keywords' => 'storage, test',
            'submission_stage' => 'full_paper',
            'status' => 'submitted',
            'paper_file' => $oldFile,
            'submitted_at' => now(),
        ]);

        SubmissionAuthor::create([
            'submission_id' => $submission->id,
            'name' => $participant->full_name,
            'email' => $participant->email,
            'institution' => $participant->institution,
            'is_corresponding' => true,
            'sort_order' => 1,
        ]);

        $event = 'eloquent.updating: ' . Submission::class;

        $this->listenAndThrow($event);

        try {
            $response = $this
                ->actingAs($this->createAdmin())
                ->put(
                    route(
                        'admin.submissions.update',
                        $submission
                    ),
                    [
                        'conference_id' =>
                        $conference->id,

                        'participant_id' =>
                        $participant->id,

                        'topic_id' =>
                        $topic->id,

                        'title' =>
                        'Updated Storage Test Submission',

                        'abstract' =>
                        'Updated storage test abstract.',

                        'keywords' =>
                        'storage, test, updated',

                        'status' =>
                        'submitted',

                        'submitted_at' =>
                        now()->format(
                            'Y-m-d H:i:s'
                        ),

                        'paper_file' =>
                        UploadedFile::fake()->create(
                            'new-admin-paper.pdf',
                            100,
                            'application/pdf'
                        ),

                        'authors' => [
                            [
                                'name' =>
                                $participant->full_name,

                                'email' =>
                                $participant->email,

                                'institution' =>
                                $participant->institution,

                                'department' =>
                                'IT',

                                'is_corresponding' =>
                                true,

                                'sort_order' =>
                                1,
                            ],
                        ],
                    ]
                );

            $response->assertStatus(500);
        } finally {
            $this->forgetEvent($event);
        }

        Storage::disk('local')->assertExists(
            $oldFile
        );

        $this->assertCount(
            1,
            Storage::disk('local')->allFiles(
                'submissions/papers'
            )
        );

        $this->assertSame(
            $oldFile,
            $submission->fresh()->paper_file
        );
    }

    public function test_certificate_service_generate_pdf_failure_cleans_new_pdf_and_keeps_old_pdf(): void
    {
        $conference = $this->createConference();

        $participant =
            $this->createPresenterParticipant(
                $conference,
                $this->createPresenterRegistrationType(
                    $conference
                )
            );

        $oldFile =
            'certificates/old-service-certificate.pdf';

        Storage::disk('public')->put(
            $oldFile,
            'OLD CERTIFICATE'
        );

        $certificate = Certificate::create([
            'participant_id' => $participant->id,
            'conference_id' => $conference->id,
            'submission_id' => null,
            'certificate_number' => 'CERT-STORAGE-SERVICE',
            'type' => 'participant',
            'issued_at' => now(),
            'file_path' => $oldFile,
        ]);

        $event = 'eloquent.updating: ' . Certificate::class;

        $this->listenAndThrow($event);

        try {
            $this->expectException(
                \RuntimeException::class
            );

            app(
                CertificateGenerationService::class
            )->generatePdf(
                $certificate
            );
        } finally {
            $this->forgetEvent($event);
        }

        Storage::disk('public')->assertExists(
            $oldFile
        );

        $this->assertCount(
            1,
            Storage::disk('public')->allFiles(
                'certificates'
            )
        );

        $this->assertSame(
            $oldFile,
            $certificate->fresh()->file_path
        );
    }

    public function test_certificate_service_removes_record_when_pdf_generation_fails(): void
    {
        $conference = $this->createConference();

        $participant =
            $this->createPresenterParticipant(
                $conference,
                $this->createPresenterRegistrationType(
                    $conference
                )
            );

        $topic = Topic::create([
            'conference_id' => $conference->id,
            'name' => 'Certificate Storage Topic',
            'description' => 'Certificate storage topic.',
            'icon' => 'bi-cpu',
            'color' => 'primary',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $submission = Submission::create([
            'conference_id' => $conference->id,
            'participant_id' => $participant->id,
            'topic_id' => $topic->id,
            'submission_code' => 'CERT-STORAGE-' . uniqid(),
            'title' => 'Certificate Storage Submission',
            'abstract' => 'Certificate storage abstract.',
            'keywords' => 'certificate, storage',
            'submission_stage' => 'full_paper',
            'status' => 'published',
            'submitted_at' => now(),
        ]);

        Pdf::shouldReceive('loadView')
            ->once()
            ->andThrow(
                new \RuntimeException(
                    'Intentional PDF generation failure.'
                )
            );

        $this->expectException(
            \RuntimeException::class
        );

        try {
            app(
                CertificateGenerationService::class
            )->createForSubmission(
                $submission
            );
        } finally {
            $this->assertDatabaseMissing(
                'certificates',
                [
                    'submission_id' =>
                    $submission->id,
                ]
            );

            $this->assertEmpty(
                Storage::disk('public')->allFiles(
                    'certificates'
                )
            );
        }
    }

    public function test_admin_certificate_store_removes_record_when_pdf_generation_fails(): void
    {
        $conference = $this->createConference();

        $user = $this->createParticipantUser();

        $participant = Participant::create([
            'user_id' => $user->id,
            'conference_id' => $conference->id,
            'registration_type_id' => null,
            'registration_number' => 'REG-CERT-STORAGE-' . uniqid(),
            'full_name' => $user->name,
            'email' => $user->email,
            'phone' => '081234567890',
            'institution' => 'Storage Test University',
            'department' => 'IT',
            'country' => 'Indonesia',
            'city' => 'Slawi',
            'participant_type' => 'regular',
            'attendance_type' => 'online',
            'registration_status' => 'confirmed',
            'registered_at' => now(),
        ]);

        $this->createVerifiedAttendance(
            $conference,
            $participant
        );

        Pdf::shouldReceive('loadView')
            ->once()
            ->andThrow(
                new \RuntimeException(
                    'Intentional PDF generation failure.'
                )
            );

        $admin = $this->createAdmin();

        $response = $this
            ->actingAs($admin)
            ->post(
                route('admin.certificates.store'),
                [
                    'participant_id' =>
                    $participant->id,

                    'type' =>
                    'participant',
                ]
            );

        $response->assertStatus(500);

        $this->assertDatabaseMissing(
            'certificates',
            [
                'participant_id' =>
                $participant->id,

                'conference_id' =>
                $conference->id,

                'type' =>
                'participant',
            ]
        );

        $this->assertEmpty(
            Storage::disk('public')->allFiles(
                'certificates'
            )
        );
    }

    public function test_admin_certificate_regenerate_failure_keeps_old_pdf_and_cleans_new_pdf(): void
    {
        $conference = $this->createConference();

        $participant =
            $this->createPresenterParticipant(
                $conference,
                $this->createPresenterRegistrationType(
                    $conference
                )
            );

        $oldFile =
            'certificates/old-admin-certificate.pdf';

        Storage::disk('public')->put(
            $oldFile,
            'OLD ADMIN CERTIFICATE'
        );

        $certificate = Certificate::create([
            'participant_id' =>
            $participant->id,

            'conference_id' =>
            $conference->id,

            'submission_id' =>
            null,

            'certificate_number' =>
            'CERT-STORAGE-ADMIN',

            'type' =>
            'participant',

            'issued_at' =>
            now(),

            'file_path' =>
            $oldFile,
        ]);

        $event = 'eloquent.updating: ' . Certificate::class;

        $this->listenAndThrow($event);

        try {
            $response = $this
                ->actingAs($this->createAdmin())
                ->post(
                    route(
                        'admin.certificates.regenerate',
                        $certificate
                    )
                );

            $response->assertStatus(500);
        } finally {
            $this->forgetEvent($event);
        }

        Storage::disk('public')->assertExists(
            $oldFile
        );

        $this->assertCount(
            1,
            Storage::disk('public')->allFiles(
                'certificates'
            )
        );

        $this->assertSame(
            $oldFile,
            $certificate->fresh()->file_path
        );
    }
}

<?php

namespace Tests\Unit;

use App\Services\FonnteService;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Tests\TestCase;

class FonnteServiceTest extends TestCase
{
    public function test_fonnte_success_returns_true(): void
    {
        config([
            'services.fonnte.token' => 'test-token',
            'services.fonnte.url' => 'https://example.test/send',
        ]);

        Http::fake([
            'https://example.test/send' => Http::response([
                'status' => true,
            ], 200),
        ]);

        $result = app(FonnteService::class)->send(
            '08123456789',
            'Test message'
        );

        $this->assertTrue($result);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://example.test/send'
                && $request->header('Authorization')[0] === 'test-token'
                && $request['target'] === '08123456789'
                && $request['message'] === 'Test message';
        });
    }

    public function test_fonnte_http_failure_throws_exception(): void
    {
        config([
            'services.fonnte.token' => 'test-token',
            'services.fonnte.url' => 'https://example.test/send',
        ]);

        Http::fake([
            'https://example.test/send' => Http::response(
                ['status' => false],
                500
            ),
        ]);

        $this->expectException(RuntimeException::class);

        app(FonnteService::class)->send(
            '08123456789',
            'Test message'
        );
    }

    public function test_fonnte_missing_configuration_throws_exception(): void
    {
        config([
            'services.fonnte.token' => null,
            'services.fonnte.url' => null,
        ]);

        $this->expectException(RuntimeException::class);

        app(FonnteService::class)->send(
            '08123456789',
            'Test message'
        );
    }
}
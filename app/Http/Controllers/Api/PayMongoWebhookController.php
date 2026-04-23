<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayMongoWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        $payload = $request->getContent();
        $signatureHeader = $request->header('Paymongo-Signature');

        // Verify webhook signature
        if (!$this->verifySignature($payload, $signatureHeader)) {
            Log::warning('PayMongo webhook signature verification failed', [
                'ip' => $request->ip(),
            ]);
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $data = $request->json()->all();
        $eventType = $data['data']['attributes']['type'] ?? null;

        if ($eventType === 'checkout_session.payment.paid') {
            $sessionId = $data['data']['attributes']['data']['id'] ?? null;

            if ($sessionId) {
                \App\Jobs\ProcessPayMongoPayment::dispatch($sessionId);
                Log::info('PayMongo webhook received: payment.paid', ['session_id' => $sessionId]);
            }
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Verify PayMongo webhook signature.
     */
    protected function verifySignature(string $payload, ?string $signatureHeader): bool
    {
        if (!$signatureHeader) {
            return false;
        }

        $secret = config('paymongo.webhook_secret');

        // Parse signature header: format is "t=timestamp,te=test_signature,li=live_signature"
        $parts = [];
        parse_str(str_replace(',', '&', $signatureHeader), $parts);

        $signature = $parts['te'] ?? $parts['li'] ?? '';
        $timestamp = $parts['t'] ?? '';

        if (!$signature || !$timestamp) {
            return false;
        }

        $signedPayload = "{$timestamp}.{$payload}";
        $computedSignature = hash_hmac('sha256', $signedPayload, $secret);

        return hash_equals($computedSignature, $signature);
    }
}
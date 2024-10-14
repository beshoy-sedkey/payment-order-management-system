<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Webhook\PayPalWebhookService;

class WebhookController extends Controller
{
    public function handleWebhook(Request $request, PayPalWebhookService $payPalWebhookService)
    {
        try {
            $payload = $request->all();
            Log::info('PayPal Webhook received', $payload);
            $payPalWebhookService->processWebhook($payload);
            return response()->json(['message' => 'Webhook processed successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Error processing PayPal webhook: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}

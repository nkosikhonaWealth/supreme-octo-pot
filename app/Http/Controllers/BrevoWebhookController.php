<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParticipantMessageLog;
use Illuminate\Support\Facades\Log;

class BrevoWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Brevo Webhook', $request->all());

        $event = $request->input('event');
        $email = $request->input('email');
        $reason = $request->input('reason') ?? null;
        $date = now();

        $log = ParticipantMessageLog::where('email', $email)->latest()->first();

        if (!$log) {
            Log::warning("No log found for email: {$email}");
            return response()->json(['status' => 'Test Successful'], 405);
        }

        switch ($event) {
            case 'hard_bounce':
            case 'soft_bounce':
            case 'blocked':
            case 'error':
            case 'deferred':
            case 'unsubscribed':
            case 'invalid_email':
                $log->update([
                    'status' => 'Failed',
                    'error_message' => $reason,
                ]);
                break;

            case 'sent':
                $log->update([
                    'status' => 'Sent',
                    'updated_at' => $date,
                ]);
                break;

            case 'delivered':
                $log->update([
                    'status' => 'Delivered',
                    'error_message' => 'Delivered Successfully',
                    'updated_at' => $date,
                ]);
                break;

            case 'unique_opened':
                $log->update([
                    'status' => 'Opened',
                    'error_message' => 'Email Was Opened',
                    'updated_at' => $date,
                ]);
                break;
        }

        return response()->json(['status' => 'received'], 200);
    }

}

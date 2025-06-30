<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\BookingSession;
use App\Models\Tutor;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing booking sessions with null payment methods
        $sessionsWithoutPayment = BookingSession::whereNull('payment_method')->get();
        
        foreach ($sessionsWithoutPayment as $session) {
            $tutor = Tutor::find($session->tutor_id);
            if ($tutor && $tutor->payment_details) {
                // Parse tutor's payment methods
                $paymentMethods = $this->parsePaymentMethods($tutor->payment_details);
                
                // Set the first available payment method as default
                if (!empty($paymentMethods)) {
                    $session->payment_method = $paymentMethods[0];
                    $session->save();
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally revert payment methods to null if needed
        // BookingSession::whereNotNull('payment_method')->update(['payment_method' => null]);
    }

    /**
     * Parse payment methods from payment_details field
     */
    private function parsePaymentMethods($paymentDetails)
    {
        if (empty($paymentDetails)) {
            return [];
        }

        // Try to decode as JSON first
        $decoded = json_decode($paymentDetails, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return array_filter($decoded); // Remove empty values
        }

        // Try comma-separated values
        if (strpos($paymentDetails, ',') !== false) {
            $methods = array_map('trim', explode(',', $paymentDetails));
            return array_filter($methods); // Remove empty values
        }

        // Single payment method as string
        return [trim($paymentDetails)];
    }
};

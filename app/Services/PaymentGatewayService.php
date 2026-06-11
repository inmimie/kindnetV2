<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentGatewayService
{
    /**
     * Available payment gateways with their configurations.
     */
    public static function getAvailableGateways(): array
    {
        return [
            'fpx' => [
                'name' => 'FPX Online Banking',
                'description' => 'Financial Process Exchange — Direct bank transfer melalui online banking. Paling popular di Malaysia.',
                'icon' => 'fpx',
                'color' => 'blue',
                'methods' => [
                    'Maybank2u', 'CIMB Clicks', 'Public Bank', 'RHB Now',
                    'Hong Leong Connect', 'AmOnline', 'Bank Islam', 'Affin Bank',
                    'Bank Rakyat', 'BSN', 'OCBC', 'Standard Chartered',
                    'HSBC', 'Bank Muamalat', 'Agrobank', 'Alliance Bank',
                    'KFH', 'UOB',
                ],
            ],
            'toyyibpay' => [
                'name' => 'ToyyibPay',
                'description' => 'Payment gateway popular Malaysia yang menyokong FPX dan kad kredit/debit.',
                'icon' => 'toyyibpay',
                'color' => 'purple',
                'methods' => [
                    'FPX (via ToyyibPay)', 'Credit Card', 'Debit Card',
                ],
            ],
            'billplz' => [
                'name' => 'Billplz',
                'description' => 'Platform pembayaran bill & invoice yang dipercayai di Malaysia dengan pelbagai kaedah pembayaran.',
                'icon' => 'billplz',
                'color' => 'orange',
                'methods' => [
                    'FPX (via Billplz)', 'Direct Debit', 'PayPal',
                ],
            ],
        ];
    }

    /**
     * Process payment through the selected gateway.
     *
     * @param float $amount
     * @param string $gateway
     * @param string|null $method
     * @param array $details
     * @return array
     */
    public function processPayment(float $amount, string $gateway = 'bank_transfer', ?string $method = null, array $details = []): array
    {
        $prefix = match ($gateway) {
            'fpx' => 'FPX',
            'duitnow' => 'DN',
            'toyyibpay' => 'TYB',
            'billplz' => 'BPZ',
            'bank_transfer' => 'BT',
            default => 'TXN',
        };

        $transactionId = $prefix . '_' . strtoupper(Str::random(12));
        $gatewayReference = strtoupper($gateway) . '-REF-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        Log::info("PAYMENT PROCESSING: RM{$amount} via {$gateway}" . ($method ? " ({$method})" : '') . ". TXN: {$transactionId}");

        // In production, this would call the actual gateway API:
        // - FPX: PayNet FPX API
        // - DuitNow: PayNet DuitNow API
        // - ToyyibPay: toyyibpay.com/apireference
        // - Billplz: www.billplz.com/api
        // - Bank Transfer: Manual processing

        return [
            'success' => true,
            'transaction_id' => $transactionId,
            'gateway_reference' => $gatewayReference,
            'amount' => $amount,
            'status' => 'completed',
            'gateway' => $gateway,
            'method' => $method,
        ];
    }

    /**
     * Validate the selected gateway and method combination.
     */
    public function validateGateway(string $gateway, ?string $method = null): bool
    {
        $gateways = self::getAvailableGateways();

        if (!isset($gateways[$gateway])) {
            return false;
        }

        if ($method && !in_array($method, $gateways[$gateway]['methods'])) {
            return false;
        }

        return true;
    }
}

<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;

class ZarinpalService
{
    protected $merchant_id;
    protected $sandbox;

    public function __construct()
    {
        $this->merchant_id = env('ZARINPAL_MERCHANT_ID', '123456789012345678901234567890123456');
        $this->sandbox = env('ZARINPAL_SANDBOX', true);
    }

    protected function baseUrl(): string
    {
        return $this->sandbox
            ? 'https://sandbox.zarinpal.com/pg/v4/payment'
            : 'https://api.zarinpal.com/pg/v4/payment';
    }

    public function requestPayment($amount, $callback_url, $description = 'Test Payment')
    {
        $options = [];
        if ($this->sandbox) {
            $options['verify'] = false;
        }
        $response = Http::withOptions($options)->post($this->baseUrl() . '/request.json', [
            'merchant_id' => $this->merchant_id,
            'amount' => $amount,
            'callback_url' => $callback_url,
            'description' => $description,
        ]);
        if ($response->failed()) {
            \Log::error('Payment Request Failed', [
                'response' => $response->body()
            ]);
        }

        return $response->json();
    }

    public function verifyPayment($authority, $amount)
    {
        $response = Http::withoutVerifying()->post($this->baseUrl() . '/verify.json', [
            'merchant_id' => $this->merchant_id,
            'amount' => $amount,
            'authority' => $authority,
        ]);

        return $response->json();
    }

    public function getStartPayUrl($authority)
    {
        $url = $this->sandbox
            ? "https://sandbox.zarinpal.com/pg/StartPay/{$authority}"
            : "https://www.zarinpal.com/pg/StartPay/{$authority}";

        return $url;
    }
}


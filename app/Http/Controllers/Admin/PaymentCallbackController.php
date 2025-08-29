<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Model\PaymentLog;
use App\Model\Payments;
use Illuminate\Http\Request;
use App\Services\ZarinpalService;
use Carbon\Carbon;

class PaymentCallbackController extends Controller
{
    protected $zarinpal;

    public function __construct(ZarinpalService $zarinpal)
    {
        $this->zarinpal = $zarinpal;
    }

    public function handle(Request $request, $paymentId)
    {
        $payment = Payments::findOrFail($paymentId);
        $order = $payment->order;

        if ($payment->status !== 'pending') {
            if ($order->status === 'pending') {
                $order->status = 'cancelled';
                $order->save();
            }

            return Response::errorResponse(400,__('api.payment_obsolete'));
        }

        $authority = $request->query('Authority');
        $status = $request->query('Status');

        PaymentLog::create([
            'payment_id' => $payment->id,
            'request_payload' => json_encode($request->all()),
            'response_payload' => json_encode([]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($status === 'OK') {
            $verifyResponse = $this->zarinpal->verifyPayment($authority, $payment->amount);
            $verifyArray = is_array($verifyResponse) ? $verifyResponse : (array) $verifyResponse;

            PaymentLog::create([
                'payment_id' => $payment->id,
                'request_payload' => json_encode(['authority' => $authority]),
                'response_payload' => json_encode($verifyArray ?? []),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (isset($verifyArray['data']['code']) && $verifyArray['data']['code'] == 100) {
                $payment->status = 'success';
                $payment->paid_at = Carbon::now();
                $payment->save();

                $order->payment_status = 'paid';
                $order->status = 'paid';
                $order->save();

                return response()->json([
                    'status' => 'success',
                    'message' => __('api.payment_success'),
                    'ref_id' => $verifyArray['data']['ref_id'] ?? null
                ]);
            } else {
                $payment->status = 'failed';
                $payment->save();

                $order->payment_status = 'unpaid';
                $order->status = 'cancelled';
                $order->save();

                return response()->json([
                    'status' => 'failed',
                    'message' => __('api.payment_error'),
                    'verify_response' => $verifyArray
                ]);
            }
        }

        $payment->status = 'failed';
        $payment->save();

        $order->payment_status = 'unpaid';
        $order->status = 'cancelled';
        $order->save();

        PaymentLog::create([
            'payment_id' => $payment->id,
            'request_payload' => json_encode($request->all()),
            'response_payload' => json_encode([
                'Authority' => $authority,
                'Status' => $status
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return Response::errorResponse(500, __('api.payment_failed'));
    }
}

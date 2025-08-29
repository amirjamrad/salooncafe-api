<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Model\Orders;
use App\Model\PaymentLog;
use App\Model\Payments;
use Illuminate\Http\Request;
use App\Services\ZarinpalService;

use Carbon\Carbon;
use DB;

class PaymentController extends Controller
{
    protected $zarinpal;

    public function __construct(ZarinpalService $zarinpal)
    {
        $this->zarinpal = $zarinpal;
    }

    public function pay(Request $request, $orderId)
    {
        $order = Orders::findOrFail($orderId);

        if ($order->status === 'paid') {
            return Response::errorResponse(400, __('api.payment_paid'));
        }
        if ($order->status === 'cancelled') {
            return Response::errorResponse(400, __('api.payment_cancelled'));
        }


        $payment = Payments::create([
            'order_id' => $order->id,
            'amount' => $order->total_price,
            'gatway' => 'Zarinpal',
            'status' => 'pending',
            'paid_at' => null,
        ]);

        $response = $this->zarinpal->requestPayment(
            $payment->amount,
            route('api.payment.callback', ['payment' => $payment->id]),
            "پرداخت سفارش #{$order->id}"
        );

        $responseArray = is_array($response) ? $response : (array)$response;

        PaymentLog::create([
            'payment_id' => $payment->id,
            'request_payload' => json_encode([
                'amount' => $payment->amount,
                'order_id' => $order->id
            ], JSON_PRETTY_PRINT),
            'response_payload' => json_encode([
                'status' => $responseArray['Status'] === 'OK' ? 'success' : 'failed',
                'message' => $responseArray['Status'] === 'OK' ? 'پرداخت موفق ✅' : 'پرداخت ناموفق ⛔',
                'data' => $responseArray,
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if (isset($responseArray['data']['authority'])) {
            $authority = $responseArray['data']['authority'];
            $payment->ref_code = $authority;
            $payment->save();

            return response()->json([
                'status' => 'success',
                'message' => __('api.payment_ready'),
                'data' => [
                    'payment_id' => $payment->id,
                    'payment_url' => $this->zarinpal->getStartPayUrl($authority)
                ]
            ]);
        } else{
            return response()->json([
                'status' => 'failed',
                'message' => __('api.payment_error'),
                'data' => $responseArray
            ]);
        }
    }


}

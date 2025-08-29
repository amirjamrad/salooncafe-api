<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderStoreRequest;
use App\Http\Resources\Admin\OrderResource;
use App\Model\Item;
use App\Model\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(OrderStoreRequest $request)
    {
        $validated = $request->validated();
        $user = auth()->user();

        DB::beginTransaction();
        try {
            $order = Orders::create([
                'user_id' => $user->id,
                'total_price' => 0,
                'status' => 'pending',
                'payment_status' => 'unpaid'
            ]);

            $total = 0;
            foreach ($validated['items'] as $itemData) {
                $item = Item::findOrFail($itemData['id']);
                $quantity = $itemData['quantity'];

                $order->order_items()->create([
                    'item_id' => $item->id,
                    'quantity' => $quantity,
                    'price' => $item->price,
                ]);

                $total += $item->price * $quantity;
            }

            $order->total_price = $total;
            $order->save();

            DB::commit();

            return Response::successResponse(200, __('api.order_create_success'), new OrderResource($order->load('user')));


        } catch (\Exception $e) {
            DB::rollBack();
            return Response::errorResponse(500,'خطا در ایجاد سفارش : '.$e->getMessage());
        }
    }

}

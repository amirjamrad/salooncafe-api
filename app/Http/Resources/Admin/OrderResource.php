<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'username' => $this->user?->full_name,
            'total_price'=> number_format($this->total_price, 0, '', ','),
            'status'=> $this->status,
            'payment_status'=>$this->payment_status,
            'created_at'=>$this->created_at?->toDateTimeString(),
            'updated_at'=>$this->updated_at?->toDateTimeString(),
        ];
    }
}

<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Admin\ItemResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'products' => ItemResource::collection($this->whenLoaded('items')),
            'created_at'=>$this->created_at?->toDateTimeString(),
        ];
    }
}

<?php

namespace App\Http\Resources\Admin;

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
            'deleted_at'=>$this->deleted_at?->toDateTimeString(),
            'created_at'=>$this->created_at?->toDateTimeString(),
            'updated_at'=>$this->updated_at?->toDateTimeString(),
        ];
    }
}

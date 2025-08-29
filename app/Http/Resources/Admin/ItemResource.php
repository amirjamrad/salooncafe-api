<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'picture' => url(env('IMAGE_ITEMS_PATH').$this->picture),
            'price' => $this->price,
            'category_id' => $this->category_id,
            'category_name' => $this->category->name,
            'is_active' => (bool) $this->is_active,
            'deleted_at' => $this->deleted_at?->toDateTimeString(),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}

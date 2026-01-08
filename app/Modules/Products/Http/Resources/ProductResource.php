<?php

namespace App\Modules\Products\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'description' => $this->description,
            'is_active' => (bool) $this->is_active,

            // Expose both cents (accurate) and display price (readable)
            'price_cents' => $this->price_cents,
            'price' => $this->price_cents / 100,

            'cost_cents' => $this->cost_cents,
            'cost' => $this->cost_cents !== null ? $this->cost_cents / 100 : null,

            'created_by' => $this->created_by,
            'created_at' => optional($this->created_at)->toISOString(),
            'updated_at' => optional($this->updated_at)->toISOString(),
        ];
    }
}

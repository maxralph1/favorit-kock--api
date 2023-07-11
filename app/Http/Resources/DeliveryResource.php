<?php

namespace App\Http\Resources;

// use App\Http\Resources\UserResource;
// use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryResource extends JsonResource
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
            'order_id' => $this->order_id,
            'user_id' => $this->user_id,
            'delivered' => $this->delivered,
            'time_delivered' => $this->time_delivered,
        ];
    }
}

<?php

namespace App\Http\Resources;

// use App\Http\Resources\UserResource;
// use App\Http\Resources\OrderItemResource;
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
            'id' => $this->id,
            // 'user' => [
            //     'id' => $this->user->id,
            //     'name' => $this->user->name,
            //     'username' => $this->user->username,
            // ],
            'user_id' => $this->user_id,
            'order_annuled' => $this->order_annuled,
            'delivered' => $this->delivered,
            'total_amount' => $this->total_amount,
            'paid' => $this->paid,
            'delivered_by' => $this->delivered_by,
            // 'order_items' => [
            //     OrderItemResource::collection($this->orderItems),
            // ]
        ];
    }
}

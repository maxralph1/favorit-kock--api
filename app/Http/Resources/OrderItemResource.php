<?php

namespace App\Http\Resources;

// use App\Http\Resources\MealResource;
// use App\Http\Resources\UserResource;
// use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            // 'meal' => [
            //     'id' => $this->meal->id,
            //     'title' => $this->meal->title,
            //     'description' => $this->meal->description,
            //     'price' => $this->meal->price,
            //     'active' => $this->meal->active,
            // ], 
            'meal_id' => $this->meal_id,
            // 'order' => [
            //     'id' => $this->order->id,
            //     'title' => $this->order->title,
            // ],
            'order_id' => $this->order_id,
            // 'user' => [
            //     'id' => $this->user->id,
            //     'name' => $this->user->name,
            //     'username' => $this->user->username,
            // ], 
            'user_id' => $this->user_id,
            'amount_due' => $this->amount_due,
            'quantity_ordered' => $this->quantity_ordered,
        ];
    }
}

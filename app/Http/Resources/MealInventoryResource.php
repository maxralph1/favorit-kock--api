<?php

namespace App\Http\Resources;

// use App\Http\Resources\MealResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MealInventoryResource extends JsonResource
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
            'plates_prepared' => $this->plates_prepared,
            'active' => $this->active,
        ];
    }
}

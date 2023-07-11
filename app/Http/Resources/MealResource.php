<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MealResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'active' => $this->active,
            // 'category' => [
            //     'id' => $this->category->id,
            //     'title' => $this->category->title,
            // ],
            'category' => [
                'id' => $this->category->id,
                'title' => $this->category->title,
                'description'  => $this->category->description,
            ],
            // 'images' => MealImageResource::collection($this->mealImages),
            // 'categories' => CategoryResource::collection($this->categories),
        ];
    }
}

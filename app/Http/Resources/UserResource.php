<?php

namespace App\Http\Resources;

// use App\Http\Resources\MealImageResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
            'user_image' => new UserImageResource($this->userImage),
            'user_addresses' => UserAddressResource::collection($this->userAddresses),
        ];
    }
}

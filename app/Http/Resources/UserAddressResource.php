<?php

namespace App\Http\Resources;

// use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAddressResource extends JsonResource
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
            'house_number' => $this->house_number,
            'street' => $this->street,
            'city' => $this->city,
            'post_code' => $this->post_code,
            'state' => $this->state,
            'landmark' => $this->landmark,
            'default' => $this->default,
        ];
    }
}

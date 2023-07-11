<?php

namespace App\Http\Resources;

// use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserImageResource extends JsonResource
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
            'image_url' => $this->image_url,
            // 'user' => [
            //     'id' => $this->user->id,
            //     'username' => $this->user->username,
            //     'name' => $this->user->name,
            //     'email' => $this->user->email,
            // ],
            'user_id' => $this->user_id,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'avatar_url' => $this->avatar_url,
            'created_at' => $this->created_at,
            'email' => $this->email,
            'introduce' => $this->introduce,
            'name' => $this->name,
            'username' => $this->username,
            'role' => $this->role,
            'social_accounts' => $this->whenLoaded('socialAccounts'),
            'settings' => $this->title
        ];
    }
}

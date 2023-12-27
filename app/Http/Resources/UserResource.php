<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $photo = $this->photo? $this->photo: 'null.png';
        return [
            'name'=> $this->name,
            'nickname'=> $this->nickname,
            'photo'=> URL::to("/api/photo/". $photo),
            'email'=> $this->email,
        ];
    }
}

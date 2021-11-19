<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name'          =>  (string) $this->name,
            'phone'         =>  (string) $this->phone,
            'email'         =>  (string) $this->email,
            'gender'        =>  (string) $this->gender,
            'dob'           =>  (string) ($this->dob ? $this->dob->format('d/m/Y') : ''),
            'address'       =>  (string) $this->address,
        ];
    }
}

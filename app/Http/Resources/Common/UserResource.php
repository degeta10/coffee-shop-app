<?php

namespace App\Http\Resources\Common;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'            =>  (string) $this->id,
            'name'          =>  (string) $this->name,
            'phone'         =>  (string) $this->phone,
            'email'         =>  (string) $this->email,
            'gender'        =>  (string) $this->gender,
            'dob'           =>  (string) ($this->dob ? $this->dob->format('d/m/Y') : ''),
        ];
    }
}

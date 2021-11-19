<?php

namespace App\Http\Resources\Customer;

use App\Http\Resources\Common\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'order_id'      =>  (string) $this->id,
            'order_no'      =>  (string) $this->order_no,
            'product'       =>   new ProductResource($this->product),
            'quantity'      =>  (string) $this->quantity,
            'total_amount'  =>  (string) $this->amount,
            'ordered_on'    =>  (string) $this->date_of_order,
            'status'        =>  (string) $this->status,
        ];
    }
}

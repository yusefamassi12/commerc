<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $product = products::find($this->product_id);

        return [
            'productID' => $this->product_id,
            'price' => $product->regularprice,
            'name' => $product->name,
            'Quantity' => $this->quantity,
        ];
    }
}

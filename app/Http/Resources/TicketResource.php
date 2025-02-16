<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        return[
            'event_id'=>$this->event_id,
            'ticket_type'=>$this->ticket_type,
            'price'=>$this->price,
            'quantity'=>$this->quantity,
            'available'=>$this->available,
        ];
    }
}

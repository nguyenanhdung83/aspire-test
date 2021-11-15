<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'term' => $this->term,
            'frequency' => $this->frequency,
            'process_status' => $this->process_status,
            'repayment_completed' => $this->repayment_completed,
            'requested_at' => $this->created_at,
            'repayments' => RepaymentResource::collection($this->whenLoaded('repayments')),
        ];
    }
}

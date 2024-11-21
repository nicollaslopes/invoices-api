<?php

namespace App\Http\Resources\v1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{

    private array $types = [
        'B' => 'Boleto',
        'C' => 'Cartão',
        'P' => 'Pix',
    ];

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $paid = $this->paid;

        return [
            'user' => [
                'first_name' => $this->user->first_name,
                'last_name' => $this->user->last_name,
                'full_name' => $this->user->first_name . ' ' .$this->user->last_name,
                'email' => $this->user->email
            ],
            'type' => $this->types[$this->type],
            'value' => '$' . number_format($this->value, 2, ',', '.'),
            'paid' => $paid ? 'Paid' : 'Not paid',
            'payment_date' => $paid ? Carbon::parse($this->payment_date)->format('m/d/Y H:i:s') : null,
            'payment_since' => $paid ? Carbon::parse($this->payment_date)->diffForHumans() : null,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentFormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = $this->divided_in;
        $data = json_decode($this->divided_in);
        $dividedIn = '<ul>';
        foreach ($data as   $value) {
            $class = "";
            if ($this->paid_by == $value) {
                $class = 'text-success';
            } else {
                $class = 'text-danger';
            }
            $dividedIn .= '<li>';
            $dividedIn .= '<span class="' . $class . '">';
            $dividedIn .= getUserName(getUser($value));
            $dividedIn .= '</span>';
            $dividedIn .= '</li>';
        }
        $dividedIn .= '</ul>';
        return [
            "id" => $this->id,
            "title" => $this->title ?? null,
            "paid_by" => $this->paidBy->name ?? null,
            "divided_in" => $dividedIn ?? null,
            "total_amount" => $this->total_amount ?? 0,
            "per_head_amount" => $this->per_head_amount ?? 0,
            "date" => $this->date ?? 0,
        ];
    }
}

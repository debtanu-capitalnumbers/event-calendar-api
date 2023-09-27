<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'event_category' => $this->event_category,
            'event_start_date' => $this->event_start_date,
            'event_start_time' => $this->event_start_time,
            'event_end_time' => $this->event_end_time,
            'event_start_date_time' => date('m/d/Y - h:i A', strtotime($this->event_start_date.' '.$this->event_start_time)),
            'event_end_date_time' => date('m/d/Y - h:i A', strtotime($this->event_start_date.' '.$this->event_end_time)),
            'is_active' => (!isset($this->is_active)) ? "true" : (bool) $this->is_active            
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventCalendarResource extends JsonResource
{
    // public function __construct()
    // {
    //     $this->exclude = array();
    // }
    
    public function exclude($exclude = array()){
        // $this->exclude = $exclude;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data['title'] = date('g:i a', strtotime($this->event_start_time)).' '.$this->title;
        $data['date'] = $this->event_start_date;
        return $data;
    }
}

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
        $data['id'] = $this->id;
        $data['user_id'] = $this->user_id;
        $data['title'] = $this->title;
        $data['description'] = $this->description;
        $data['location'] = $this->location;
        $data['event_category'] = $this->event_category;
        $data['event_start_date'] = $this->event_start_date;
        $data['event_start_time'] = $this->event_start_time;
        $data['event_end_time'] = $this->event_end_time;
        $data['event_start_date_time'] = date('m/d/Y - h:i A', strtotime($this->event_start_date.' '.$this->event_start_time));
        $data['event_end_date_time'] = date('m/d/Y - h:i A', strtotime($this->event_start_date.' '.$this->event_end_time));
        $data['is_active'] = (!isset($this->is_active)) ? "true" : (bool) $this->is_active;
        $data['file_name'] = $this->file_name;
        $data['file_path'] = $this->file_path;
        $data['download_path'] = (is_null($this->file_path) || $this->file_path == "") ? "" : asset('storage/'.$this->file_path);
        return $data;
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
        $export_url_path = 'api/events/export/file';
        if(!$request->is($export_url_path)) $data['id'] = $this->id;
        if(!$request->is($export_url_path)) $data['user_id'] = $this->user_id;
                                            $data['title'] = $this->title;
                                            $data['description'] = $this->description;
                                            $data['location'] = $this->location;
                                            $data['event_category'] = $this->event_category;
        if(!$request->is($export_url_path)) $data['event_start_date'] = $this->event_start_date;
        if(!$request->is($export_url_path)) $data['event_start_time'] = $this->event_start_time;
        if(!$request->is($export_url_path)) $data['event_start_time_hours'] = date('h', strtotime($this->event_start_date.' '.$this->event_start_time));
        if(!$request->is($export_url_path)) $data['event_start_time_minutes'] = date('i', strtotime($this->event_start_date.' '.$this->event_start_time));
        if(!$request->is($export_url_path)) $data['event_start_time_seconds'] = date('s', strtotime($this->event_start_date.' '.$this->event_start_time));
        if(!$request->is($export_url_path)) $data['event_end_time'] = $this->event_end_time;
        if(!$request->is($export_url_path)) $data['event_end_time_hours'] = date('h', strtotime($this->event_end_date.' '.$this->event_end_time));
        if(!$request->is($export_url_path)) $data['event_end_time_minutes'] = date('i', strtotime($this->event_end_date.' '.$this->event_end_time));
        if(!$request->is($export_url_path)) $data['event_end_time_seconds'] = date('s', strtotime($this->event_end_date.' '.$this->event_end_time));
                                            $data['event_start_date_time'] = date('m/d/Y - h:i A', strtotime($this->event_start_date.' '.$this->event_start_time));
                                            $data['event_end_date_time'] = date('m/d/Y - h:i A', strtotime($this->event_start_date.' '.$this->event_end_time));
                                            $data['is_active'] = (!isset($this->is_active)) ? true : (($this->is_active) ? true : false );
        if(!$request->is($export_url_path)) $data['file_name'] = $this->file_name;
        if(!$request->is($export_url_path)) $data['file_path'] = $this->file_path;
        if(!$request->is($export_url_path)) $data['download_path'] = (is_null($this->file_path) || $this->file_path == "") ? "" : asset('storage/'.$this->file_path);
                                            $data['created_at'] = date('YYYY-mm-dd', strtotime($this->created_at));
        return $data;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = ['title', 'user_id', 'description', 'location', 'file_name', 'file_path', 'event_category', 'event_start_date', 'event_start_time', 'event_end_time', 'is_active'];

    protected $appends = ['download_path'];
    
    public function getDownloadPathAttribute(){
        $image_url = (is_null($this->file_path) || $this->file_path == "") ? "" : asset('storage/'.$this->file_path);
        return $image_url;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = ['name', 'description', 'location', 'file_name', 'file_path', 'event_category', 'event_start_date', 'event_start_time', 'event_end_time', 'is_active'];
    
}

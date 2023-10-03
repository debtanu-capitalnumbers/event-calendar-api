<?php

namespace App\Exports;

use App\Models\Event;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Http\Resources\EventResource;

class EventExport implements FromCollection, WithHeadings
{
    public function __construct($collection)
    {
        $this->collection = $collection;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $this->collection = $this->collection->get();
        return EventResource::collection($this->collection);
        //return Event::select(['id','title'])->get();
    }
    
    public function headings(): array
    {
        return [
            'title',
            'description',
            'location',
            'event_category',
            'event_start_date_time',
            'event_end_date_time',
            'is_active',
            'created_at',
        ];
    }
}

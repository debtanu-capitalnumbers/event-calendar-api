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
        return EventResource::collection($this->collection);
        //return Event::select(['id','title'])->get();
    }
    
    public function headings(): array
    {
        return [
            'Title',
            'Description',
            'Location',
            'Category',
            'Start date time',
            'End date time',
            'Is active',
            'Created at',
        ];
    }
}

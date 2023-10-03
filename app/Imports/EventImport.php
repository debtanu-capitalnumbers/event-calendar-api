<?php

namespace App\Imports;

use DateTime;
use App\Models\Event;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Importable;
//use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EventImport implements ToCollection, WithChunkReading, ShouldQueue, WithHeadingRow
{
    use Importable;

    protected $auth_id;

    function __construct($auth_id) {
        $this->auth_id = $auth_id;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $date = new DateTime(trim($row['start_date_time']));
            $start_date = $date->format('Y-m-d');
            $start_time = $date->format('h:i').':00';
            $date = new DateTime(trim($row['end_date_time']));
            $end_time = $date->format('h:i').':00';

            Event::updateOrCreate(
                [
                    'title'             => isset($row['title']) ? $row['title'] : null,
                    'user_id'           => $this->auth_id,
                    'description'       => isset($row['description']) ? $row['description'] : null,
                    'location'          => isset($row['location']) ? $row['location'] : null,
                    'event_category'          => isset($row['category']) ? $row['category'] : null,
                    'event_start_date'  => $start_date,
                    'event_start_time'  => $start_time,
                    'event_end_time'    => $end_time,
                ],
                [
                    'title'             => isset($row['title']) ? $row['title'] : null,
                    'user_id'           => $this->auth_id,
                    'description'       => isset($row['description']) ? $row['description'] : null,
                    'location'          => isset($row['location']) ? $row['location'] : null,
                    'event_category'          => isset($row['category']) ? $row['category'] : null,
                    'event_start_date'  => $start_date,
                    'event_start_time'  => $start_time,
                    'event_end_time'    => $end_time,
                    'is_active'         =>  1,
                ]
            );
        }
    }
    /*public function startRow(): int
    {
         return 1;
    }*/

    public function batchSize(): int
    {
        return 20;
    }

    public function chunkSize(): int
    {
        return 20;
    }
}

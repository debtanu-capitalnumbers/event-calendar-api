<?php

namespace App\Http\Controllers\Api\Event;

use DateTime;
use ICal\ICal;
use DateTimeZone;
use App\Models\Event;
use Illuminate\Support\Str;
use App\Exports\EventExport;
use App\Imports\EventImport;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\EventResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\EventCalendarResource;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Timezone;
use Spatie\IcalendarGenerator\Enums\TimezoneEntryType;
use Spatie\IcalendarGenerator\Components\TimezoneEntry;
use Spatie\IcalendarGenerator\Components\Event as CalendarEvent;


class EventController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Event::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $per_page = $request->get('per_page')?: 10;
        $search = trim($request->get('search'))?: null;
        $sort_by = $request->get('sort_by')?: 'DESC';
        $sort_field_name = $request->get('sort_field_name')?: 'event_start_date_time';
        $collection = auth()->user()->events();
        if(!is_null($search)){
            $collection = $collection->where('title', 'like', "%".$search."%");
        }       
        if($sort_field_name == "event_start_date_time"){
            $collection = $collection->orderBy("event_start_date", $sort_by)->orderBy("event_start_time", $sort_by);
        } else if($sort_field_name == "event_end_date_time"){
            $collection = $collection->orderBy("event_start_date", $sort_by)->orderBy("event_end_time", $sort_by);
        } else {
            $collection = $collection->orderBy($sort_field_name, $sort_by);
        }
        $collection = $collection->paginate($per_page);

        return EventResource::collection($collection);
    }

    /**
     * Display a listing of the resource.
     */
    public function allCalendarEvents(Request $request)
    {
        $collection = auth()->user()->events();
        $collection = $collection->get();

        return EventCalendarResource::collection($collection);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        $event_data = $request->all();
        if(!empty($event_data['cover_image'])){
            $validator = Validator::make($request->all(), [
                'cover_image' => 'required|mimes:png,jpg,jpeg|max:4096',
            ], [
                'cover_image.mimes' => 'Only support JPG/JPEG/PNG format.',
                'cover_image.size' => 'Maximum upload image size 4MB.',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->messages()->all(), 'message' => 'Errors found for cover image.'], 422);
            }
            $cover_image = $event_data['cover_image'];
            $path_parts = pathinfo($cover_image->getClientOriginalName());
    
            $fileName = Str::random(25).'-'.Str::slug($path_parts['filename']).'.'.$path_parts['extension'];
            $filePath = 'event/'.auth()->user()->id.'/';
    
            $cover_image->move(storage_path('app/public/'.$filePath), $fileName);
            $event_data['file_name'] = $fileName;
            $event_data['file_path'] = $filePath.$fileName;
        }
        $event = $request->user()->events()->create($event_data);
        return EventResource::make($event);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return EventResource::make($event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        $event_data = $request->all();
        if(!empty($event_data['cover_image'])){
            $validator = Validator::make($request->all(), [
                'cover_image' => 'required|mimes:png,jpg,jpeg|max:4096',
            ], [
                'cover_image.mimes' => 'Only support JPG/JPEG/PNG format.',
                'cover_image.size' => 'Maximum upload image size 4MB.',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->messages()->all(), 'message' => 'Errors found for cover image.'], 422);
            }
            $cover_image = $event_data['cover_image'];
            $path_parts = pathinfo($cover_image->getClientOriginalName());
    
            $fileName = Str::random(25).'-'.Str::slug($path_parts['filename']).'.'.$path_parts['extension'];
            $filePath = 'event/'.auth()->user()->id.'/';
    
            $cover_image->move(storage_path('app/public/'.$filePath), $fileName);
            $event_data['file_name'] = $fileName;
            $event_data['file_path'] = $filePath.$fileName;
        }
        $event->update($event_data);

        return EventResource::make($event);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response()->noContent();
    }
    
    /**
     * export the specified resource from storage.
     */
    public function export(Request $request)
    {
        my_export_csv();
        $validator = Validator::make($request->all(), [
            'export_type' => 'required',
            'event_start_date' => 'required',
            'event_end_date' => 'required',
        ], [
            'export_type.required' => 'The event export type field is required.',
            'event_start_date.required' => 'The event start date field is required.',
            'event_end_date.required' => 'The event end date field is required.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()->all(), 'message' => 'Errors found for cover image.'], 422);
        }  


        $event_data = $request->all();
        $event_start_date = $event_data['event_start_date'];
        $event_start_date_strtotime = strtotime($event_start_date);
        
        $event_end_date = $event_data['event_end_date'];
        $event_end_date_strtotime = strtotime($event_end_date);

        if($event_end_date_strtotime <= $event_start_date_strtotime){ 
            $errors['event_end_date'][] = "The event end date must be greater than start date."; 
            return response()->json(['errors' => $errors, 'message' => 'Errors found for date.'], 422);
        }

        $collection = auth()->user()->events();
        $collection = $collection->where('event_start_date', '>=', $event_start_date);  
        $collection = $collection->where('event_start_date', '<=', $event_end_date);  
        $collection = $collection->orderBy("event_start_date", 'DESC')->orderBy("event_start_time", 'DESC');  
        $collection = $collection->get();

        if($event_data['export_type'] == "csv") {
            $newfile = 'event-export-'.date('Y-m-d-H-i-s').'.csv';
            my_export_csv();
            Excel::store(new EventExport($collection), $newfile, 'csvlocal');    
        } else {
            $newfile = 'event-export-'.date('Y-m-d-H-i-s').'.ics';
            foreach ($collection as $key => $single_event) {
                $event_start_date_time = date('Y-m-d H:i:s', strtotime($single_event->event_start_date.' '.$single_event->event_start_time));
                $event_start_date_time = new DateTime($event_start_date_time); 
                $event_start_date_time->setTimezone(new DateTimeZone("UTC")); 

                $event_end_date_time = date('Y-m-d H:i:s', strtotime($single_event->event_start_date.' '.$single_event->event_end_time));
                $event_end_date_time = new DateTime($event_end_date_time); 
                $event_end_date_time->setTimezone(new DateTimeZone("UTC")); 
                $create_event[] = CalendarEvent::create($single_event->title)
                                ->image($single_event->download_path)
                                ->name($single_event->title)
                                ->address($single_event->location)
                                ->description($single_event->description)
                                ->uniqueIdentifier(Str::uuid()->toString())
                                ->createdAt($event_start_date_time)
                                ->startsAt($event_start_date_time)
                                ->endsAt($event_end_date_time);
            }
            $calendar = Calendar::create('Event calendar')->event($create_event);
            // return response($calendar->get(), 200, [
            //     'Content-Type' => 'text/calendar; charset=utf-8',
            //     'Content-Disposition' => 'attachment; filename="my-awesome-calendar.ics"',
            // ]);
            $calendar = Calendar::create('Event calendar')->event($create_event);
            Storage::disk('local')->put('public/csv/'.$newfile, $calendar->get());
        }
        $url = asset('storage/csv/'.$newfile);
        return response()->json(['url' => $url, 'message' => 'File generated successfully.'], 200);
    }
    
    /**
     * export the specified resource from storage.
     */
    public function import(Request $request)
    {
        my_export_csv();
        $validator = Validator::make($request->all(), [
            'import_type' => 'required',
            'import_file' => 'required|mimes:csv,txt,ics|max:4096',
            // 'import_file' => 'required|mimes:text/csv,text/plain,application/csv,text/comma-separated-values,text/anytext,application/octet-stream,application/txt,text/calendar|max:4096',
        ], [
            'import_type.required' => 'The event import type field is required.',
            'import_file.required' => 'The event import file is required.',
            'import_file.mimes' => 'Only support CSV/CALENDAR format.',
            'import_file.size' => 'Maximum upload image size 4MB.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()->all(), 'message' => 'Errors found for import file.'], 422);
        }  


        $event_data = $request->all();
        $file = $request->file('import_file');

        if($event_data['import_type'] == "csv") {
            Excel::import(new EventImport(auth()->user()->id), $file);
        } else {
            $ical = new ICal($file);
            $all_events = $ical->events();
            foreach ($all_events as $key => $row) {
                $row = (array) $row;
                $start_date = date('Y-m-d', strtotime($row['dtstart']));
                $start_time = date('H:i:s', strtotime($row['dtstart']));
                $end_time = date('H:i:s', strtotime($row['dtend']));
                
                Event::updateOrCreate(
                    [
                        'title'             => isset($row['summary']) ? $row['summary'] : null,
                        'user_id'           => auth()->user()->id,
                        'description'       => isset($row['description']) ? $row['description'] : null,
                        'location'          => isset($row['location']) ? $row['location'] : null,
                        // 'event_category'          => isset($row['category']) ? $row['category'] : null,
                        'event_start_date'  => $start_date,
                        'event_start_time'  => $start_time,
                        'event_end_time'    => $end_time,
                    ],
                    [
                        'title'             => isset($row['summary']) ? $row['summary'] : null,
                        'user_id'           => auth()->user()->id,
                        'description'       => isset($row['description']) ? $row['description'] : null,
                        'location'          => isset($row['location']) ? $row['location'] : null,
                        // 'event_category'          => isset($row['category']) ? $row['category'] : null,
                        'event_start_date'  => $start_date,
                        'event_start_time'  => $start_time,
                        'event_end_time'    => $end_time,
                        'is_active'         =>  1,
                    ]
                );
            }
        }
        return response()->json(['message' => 'File imported successfully.'], 200);
    }
}

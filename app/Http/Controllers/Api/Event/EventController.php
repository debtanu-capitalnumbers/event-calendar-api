<?php

namespace App\Http\Controllers\Api\Event;

use App\Models\Event;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;

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
        $sort_field_name = $request->get('sort_field_name')?: 'id';
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
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        $event_data = $request->validated();
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
        $event->update($request->validated());

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
}

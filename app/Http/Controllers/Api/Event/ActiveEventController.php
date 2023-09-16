<?php

namespace App\Http\Controllers\Api\Event;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

class ActiveEventController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Event $event)
    {
        $this->authorize('update-active-event', $event);
        $event->is_active = $request->is_active;
        $event->update();

        return EventResource::make($event);
    }
}

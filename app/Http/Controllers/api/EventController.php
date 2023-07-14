<?php

namespace App\Http\Controllers\api;

use App\Helper\Helper;
use App\Models\{Event, Url};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\EventResources;
use App\Http\Requests\EventRequest;
use Illuminate\Support\Facades\{Auth, Cache};

class EventController extends Controller
{
    /**
     * Get all events.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $events = Helper::saveToCache('events', Event::all(), now()->addHour(1));


            return response()->json([
                'message' => 'Event index',
                'data' => $events
            ], 200);
        } catch (\Throwable $th) {

            return response()->json(['message' => 'Events not found'], 404);
        }
    }

    public function slug(string $slug)
    {
        try {
            //code...
            $url_id = explode('-', $slug)[6];
            $data = Url::where('short_id', $url_id)->first();

            if (!$data) {
                return response()->json(['message' => 'Event not found'], 404);
            }

            $id = $data->event_id;
            $cachedEvent = Helper::getFromCache('events', $id);

            if ($cachedEvent) {
                $event = $cachedEvent;
            } else {
                $event = Event::findOrFail($id);
                // $event = Helper::saveToCache('events', $event->id, $event, now()->addHour(1));
            }

            Helper::updateEventClicks($event);
            Helper::updateCache('events', $event->id, $event, now()->addHour(1));

            return response()->json([
                'message' => 'Event retrieved successfully',
                'data' => new EventResources($event)
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Event not found'], 404);
        }
    }


    /**
     * Get a specific event.
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function show(string $id)
    {
        try {
            $cachedEvent = Helper::getFromCache('events', $id);

            if ($cachedEvent) {
                $event = $cachedEvent;
            } else {
                $event = Event::findOrFail($id);
                $event = Helper::saveToCache('events' . $event->id, $event, now()->addHour(1));
            }

            Helper::updateEventClicks($event);
            Helper::updateCache('events', $event->id, $event, now()->addHour(1));

            return response()->json([
                'message' => 'Event retrieved successfully',
                'data' => new EventResources($event)
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Event not found'], 404);
        }
    }
    /**
     * Create a new event.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(EventRequest $request)
    {
        $data = $request->all();
        $url_data = Helper::generateLink($data['title']);

        $data = Event::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'location' => $data['location'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'time' => $data['time'],
            'type' => $data['type'],
            'capacity' => $data['capacity'],
            'available_seats' => $data['capacity'] ?? $data['available_seats'],
            'price' => $data['type'] === 'free' ? 0 : $data['price'],
            'image' => $data['image'],
            'user_id' => Auth::user()->id,
        ]);

        if ($request->hasFile('image') && !empty($request->image)) {
            $data->addMediaFromRequest('image')->toMediaCollection('image');
        }

        $url = Url::create([
            'long_url' => $url_data['long_url'],
            'short_id' => $url_data['short_id'],
            'short_url' => $url_data['short_url'],
            'event_id' => $data->id,
            'clicks' => 0,
        ]);


        return response()->json([
            'message' => 'Event created successfully',
            'event' => new EventResources($data),
        ], 201);
    }

    /**
     * Redirect to the specified event.
     * @param  string  $short_id
     * @return \Illuminate\Http\Response
     */

    public function redirect($short_id)
    {
        $url = Url::where('short_id', $short_id)->first();

        if (!$url) {
            return response()->json(['message' => 'Url not found'], 404);
        }

        return redirect($url->long_url);
    }

    /**
     * Update the specified event in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, string $id)
    {
        try {
            //code...
            $cachedEvent = Helper::getFromCache('events', $id);

            if ($cachedEvent) {
                $event = $cachedEvent;
            } else {
                $event = Event::findOrFail($id);
            }

            $event->update($request->all());
            Helper::updateCache('events', $id, $event, now()->addHour(1));


            return response()->json([
                'message' => 'Event created successfully',
                'event' => new EventResources($event),
            ], 201);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'Event not found'], 404);
        }
    }


    /**
     * Remove the specified event from storage.
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(string $id)
    {
        try {
            //code...
            $event =  Cache::get('event:' . $id);

            if ($event) {
                Cache::forget('event:' . $id);
            }

            if (!$event) {
                $event = Event::find($id);

                if ($event) {
                    $event->delete();
                }
            }

            return response()->json(['message' => 'Event deleted successfully'], 200);
        } catch (\Throwable $th) {
            //throw $th;

            return response()->json(['message' => 'Event not found'], 404);
        }
    }
}

<?php

namespace App\Http\Controllers\api;

use App\Helper\Helper;
use App\Models\{Event, Url};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\EventResources;
use App\Http\Requests\EventRequest;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Get all events.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::all();


        return response()->json([
            'message' => 'Event index',
            'data' => $events
        ], 200);
    }

    public function slug(string $slug)
    {
        $url_id= explode('-', $slug)[6];

        $data = Url::where('short_id', $url_id)->first();
        $event =  Event::where('id', $data->event_id)->first();


        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        return response()->json(['event' => new EventResources($event)], 200);
    }


    /**
     * Get a specific event.
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function show(string $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event notddd found'], 404);
        }

        return response()->json(
            ['message' => 'Event retrieved sucessfully', 
            'event' => new EventResources($event)], 200);
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

        if($request->hasFile('image')&& !empty($request->image) ){
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

    public function redirect($short_id)
    {
        $url = Url::where('short_id', $short_id)->first();

        if (!$url) {
            return response()->json(['message' => 'Url not found'], 404);
        }

        $url->increment('clicks');

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
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $event->update($request->all());

        return response()->json(['message' => 'Event updated successfully'], 200);
    }


    /**
     * Remove the specified event from storage.
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(string $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $event->delete();

        return response()->json(['message' => 'Event deleted successfully'], 200);
    }
}

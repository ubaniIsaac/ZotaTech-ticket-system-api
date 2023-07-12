<?php

namespace App\Http\Controllers\api;

use App\Models\Event;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    //
    public function redirect(Request $request)
    {
        // $short_link = $request->short_link;
        // $event = Event::where('short_link', $short_link)->first();

        // if (!$event) {
        //     return response()->json(['message' => 'Event not found'], 404);
        // }

        return response()->json(['event' => '$event'], 200);
    }
}

<?php

namespace App\Http\Controllers\api;

use App\Models\Url;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UrlController extends Controller
{
    //
    public function index()
    {
        return response()->json(['message' => 'Url index'], 200);
    }

    public function show(string $id)
    {
        return response()->json(['message' => 'Url show'], 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $url = Url::create($data);

        return response()->json(['message' => 'Url Created', 'links' => $url], 201);
    }

    public function update(Request $request, string $id)
    {
        return response()->json(['message' => 'Url update'], 200);
    }
}

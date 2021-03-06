<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Videos;

class VideosController extends Controller
{
    public function index()
    {
        return Videos::all();
    }

    public function show(Videos $video)
    {
        return $video;
    }

    public function store(Request $request)
    {
        $video = Videos::create($request->all());

        return response()->json($video, 201);
    }

    public function update(Request $request, Videos $video)
    {
        $video->update($request->all());

        return response()->json($video, 200);
    }

    public function delete(Videos $video)
    {
        $video->delete();

        return response()->json(null, 204);
    }
}

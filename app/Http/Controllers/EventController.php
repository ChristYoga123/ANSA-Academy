<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public $title = 'Event';

    public function index()
    {
        return view('pages.event.index', [
            'title' => $this->title,
            'events' => Event::with(['media', 'eventJadwals'])->withCount('eventJadwals')->latest()->paginate(6)
        ]);
    }

    public function show($slug)
    {
        $event = Event::with(['media', 'eventJadwals'])->where('slug', $slug)->first();
        return view('pages.event.show', [
            'title' => $this->title,
            'event' => $event
        ]);
    }
}

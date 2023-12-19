<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {

        $eventsQuery = Event::query();
        $events = $eventsQuery->withCount('attendees')->withCount('guests')->where("user_id", auth()->id())->get();

        return view('statistics', [
            'events' => $events,
        ]);
    }
}

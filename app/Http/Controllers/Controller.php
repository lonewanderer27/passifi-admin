<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function adminIndex(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $eventsQuery = Event::query();

        // Filter events by the user_id of the currently logged in admin
        $eventsQuery->where('user_id', auth()->id());

        // If start_date is provided, filter events from that date onwards
        if ($startDate) {
            $eventsQuery->whereDate('start', '>=', $startDate);
        }

        // If end_date is provided, filter events up to that date
        if ($endDate) {
            $eventsQuery->whereDate('start', '<=', $endDate);
        }

        $events = $eventsQuery
            ->with(['guests.user'])
            ->withCount(['guests'])
            ->withCount('attendees')
            ->get();

        return view('dashboard', [
            'events' => $events,
        ]);
    }
}

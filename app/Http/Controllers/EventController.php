<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
//use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Inertia\Inertia;
use chillerlan\QRCode\{QRCode, QROptions, Output\QROutputInterface};

class EventController extends Controller
{
    public function getAvatar($id) {
        // fetch the event
        $event = Event::find($id);

        // check if the event exists
        if (!$event) {
            return response()->json([
                "error" => "Event not found!",
                "message" => "Event not found!",
                "success" => false
            ], 404);
        }

        // check if the event has an avatar
        if (!$event->avatar) {
            return response()->json([
                "error" => "Event avatar not found!",
                "message" => "Event avatar not found!",
                "success" => false
            ], 404);
        }

        // fetch the avatar
        $avatar = $event->avatar;

        // return the avatar as a png response
        return Response::make($avatar, 200, ['Content-Type' => 'image/png']);
    }

    public function index()
    {
        $events = Event::all();

        return response()->json([
            "events" => $events,
            "error" => null,
            "success" => true
        ]);
    }

    public function getEventQR($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                "error" => "Event not found!",
                "message" => "Event not found!",
                "success" => false
            ], 404);
        }

        // Generate QR Code
//        $qrCode = QrCode::format('png')->style('round')->size(500)->generate($event->invite_code);
        $qrCode = (new QRCode((new QROptions([
            'outputType' => QROutputInterface::GDIMAGE_PNG,
            'scale' => 5,
            'eccLevel' => QRCode::ECC_L,
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'imageBase64' => false,
        ]))))->render($event->invite_code);

        // Create filename
        $filename = str_replace(' ', '_', $event->title) . '--invite--qrcode.png';

        // Return image response
        return Response::make($qrCode, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename=' . $filename
        ]);
    }

    public function adminScan($id)
    {
        $event = Event::find($id);
        Inertia::setRootView('event');

        // fetch attendees api route
        $attendeesApiRoute = route('attendees.createByEvent');

        return Inertia::render('AdminScan', [
            'event' => $event,
            'attendeesApiRoute' => $attendeesApiRoute
        ]);
    }

    public function adminStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|unique:events',
            'avatar' => 'nullable|string',
            'description' => 'nullable|string',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required',
            'location' => 'required|string',
            'organizer' => 'required|string',
            'organizer_email' => 'required|email',
            'organizer_approval' => 'required|boolean',
            'user_id' => 'required|integer|exists:users,id',
            'invite_code' => 'required|string|unique:events'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Validation failed",
                "errors" => $validator->errors(),
                "error" => true,
                "success" => false
            ], 422); // 422 Unprocessable Entity
        }

        $event = Event::create([
            'title' => $request->title,
            'avatar' => $request->avatar,
            'description' => $request->description,
            'date' => $request->date,
            'time' => $request->time,
            'location' => $request->location,
            'organizer' => $request->organizer,
            'organizer_email' => $request->organizer_email,
            'organizer_approval' => $request->organizer_approval,
            'user_id' => $request->user_id,
            'invite_code' => $request->invite_code
        ]);

        $event2 = Event::withCount('attendees')->find($event->id);

        if ($event2) {
            return redirect('/')->with('success', 'Event created successfully');
        } else {
            return view('dashboard', [
                'events' => null,
            ])->with('error', 'Event not created');
        }
    }
}

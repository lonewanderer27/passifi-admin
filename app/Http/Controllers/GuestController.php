<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Guest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GuestController extends Controller
{
    public function approveGuest($event_id, $guest_id)
    {
        $event = Event::find($event_id);

        if (!$event) {
            return response()->json([
                "error" => "Event not found!",
                "message" => "Event not found!",
                "success" => false
            ], 404);
        }

        $guest = Guest::find($guest_id);

        if (!$guest) {
            return response()->json([
                "error" => "Guest not found!",
                "message" => "Guest not found!",
                "success" => false
            ], 404);
        }

        $guest->approved = true;
        $guest->pending = false;
        $guest->save();

        return response()->json([
            "event" => $event,
            "guest" => $guest,
            "error" => null,
            "success" => true
        ]);
    }

    public function denyGuest($event_id, $guest_id)
    {
        $event = Event::find($event_id);

        if (!$event) {
            return response()->json([
                "error" => "Event not found!",
                "message" => "Event not found!",
                "success" => false
            ], 404);
        }

        $guest = Guest::find($guest_id);

        if (!$guest) {
            return response()->json([
                "error" => "Guest not found!",
                "message" => "Guest not found!",
                "success" => false
            ], 404);
        }

        $guest->approved = false;
        $guest->pending = false;
        $guest->save();

        return response()->json([
            "event" => $event,
            "guest" => $guest,
            "error" => null,
            "success" => true
        ]);
    }

    public function getApprovedGuestsByEvent($event_id)
    {
        $event = Event::find($event_id);

        if (!$event) {
            return response()->json([
                "error" => "Event not found!",
                "message" => "Event not found!",
                "success" => false
            ], 404);
        }

        $guests = Guest::where('event_id', $event_id)->where('approved', true)->get();

        return response()->json([
            "event" => $event,
            "guests" => $guests,
            "error" => null,
            "success" => true
        ]);
    }

    public function getPendingGuestsByEvent($event_id)
    {
        $event = Event::find($event_id);

        if (!$event) {
            return response()->json([
                "error" => "Event not found!",
                "message" => "Event not found!",
                "success" => false
            ], 404);
        }

        $guests = Guest::where('event_id', $event_id)->where('pending', true)->get();

        return response()->json([
            "event" => $event,
            "guests" => $guests,
            "error" => null,
            "success" => true
        ]);
    }

    public function getDeniedGuestsByEvent($event_id)
    {
        $event = Event::find($event_id);

        if (!$event) {
            return response()->json([
                "error" => "Event not found!",
                "message" => "Event not found!",
                "success" => false
            ], 404);
        }

        $guests = Guest::where('event_id', $event_id)->where('approved', false)->get();

        return response()->json([
            "event" => $event,
            "guests" => $guests,
            "error" => null,
            "success" => true
        ]);
    }

    public function getAllGuestsByEvent($event_id)
    {
        $event = Event::find($event_id);

        if (!$event) {
            return response()->json([
                "error" => "Event not found!",
                "message" => "Event not found!",
                "success" => false
            ], 404);
        }

        $guests = Guest::where('event_id', $event_id)->get();

        return response()->json([
            "event" => $event,
            "guests" => $guests,
            "error" => null,
            "success" => true
        ]);
    }

    public function joinUsingInviteCode(Request $request)
    {
        $requestData = [
            'invite_code' => $request->invite_code,
            'user_id' => $request->user_id,
            // Add any other relevant request data here
        ];

        info('JoinUsingInviteCode Request Data:', $requestData);

        $event = Event::where('invite_code', $request->invite_code)->first();

        if (!$event) {
            return response()->json([
                "error" => "Event not found!",
                "message" => "Event not found!",
                "success" => false
            ], 404);
        }

        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json([
                "error" => "User not found!",
                "message" => "User not found!",
                "success" => false
            ], 404);
        }

        // find if an existing guest record exists for this user and event
        $guest = Guest::where('event_id', $event->id)->where('user_id', $user->id)->first();

        // check whether the event requires approval to join
        if ($event->organizer_approval){
            if ($guest && $guest->pending && !$guest->approved) {
                // indicate to user that they are already a guest but pending approval
                // return error
                return response()->json([
                    "error" => "You're already a guest but pending approval!",
                    "message" => "You're already a guest but pending approval!",
                    "success" => false
                ], 422);
            }

            if ($guest && $guest->approved && !$guest->pending) {
                return response()->json([
                    "error" => "Guest already exists!",
                    "message" => "Guest already exists!",
                    "success" => false
                ], 422);
            }

            $guest = Guest::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
                'approved' => false,
                'pending' => true
            ]);

            // send a response that the guest has been created along with the event and user details
            // but it's under review

            return response()->json([
                "event" => $event,
                "guest" => $guest,
                "error" => null,
                "success" => true,
                "message" => "Your request to join the event is under review. You will be notified once you are approved."
            ], 201); // 201 Created
        } else {
            if ($guest && $guest->approved) {
                return response()->json([
                    "error" => "You're already joined in this event!",
                    "message" => "You're already joined in this event!",
                    "success" => false
                ], 422);
            }

            $guest = Guest::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
                'approved' => true,
                'pending' => false
            ]);

            // send a response that the guest has been added to the list
            return response()->json([
                "event" => $event,
                "guest" => $guest,
                "error" => null,
                "success" => true,
                "message" => "Your request to join the event has been approved!"
            ]);
        }
    }

    public function store(Request $request, $event_id) {

        $event = Event::find($event_id);

        if (!$event) {
            return response()->json([
                "error" => "Event not found!",
                "message" => "Event not found!",
                "success" => false
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'emails' => 'required|array',
            'emails.*' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "error" => $validator->errors(),
                "success" => false
            ], 422);
        }

        $guests = [];

        foreach ($request->emails as $email) {
            $user = User::where('email', $email)->first();

            if ($user) {
                $guests = Guest::create([
                    'event_id' => $event_id,
                    'user_id' => $user->id,
                    'approved' => true,
                    'pending' => false
                ]);

                $guests[] = $guests;
            } else {
                // Handle the case where the user with the given email is not found
                // You may choose to skip or log these cases based on your requirements
            }
        }

        return response()->json([
            "event" => $event,
            "guests" => $guests,
            "error" => null,
            "success" => true
        ], 201); // 201 Created
    }
}

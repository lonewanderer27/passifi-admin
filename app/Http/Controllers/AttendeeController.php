<?php

namespace App\Http\Controllers;

use App\Events\AttendeeScanned;
use App\Models\Attendee;
use App\Models\Guest;
use App\Models\InvitedGuest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendeeController extends Controller
{
    public function createByEvent(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'event_id' => 'required|integer|exists:events,id'
        ]);

        // Add a custom rule to check for uniqueness of user_id and event_id
        $validator->addExtension('unique_attendee', function ($attribute, $value, $parameters) {
            return Attendee::where('user_id', $parameters[0])
                ->where('event_id', $parameters[1])
                ->doesntExist();
        });

        $validator->sometimes('user_id', 'unique_attendee:'.$request->user_id.','.$request->event_id, function ($input) {
            return true; // This callback is executed only when validation is required
        });

        if ($validator->fails()) {
            return response()->json([
                "error" => "Validation failed",
                "errors" => $validator->errors(),
                "success" => false,
            ], 422); // 422 Unprocessable Entity
        }

        // Check if the user exists in guests and is not pending and is approved
        $attendee = Guest::where('user_id', $request->user_id)
            ->where('event_id', $request->event_id)
            ->where('pending', false)
            ->where('approved', true)
            ->exists();

        if (!$attendee) {
            return response()->json([
                "error" => "User not found in guests",
                "message" => "User not found in guests",
                "success" => false
            ], 404);
        } else {
            $attendee = Attendee::create([
                'user_id' => $request->user_id,
                'event_id' => $request->event_id,
                'status' => true,
                'verified' => true
            ]);

            $user = User::find($request->user_id);

            if ($attendee) {
                return response()->json([
                    "attendee" => $attendee,
                    "user" => $user,
                    "message" => "Attendance created",
                    "error" => null,
                    "success" => true,
                ], 200);
            } else {
                return response()->json([
                    "user" => $user,
                    "error" => "Attendance not created",
                    "message" => "Attendance not created",
                    "success" => false
                ], 500);
            }
        }
    }
}

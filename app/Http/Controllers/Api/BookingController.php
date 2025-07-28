<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    use ApiResponse;
    
    public function createBooking(Request $request, $id) {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'date' => 'required|date|after_or_equal:today',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), $validator->errors()->first(), 422);
        }  
        
        $user = auth()->user();

        if(!$user) {
            return $this->error([], 'user not found', 404);
        }

        $existingBooking =Booking::where('user_id', $user->id)->where('service_id', $id)->first();

        if ($existingBooking) {
            return $this->error([], 'You already have a booking for this service', 400);
        }

        $booking = new Booking();
        $booking->user_id = $user->id;
        $booking->service_id = $id;
        $booking->date = $request->date;
        $booking->save();

        if (!$booking) {
            return $this->error([], 'Booking not created', 500);
        }

        return $this->success($booking, 'Booking created successfully', 201);
    }

    public function myBooking()
    {
        $user = auth()->user();

        if(!$user) {
            return $this->error([], 'user not found', 404);
        }

        $data = Booking::with('service')->where('user_id', $user->id)->latest()->get();

        if(!$data){
            return $this->error([], 'Booking Not found',200);
        }

        return $this->success($data, 'Booking found successfully', 200);
    }
}

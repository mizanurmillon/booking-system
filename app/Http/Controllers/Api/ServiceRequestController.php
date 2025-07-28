<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Booking;

class ServiceRequestController extends Controller
{
    use ApiResponse;
    
    public function getAllServiceRequests()
    {
        $data = Booking::with('service', 'user')->latest()->get();

        if(!$data){
            return $this->error([], 'Service Request Not found',200);
        }

        return $this->success($data, 'Service Request found successfully', 200);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    use ApiResponse;
    
    public function getAllServices()
    {
        $user = auth()->user();

        if(!$user){
            return $this->error([], 'user not found', 404);
        }

        $data = Service::where('status','active')->latest()->get();

        if(!$data){
            return $this->error([], 'Services Not found',200);
        }

        return $this->success($data, 'Service fetch Successful!',200);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    use ApiResponse;
    
    public function createService(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:services',
            'description' => 'required|string',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), $validator->errors()->first(), 422);
        }   

        $user = auth()->user();

        if(!$user) {
            return $this->error([], 'user not found', 404);
        }

        // Create the service
        $data = Service::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        if ($data) {
            return $this->success($data, 'Service created successfully', 201);
        } else {
            return $this->error([], 'Failed to create service', 500);
        }
    }

    public function updateService(Request $request, $id)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), $validator->errors()->first(), 422);
        }   

        $user = auth()->user();

        if(!$user) {
            return $this->error([], 'user not found', 404);
        }

        // Find the service by ID   
        $data = Service::find($id);

        if (!$data) {
            return $this->error([], 'Service not found', 404);
        }

        // Update the service
        $data->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return $this->success($data, 'Service updated successfully', 200);
    }

    public function deleteService($id)
    {
        // Find the service by ID
        $data = Service::find($id);

        if (!$data) {
            return $this->error([], 'Service not found', 404);
        }

        // Delete the service
        $data->delete();

        return $this->success([], 'Service deleted successfully', 200);
    }
}

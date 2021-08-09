<?php

namespace App\Http\Controllers\API;

use App\Models\Request as RequestAccess;
use Illuminate\Http\Request;
use App\Http\Resources\RequestResource;
use Validator;

class RequestController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $requests = RequestAccess::all();
        return $this->sendResponse(RequestResource::collection($requests), 'Requests retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'class_id' => 'required|exists:classes,id'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
            $data['student_id'] = auth()->user();
            $request = RequestAccess::create($data);
            return $this->sendResponse($request, 'Access requested successfully', 201);
        } catch (\Exception $e) {
            return $this->sendError('Insert Error.', $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(RequestAccess $request)
    {
        return $this->sendResponse(new RequestResource($request), 'Request retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RequestAccess $requestAccess)
    {
        $requestAccess->update($request->all());
        return $this->sendResponse(new RequestResource($requestAccess), 'Request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(RequestAccess $request)
    {
        $request->delete();
        return $this->sendResponse($request, 'Request deleted successfully.');
    }
}

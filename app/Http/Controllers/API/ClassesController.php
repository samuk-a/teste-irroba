<?php

namespace App\Http\Controllers\API;

use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ClassesResource;

class ClassesController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!check_permission('READ', 'classes'))
            return $this->sendError('Forbidden', 'Page unaccessable', 403);
        $classes = Classes::all();
        return $this->sendResponse(ClassesResource::collection($classes), 'Classes retrieved successfully');
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
                'name' => 'required|max:255',
                'professor_id' => 'required|integer|exists:users,id',
                'weekday' => 'required|integer',
                'initial_hour' => 'required',
                'duration' => 'required|integer'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
            $class = Classes::create($data);
            return $this->sendResponse($class, 'Class created successfully', 201);
        } catch (\Exception $e) {
            return $this->sendError('Insert Error.', $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Classes  $classes
     * @return \Illuminate\Http\Response
     */
    public function show(Classes $classes)
    {
        if (!check_permission('READ', 'classes'))
            return $this->sendError('Forbidden', 'Page unaccessable', 403);
        return $this->sendResponse(new ClassesResource($classes), 'Class retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Classes  $classes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Classes $classes)
    {
        if (!check_permission('UPDATE', 'classes'))
            return $this->sendError('Forbidden', 'Page unaccessable', 403);
        $classes->update($request->all());
        return $this->sendResponse(new ClassesResource($classes), 'Class updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Classes  $classes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Classes $classes)
    {
        if (!check_permission('DELETE', 'classes'))
            return $this->sendError('Forbidden', 'Page unaccessable', 403);
        $classes->delete();
        return $this->sendResponse($classes, 'Class deleted successfully.');
    }
}

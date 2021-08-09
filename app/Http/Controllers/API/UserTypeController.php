<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\UserTypeResource;
use App\Models\Permission;
use App\Models\UserType;
use Illuminate\Http\Request;
use Validator;

class UserTypeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = UserType::all();
        return $this->sendResponse(UserTypeResource::collection($types), 'Types retrieved successfully');
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
                'permission_ids' => 'required|array'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
            $errors = [];
            foreach ($data['permission_ids'] as $id) {
                if (empty(Permission::where('id', $id)->get()->toArray())) {
                    array_push($errors, "Permission $id not found.");
                }
            }
            if (!empty($errors))
                return $this->sendError('Missing permission', $errors, 500);
            $type = UserType::create($data);
            return $this->sendResponse($type, 'Type created successfully', 201);
        } catch (\Exception $e) {
            return $this->sendError('Insert Error.', $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserType  $userType
     * @return \Illuminate\Http\Response
     */
    public function show(UserType $userType)
    {
        return $this->sendResponse(new UserTypeResource($userType), 'Type retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserType  $userType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserType $userType)
    {
        $userType->update($request->all());
        return $this->sendResponse(new UserTypeResource($userType), 'Type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserType  $userType
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserType $userType)
    {
        $userType->delete();
        return $this->sendResponse($userType, 'Type deleted successfully.');
    }
}

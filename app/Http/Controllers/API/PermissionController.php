<?php

namespace App\Http\Controllers\API;

use App\Models\Permission;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\Request;
use Validator;

class PermissionController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!check_permission('CREATE', 'permissions'))
            return $this->sendError('Forbidden', 'Page unaccessable', 403);
        $permissions = Permission::all();
        return $this->sendResponse(PermissionResource::collection($permissions), 'Permissions retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!check_permission('CREATE', 'permissions'))
            return $this->sendError('Forbidden', 'Page unaccessable', 403);
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'name' => 'required|max:255',
                'permission_type' => 'required|in:CREATE,READ,UPDATE,DELETE',
                'permission_area' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
            $permission = Permission::create($data);
            return $this->sendResponse($permission, 'Permission created successfully', 201);
        } catch (\Exception $e) {
            return $this->sendError('Insert Error.', $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        if (!check_permission('READ', 'permissions'))
            return $this->sendError('Forbidden', 'Page unaccessable', 403);
        return $this->sendResponse(new PermissionResource($permission), 'Permission retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        if (!check_permission('UPDATE', 'permissions'))
            return $this->sendError('Forbidden', 'Page unaccessable', 403);
        $permission->update($request->all());
        return $this->sendResponse(new PermissionResource($permission), 'Permission updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        if (!check_permission('DELETE', 'permissions'))
            return $this->sendError('Forbidden', 'Page unaccessable', 403);
        $permission->delete();
        return $this->sendResponse($permission, 'Permission deleted successfully.');
    }
}

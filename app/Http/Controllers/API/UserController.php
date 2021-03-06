<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Validator;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!check_permission('READ', 'users'))
            return $this->sendError('Forbidden', 'Page unaccessable', 403);
        $users = empty($request->all()) ? User::all() : User::where('type', (int)$request->query('type'))->get();
        return $this->sendResponse($users, 'Users retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!check_permission('CREATE', 'users'))
            return $this->sendError('Forbidden', 'Page unaccessable', 403);
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'type' => 'required|exists:user_types,id'
        ]);

        if ($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $data['password'] = bcrypt('Irroba.1111');
        $user = User::create($data);
        return $this->sendResponse($user, 'User created successfully.', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        if (!check_permission('READ', 'users'))
            return $this->sendError('Forbidden', 'Page unaccessable', 403);
        return $this->sendResponse(new UserResource($user), 'User retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if (!check_permission('UPDATE', 'users'))
            return $this->sendError('Forbidden', 'Page unaccessable', 403);
        $user->update($request->all());
        return $this->sendResponse(new UserResource($user), 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (!check_permission('DELETE', 'users'))
            return $this->sendError('Forbidden', 'Page unaccessable', 403);
        $user->delete();
        return $this->sendResponse($user, 'User deleted successfully.');
    }
}

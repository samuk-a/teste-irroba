<?php

namespace App\Http\Controllers\API;

use App\Models\Administrator;
use App\Models\Professor;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Validator;
use DB;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return $this->sendResponse(UserResource::collection($users), 'Users retrieved successfully');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listProfessors()
    {
        $users = Professor::join('users', 'users.id', '=', 'professors.user_id')
                ->select('users.*')
                ->get()->toArray();

        return $this->sendResponse($users, 'Professors retrieved successfully');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listStudents()
    {
        $users = Student::join('users', 'users.id', '=', 'students.user_id')
                ->select('users.*')
                ->get()->toArray();

        return $this->sendResponse(UserResource::collection($users), 'Students retrieved successfully');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listAdministrators()
    {
        $users = Administrator::join('users', 'users.id', '=', 'administrators.user_id')
                ->select('users.*')
                ->get()->toArray();

        return $this->sendResponse(UserResource::collection($users), 'Administrators retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required',
            'email' => 'required|email',
            'type' => 'required'
        ]);

        if ($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $data['password'] = bcrypt('Irroba.1111');
        DB::beginTransaction();
        try {
            $user = User::create($data);
            switch ($data['type']) {
                case 'P':
                    Professor::create(['user_id' => $user->id]);
                    break;
                case 'A':
                    Administrator::create(['user_id' => $user->id]);
                    break;
                default:
                    Student::create(['user_id' => $user->id]);
            }
            DB::commit();
            return $this->sendResponse($user, 'User created successfully.', 201);
        } catch (Exception) {
            DB::rollback();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}

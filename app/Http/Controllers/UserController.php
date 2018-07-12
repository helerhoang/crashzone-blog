<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::excludeMe()->get();
        return response_success([
            'users' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userInfo = $request->only(['name', 'email', 'password']);
        $validator = Validator::make($userInfo, [
            'name' => 'required|min:3',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:5'
        ]);

        if ($validator->fails()) {
            return response_error(['errors' => $validator->errors()]);
        };


        $new = User::create(['name' => $userInfo['name'], 'email' => $userInfo['email'], 'password' => bcrypt($userInfo['password'])]);

        return response_success(['user' => $new]);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function show(Users $users)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Users $users)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //

        $user = User::find($id);
        if ($user && $user->delete()) {
            $users = User::all();
            return response_success([
                'users' => $users
            ], 'deleted user id ' . $id);
        }

        return response_error([], 'can not find user id ' . $id, 401);

    }
}

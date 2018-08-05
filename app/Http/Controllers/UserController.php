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
        $users = User::excludeMe()->with('roles')->get();
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


        $newUser = User::create(['name' => $userInfo['name'], 'email' => $userInfo['email'], 'password' => bcrypt($userInfo['password'])]);

        return response_success(['user' => $newUser]);


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
    public function update(Request $request, User $user)
    {

        $userInfo = $request->only(['name', 'password']);
        $validator = Validator::make($userInfo, [
            'name' => 'required|min:3',
            'password' => 'min:5'
        ]);

        if ($validator->fails()) {
            return response_error(['errors' => $validator->errors()]);
        };


        $user->update(['name' => $userInfo['name'], 'password' => bcrypt($userInfo['password'])]);

        return response_success(['user' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        return $user->delete()
            ? response_success(['user' => $user], 'deleted user id ' . $user->id)
            : response_error([], 'can not find user id ' . $user->id, 401);
    }


    /*
     *  SOFT DELETE
     */

    public function indexDeleted()
    {
        $users = User::onlyTrashed()->get();
        return response_success(['users' => $users]);
    }

    public function destroyDeleted($id)
    {
        $user = User::withTrashed()->find($id);
        return $user->forceDelete() ?
            response_success(['user' => $user], 'deleted permanently user id ' . $user->id) : response_error([], 'can not find user id ' . $user->id, 401);
    }


    public function restoreDeleted($id)
    {
        $user = User::withTrashed()->find($id);
        return $user->restore() ?
            response_success(['user' => $user], 'retore deleted user id ' . $user->id) : response_error([], 'can not find user id ' . $user->id, 401);
    }
}

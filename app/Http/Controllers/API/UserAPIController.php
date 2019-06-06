<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\CreateUserAPIRequest;
use App\Http\Requests\UpdateUserAPIRequest;
use App\User;
use App\Http\Controllers\Controller;

/**
 * Class UserAPIController
 * @package App\Http\Controllers\API
 */
class UserAPIController extends Controller
{
    /**
     * UserAPIController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(User::latest()->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateUserAPIRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserAPIRequest $request)
    {
        $input = $request->all();
        $input['password'] = bcrypt($request->password);
        User::create($input);
        return response('success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response(User::find($id));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        return auth('api')->user();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserAPIRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserAPIRequest $request, $id)
    {
        $user = User::find($id);
        $user->update($request->all());
        return response($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        $user->delete();
        return response($user);
    }
}

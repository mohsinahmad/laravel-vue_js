<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\CreateUserAPIRequest;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class UserAPIController
 * @package App\Http\Controllers\API
 */
class UserAPIController extends Controller
{
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return response(User::all());
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

<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];

        $data['users']= User::get();

        $status = 'success';
        $http_code = 200;

        return response()->json( compact( 'status', 'data' ), $http_code );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [];

        $rules = array(
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
        );

        $validator = Validator::make($request->all(),$rules);

        if ($validator->fails()) {
            $status = 'fail';
            $message = 'Validation error(s)';
            $data = $validator->errors();
            $http_code = 400; // The server cannot process the request due to a client error
            return response()->json( compact( 'status', 'message', 'data' ), $http_code);
        }

        try{

            User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email
            ]);

            $status = 'success';
            $message = 'user created successfully!';
            $http_code = 201;

        }catch (\Exception $e){
            $message = $e->getMessage();
            $http_code = 500; // Internal server error
        }

        return response()->json( compact( 'status', 'data', 'message' ), $http_code );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = [];

        $data['users']= User::where('id', $id)->first();

        $status = 'success';
        $http_code = 200;

        return response()->json( compact( 'status', 'data' ), $http_code );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = [];

        $rules = array(
            'email' => 'email|unique:users,email'.$id,
        );

        $validator = Validator::make($request->all(),$rules);

        if ($validator->fails()) {
            $status = 'fail';
            $message = 'Validation error(s)';
            $data = $validator->errors();
            $http_code = 400; // The server cannot process the request due to a client error
            return response()->json( compact( 'status', 'message', 'data' ), $http_code);
        }

        $user_update = User::find($id);

        $user_update->first_name = isset($request->first_name) ? $request->first_name : $user_update->first_name;
        $user_update->last_name = isset($request->email) ? $request->last_name : $user_update->last_name;
        $user_update->email = isset($request->email) ? $request->email : $user_update->email;

        try{
            $user_update->save();

            $status = 'success';
            $message = 'user updated successfully!';
            $http_code = 201;

        }catch (\Exception $e){
            $message = $e->getMessage();
            $http_code = 500; // Internal server error
        }

        return response()->json( compact( 'status', 'data', 'message' ), $http_code );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $user = User::find($id); // find the user first.

        if($user){
            $user->delete();
            $status = 'success';
            $message = 'User deleted successfully!';
            $http_code = 200;
        } else {

            $status = 'error';
            $message = 'User Not found!';
            $http_code = 500; // Internal server error
        }

        return response()->json( compact( 'status', 'message','data' ), $http_code );
    }
}

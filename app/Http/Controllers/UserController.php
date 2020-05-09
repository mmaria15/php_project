<?php


namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function index()
    {
        return response()->json([
            'users' => User::all(),
        ], 200);

    }


    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validation->errors(),
            ], 422);
        }
        $user = new User;
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response()->json([
            'error' => false,
            'user' => $user,
        ], 200);
    }

    public function delete($id)
    {

        $users = User::findOrFail($id);

        if (is_null($users)) {
            return response()->json([
                'error' => true,
                'message' => "Record with id # $id not found",
            ], 404);
        }
        $users->delete();
        return response()->json([
            'error' => false,
            'message' => "User record successfully deleted id # $id",
        ], 200);
    }

    public function update(Request $request,$id)
    {
        $validation = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validation->errors(),
            ], 422);
        }
        $user = User::find($id);
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response()->json([
            'error' => true,
            'message' => "User record successfully updated # $id",
        ], 200);
    }


    /*public function update(Request $request, $id)
    {
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        //$password = Hash::make($request->input('password'));
        DB::update('update users set first_name = ?,last_name=?,email= ? ,where id = ?',[$first_name,$last_name,$email,$id]);
    }*/
}

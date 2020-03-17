<?php

namespace App\Http\Controllers;

use App\Constants;
use App\User;
use App\UserRole;
use App\UserRoleIds;
use Illuminate\Http\Request;
use function response;

class UserController extends Controller
{
    public function addNewUser(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "username" => "required",
            "password" => "required",
            "user_role_id" => "required",
        ]);

        if ($validator->fails())
            return response()->json(["success" => false, "status" => "error", "message" => "Invalid input"]);

        if ($request->input("role_id") == 3 || $request->input("role_id") == 4)
            return response()->json(["success" => false, "status" => "error", "message" => "Cannot create user of such type."]);

        $user = User::where("username", $request->input("username"))->first();
        if ($user) {
            return response()->json(["success" => false, "status" => "error", "message" => "User already exists!"]);
        } else {
            $newUser = new User;

            $newUser->username = $request->input("username");
            $newUser->password = \Hash::make($request->input("password"));
            $newUser->user_role_id = $request->input("user_role_id");
            $newUser->save();
            return response()->json(["success" => true, "status" => "ok", "message" => "User added!"]);
        }
    }

    public function deleteUser(Request $request, $id)
    {
        $user = User::where("id", $id)->first();
        if (!$user)
            return response()->json(["success" => false, "status" => "error", "message" => "User not found!"]);
        else {
            $user->delete();
            return response()->json(["success" => true, "status" => "ok", "message" => "User deleted!"]);
        }
    }

    public function updateUser(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            "username" => "required",
            "password" => "required",
        ]);

        if ($validator->fails())
            return response()->json(["success" => false, "status" => "error", "message" => "Invalid input"]);

        $user = User::where("id", $id)->first();
        $user->username = $request->input("username");
        $user->password = \Hash::make($request->input("password"));
        $user->save();
        return response()->json(["success" => true, "status" => "ok", "message" => "User updated!"]);
    }

    public function getUser($id)
    {
        $user = User::where("id", $id)->first();
        if (!$user)
            return response()->json(["success" => false, "status" => "error", "message" => "User doesn't exist!"]);
        else
            return response()->json(["success" => true, "status" => "ok", "data" => $user]);

    }

    public function getAllUserRoles()
    {
        $roles = UserRole::all();
        return response()->json($roles);
    }
}

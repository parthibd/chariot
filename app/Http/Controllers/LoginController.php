<?php

namespace App\Http\Controllers;

use App\User;
use Config;
use Hash;
use Illuminate\Http\Request;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use function response;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where("username", $request->input("username"))->first();
        if (!$user)
            return response()->json(["success" => false, "status" => "error", "message" => "No such user found!"]);
        else if (Hash::check($request->input("password"), $user->password)) {
            $builder = new Builder();
            $builder
                ->setIssuedAt(time())
                ->set("user_id", $user->id)
                ->set("username", $user->username)
                ->set("user_role_id", $user->role->id)
                ->set("user_role_name", $user->role->name);

            $token = $builder
                ->sign(new Sha256(), Config::get("jwt.secret"))
                ->getToken();
            return response()->json(["success" => true, "status" => "ok", "token" => (string)$token]);
        } else return response()->json(["success" => false, "status" => "error", "message" => "Wrong password"]);
    }
}

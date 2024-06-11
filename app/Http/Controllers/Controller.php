<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function logout(Request $request){
        if ($request->user()->tokens()->delete()) {
            return response()->json(['message' => 'Successfully logout'],200);
        }else {
            return response()->json(['message' => 'Unable to logout'],500);
        }
    }
}

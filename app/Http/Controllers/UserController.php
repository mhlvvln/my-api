<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function getMessages(Request $request){
        $user = User::find($request->id);
        $messages = $user->messages_from;
        $texts = [];
        foreach($messages as $message => $text){
            
        }
        return response()->json(
            [
                'status' => true,
                'messages' => $texts,
            ],
            200
        );
    }

    public function get(Request $request)
    {
        $user = User::find($request->id);
        return response()->json($user);
    }

    public function setPhoto(Request $request)
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        if ($user)
        {
            $user->photo = $request->input("photoUrl");
            $user->save();
            return response()->json(['status' => true, 'message' => true], 200);
        }else{
            return response()->json(['status'=>false, 'message'=>'ошибка, нет пользователя'], 403);
        }
    }

    public function getByIds(Request $request)
    {
        $arr = explode(",", $request->ids);
        $users = User::whereIn('id', $arr)->get();
        return $users;
    }
}

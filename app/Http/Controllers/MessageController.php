<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function create(Request $request){
        $message = Message::create(
            [
                'from_id'=> $request->from_id,
                'to_id'=> $request->to_id,
                'text'=> $request->text,                
            ]
            );
        return response()->json(
            [
                'status'=>true,
                'message'=>$message,
            ],200);
    }

    public function get(Request $request){
        $message = Message::find($request->id);
        return response()->json(
            [
                'status'=>true,
                'message'=>$message,
            ], 200);
    }

}

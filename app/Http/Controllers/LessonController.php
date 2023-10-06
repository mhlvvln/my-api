<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class LessonController extends Controller
{
    public function create(Request $request)
    {
        $teacher_id = auth()->user()->id;

        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => '',
            'lessonType' => 'required|string',
            'endTime' => 'required|integer',
            'canRetry' => 'required|boolean',
            'minScore' => 'required|integer',
            'script' => 'string'
        ], [
            'required' => 'Поле :attribute обязательно для заполнения.',
            'string' => 'Поле :attribute должно быть строкой.',
            'min' => 'Минимальная длина поля :attribute должна быть :min символов.',
            'boolean' => 'Поле :attribute должно быть логическим значением.',
            'integer' => 'Поле :attribute должно быть числовым значением'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Проблема с данными',
                'errors' => $validator->errors()
            ], 400);
        }

        $client = new Client(['verify' => false]);

        #$url = "https://26.22.215.125:7278/api/Lesson/Create?courseId=4";

        try{
            $response = $client->post(env('API_BASE_URL') . 'Lesson/Create', [
                'query' => [
                    'courseId' => $request->courseId,
                    'token' => "4XvL1RHinNFNM6jALE4hPLM-ncIWZxGfk"
                ],
                'json' => [
                    "title" => $request->title,
                    "lessonType" => $request->lessonType,
                    "endTime" => $request->endTime,
                    "canRetry" => $request->canRetry,
                    "minScore" => $request->minScore,
                    "script" => $request->script,
                    "description" => $request->description
                ]
            ]);

            if ($response->getStatusCode() != 200)
            {
                return response()->json([
                    'status' => false,
                    'message' => 'неудача'
                ], 403);
            }

            else return response()->json([
                "status" => true,
                "response" => json_decode($response->getBody()->getContents())
            ], 200);
            
        } catch(GuzzleException $e)
        {
            return response()->json(["status"=>false, "message"=>"Невозможно добавить урок, ошибка в параметрах"]);
        }
    }

    public function get(Request $request)
    {
        try{
            $client = new Client(['verify' => false]);
            $response = $client->get(env('API_BASE_URL') . 'Lesson', [
                'query' => [
                    'id' => $request->id,
                    'token' => "4XvL1RHinNFNM6jALE4hPLM-ncIWZxGfk"
                ],
            ]);
            return response()->json([
                "status" => true,
                "response" => json_decode($response->getBody()->getContents())
            ], 200);
            return $response->getBody()->getContents();
        } catch(GuzzleException $e)
        {
            return response()->json(["status"=>false, "message"=>"Нет такого урока", "error"=>$e->getMessage()]);
        }
    }

    public function getScript(Request $request)
    {
        $client = new Client(['verify' => false]);
        $response = $client->get(env('API_BASE_URL') . 'Lesson/script', [
            'query' => [
                'id' => $request->id,
                'token' => "4XvL1RHinNFNM6jALE4hPLM-ncIWZxGfk"
            ],
        ]);
        return response()->json([
            "status" => true,
            "response" => ($response->getBody()->getContents())
        ], 200);
        return $response->getBody()->getContents();
    }

    public function delete(Request $request)
    {
        $client = new Client(['verify' => false]);
        $response = $client->delete(env('API_BASE_URL') . 'Lesson', [
            'query' => [
                'id' => $request->id,
                'token' => "4XvL1RHinNFNM6jALE4hPLM-ncIWZxGfk"
            ],
        ]);

        if ($response->getStatusCode() == 200)
            return response()->json([
            "status" => true,
            "response" => "ok"
        ], 200);

        else return response()->json([
            "status" => false,
            "message" => "Ошибка при удалении. Вероятно, не передан параметр id, либо элемента удаляемого не существует"
        ], 500);
    }
}

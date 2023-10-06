<?php

namespace App\Http\Controllers;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class HomeworkController extends Controller
{
    public function add(Request $request)
    {
        $client = new Client(['verify' => false]);
        try{
            $response = $client->post(env('API_BASE_URL') . 'Homework/homework', [
                'query' => [
                    'teacherId' => auth()->user()->id,
                    'token' => "4XvL1RHinNFNM6jALE4hPLM-ncIWZxGfk",
                    'courseId' => $request->courseId,
                ],
                'json' => [
                    "title" => $request->title,
                    "description" => $request->description,
                    "deadline" => $request->deadline,
                    "photo" => $request->photo
                ]
            ]);

            return response()->json([
                "status" => true,
                "response" => json_decode($response->getBody()->getContents())
            ], 200);
        } catch(GuzzleException $e)
        {
            return response()->json([
                "status" => false,
                "message" => "Ошибка. У преподавателя может не быть такого курса, либо введены не все данные"
            ], 403);
        }
    }

    public function suggest(Request $request)
    {
        $client = new Client(['verify' => false]);

        try{
            $response = $client->post(env('API_BASE_URL') . 'Homework/suggest', [
                'query' => [
                    'homeworkId' => $request->homeworkId,
                    'token' => "4XvL1RHinNFNM6jALE4hPLM-ncIWZxGfk",
                    'userId' => auth()->user()->id,
                ],
                'json' => [
                    "comment" => $request->comment
                ]
            ]);

            return response()->json([
                "status" => true,
                "response" => json_decode($response->getBody()->getContents())
            ], 200);
        } catch(GuzzleException $e)
        {
            return response()->json([
                "status" => false,
                "message" => "Вероятно, переданы не все параметры, либо ученик не имеет доступа к этому домашнему заданию.",
                "error" => $e->getMessage()
            ], 200);
        }
    }


    public function check(Request $request)
    {
        $client = new Client(['verify' => false]);

        try{
            $response = $client->post(env('API_BASE_URL') . 'Homework/check', [
                'query' => [
                    'solvedId' => $request->solvedId,
                    'token' => "4XvL1RHinNFNM6jALE4hPLM-ncIWZxGfk",
                    'scoreOf5' => $request->scoreOf5,
                ],
                'json' => [
                    "checkedComment" => $request->comment
                ]
            ]);
            
            return response()->json([
                "status" => true,
                "response" => json_decode($response->getBody()->getContents())
            ], 200);
        } catch(GuzzleException $e)
        {
            return response()->json([
                "status" => false,
                "message" => "Вероятно, переданы не все параметры, либо чепуха случилась",
                "error" => $e->getMessage()
            ], 200);
        }
    }
}

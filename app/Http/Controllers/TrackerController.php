<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class TrackerController extends Controller
{

    public function newTrack(Request $request)
    {
        $client = new Client(['verify' => false]);

        $user_id = auth()->user()->id;
        try{
            
            $response = $client->post(env('API_BASE_URL') . 'Tracker/NewTrack', [
                'query' => [
                    'token' => "4XvL1RHinNFNM6jALE4hPLM-ncIWZxGfk",
                ],
                'json' => [
                    "lessonId" => $request->lessonId,
                    "userId" => $user_id,
                    "scoreOf100" => $request->scoreOf100
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
                "message" => $e->getMessage()
            ], 403);
        }
    }


    public function courseProgress(Request $request)
    {
        $client = new Client(['verify' => false]);

        $user_id = auth()->user()->id;

        try{
            $response = $client->get(env('API_BASE_URL') . 'Tracker/courseProgress', [
                'query' => [
                    'token' => "4XvL1RHinNFNM6jALE4hPLM-ncIWZxGfk",
                    'courseId' => $request->courseId,
                    'userId' => $user_id
                ]
            ]);

            return response()->json([
                "status" => true,
                "response" => json_decode($response->getBody()->getContents())
            ], 200);
        } catch(GuzzleException $e)
        {
            return response()->json(["status" => false, "message" => "пользователь не присоединен к курсу"], 403);
        }
    }


    public function teacherCourseStats(Request $request)
    {
        $client = new Client(['verify' => false]);

        try{
            $response = $client->get(env('API_BASE_URL') . 'Tracker/teacherCourseStats', [
                'query' => [
                    'token' => "4XvL1RHinNFNM6jALE4hPLM-ncIWZxGfk",
                    'courseId' => $request->courseId,
                ]
            ]);

            return response()->json([
                "status" => true,
                "response" => json_decode($response->getBody()->getContents())
            ], 200);
        } catch(GuzzleException $e)
        {
            return response()->json(["status" => false, "message" => $e->getMessage()], 403);
        }
    }
}

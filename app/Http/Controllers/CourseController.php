<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class CourseController extends Controller
{
    public function create(Request $request)
    {
        $owner_id = auth()->user()->id;
        
        # мне от Вани пришла фотография и данные
        $client = new Client(['verify' => false]);
       

        $response = $client->post(env('API_BASE_URL') . 'Course', [
            'query' => [
                'token' => "4XvL1RHinNFNM6jALE4hPLM-ncIWZxGfk"
            ],
            'json' => [
                "title" => $request->title,
                "description" => $request->description,
                "ownerId" => $owner_id
            ]
        ]);

        return response()->json([
            "status" => true,
            "response" => json_decode($response->getBody()->getContents())
        ], 200);

    }


    public function get(Request $request)
    {
        $client = new Client(['verify' => false]);

        $response = $client->get(env('API_BASE_URL') . 'Course', [
            'query' => [
                'token' => "4XvL1RHinNFNM6jALE4hPLM-ncIWZxGfk",
                'id' => $request->id
            ]
        ]);

        return response()->json([
            "status" => true,
            "response" => json_decode($response->getBody()->getContents())
        ], 200);

    }

    public function getUserCourses(Request $request)
    {
        $user_id = auth()->user()->id;
        
        $client = new Client(['verify' => false]);
        $response = $client->get(env('API_BASE_URL') . 'Course/userCourses', [
            'query' => [
                'token' => "4XvL1RHinNFNM6jALE4hPLM-ncIWZxGfk",
                'userId' => $user_id
            ]
        ]);
        return response()->json([
            "status" => true,
            "response" => json_decode($response->getBody()->getContents())
        ], 200);
    }


    public function delete(Request $request)
    {
        $client = new Client(['verify' => false]);
        $response = $client->delete(env('API_BASE_URL') . 'Course', [
            'query' => [
                'token' => "4XvL1RHinNFNM6jALE4hPLM-ncIWZxGfk",
                'id' => $request->id
            ]
        ]);

        return response()->json([
            "status" => true,
            "response" => json_decode($response->getBody()->getContents())
        ], 200);
    }

    public function getAll(Request $request)
    {

        $user = auth()->user();

        $client = new Client(['verify' => false]);

        if ($user->admin)
        {
            $response = $client->get(env('API_BASE_URL') . 'Course/AllTeacher', [
                'query' => [
                    'token' => "4XvL1RHinNFNM6jALE4hPLM-ncIWZxGfk",
                    'id' => $request->id
                ]
            ]);
        }

        else{
            $response = $client->get(env('API_BASE_URL').'Course/AllTeacher', [
                'query' => [
                    'token' => "4XvL1RHinNFNM6jALE4hPLM-ncIWZxGfk",
                    'id' => $user->id
                ]
            ]);
        }

        return response()->json([
            "status" => true,
            "response" => json_decode($response->getBody()->getContents())
        ], 200);
    }


    public function getInvite(Request $request)
    {
        $client = new Client(['verify' => false]);
        
        try{
            $response = $client->get(env('API_BASE_URL').'Course/invite', [
                'query' => [
                    'token' => "4XvL1RHinNFNM6jALE4hPLM-ncIWZxGfk",
                    'courseId' => $request->courseId 
                ]
            ]);

            return response()->json([
                "status" => true,
                "response" => json_decode($response->getBody()->getContents())
            ], 200);
        } catch(Exception $e)
        {
            return response()->json([
                "status" => false,
                "message" => "невозможно присоединиться к куру. Вероятно, он еще не создан",
                "error" => $e->getMessage()
            ], 403);
        }
    }


    public function connectByInvite(Request $request)
    {
        $client = new Client(['verify' => false]);
        $user_id = auth()->user()->id;
       
        try{
            $response = $client->get(env('API_BASE_URL').'Course/invite/' . $request->guid, [
                'query' => [
                    'userId' => $user_id ,
                    
                ]
            ]);

            return response()->json([
                "status" => true,
                "message" => "успешно",
                "response" => $response->getBody()->getContents()
            ], 200);
        } catch(Exception $e)

        {
            return response()->json([
                "status" => false,
                "message" => "невозможно присоединиться к куру. Вероятно, он еще не создан",
                "error" => $e->getMessage()
            ], 403);
        }
    }


    public function getCourses(Request $request)
    {
        $client = new Client(['verify' => false]);
        
        if (!$request->count)
        {$count = 10;}
        else{$count = $request->count;}

        $response = $client->get(env('API_BASE_URL').'Repository/allcourses/', [
            'query' => [
                'token' => "4XvL1RHinNFNM6jALE4hPLM-ncIWZxGfk",
                "count" => $count
            ]
        ]);

        return response()->json([
            "status" => true,
            "message" => "успешно",
            "response" => json_decode($response->getBody()->getContents())
        ], 200);
    }
}

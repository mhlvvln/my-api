<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ContentController extends Controller
{
    public function get(Request $request)
    {

        $client = new Client(['verify' => false]);

        $response = $client->get(env('API_BASE_URL') . 'Content', [
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

    public function getCourse(Request $request)
    {
        $client = new Client(['verify' => false]);

        $response = $client->get(env('API_BASE_URL') . 'Content/course', [
            'query' => [
                'token' => "4XvL1RHinNFNM6jALE4hPLM-ncIWZxGfk",
                'courseId' => $request->input("id")
            ]
        ]);
        
        return response()->json([
            "status" => true,
            "response" => json_decode($response->getBody()->getContents())
        ], 200);
    }
}

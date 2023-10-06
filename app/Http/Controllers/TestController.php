<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\User;

class TestController extends Controller
{
    public function index()
    {
        try {
            echo "haha";
            return response()->json(['message' => 'Успешный запрос'], 200);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['error' => 'Token Expired'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'Token Invalid'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'Token Absent'], 401);
        }
    }

    public function test()
    {
        $client = new Client(['verify' => false]);
        $url = env('API_BASE_URL') . 'Test?id=3'; // Замените на нужный URL

        $response = $client->get($url);
        if ($response->getStatusCode() == 200)
            return response()->json([
                "status" => true,
                "response" => json_decode($response->getBody()->getContents())
            ], 200);
        else{
            return response()->json([
                'status' => false,
                'message' => 'error'
            ]);
        }
    }
}


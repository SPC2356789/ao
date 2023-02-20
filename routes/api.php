<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/openai', function (Request $r) {
    if (empty($r->text)) {
        return sprintf("呼叫API:<br/>%s/api/openapi?text=問的問題", env("APP_URL"));
    }
    $client = new Client;
    $api_url = "https://api.openai.com/v1/completions";
    $json = <<<JSON
                {
                    "model": "text-davinci-003",
                    "prompt": "用正體中文回應我:$r->text",
                    "temperature": 0.9,
                    "max_tokens": 150,
                    "top_p": 1,
                    "frequency_penalty": 0.0,
                    "presence_penalty": 0.6,
                    "stop": [" Human:", " AI:"]
                }
            JSON;
    $json = json_decode(preg_replace('/[\x00-\x1F]/', '', $json), true);
    try {
        $r = $client->request('POST', $api_url, [
            'headers' => [
                'Authorization' => 'Bearer ' . env("OPENAI_KEY"),
            ],
            'json' => $json,
        ]);
    } catch (ClientException $e) {
        return json_decode($e->getResponse()->getBody()->getContents(), true);
    }
    return $r;
});
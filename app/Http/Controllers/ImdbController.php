<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ImdbController extends Controller
{
    public function index()
    {
        // Khởi tạo Guzzle Client
        $client = new Client();
        
        // Gửi yêu cầu GET đến API IMDb
        $response = $client->request('GET', 'https://imdb8.p.rapidapi.com/auto-complete', [
            'query' => [
                'q' => 'game'
            ],
            'headers' => [
                'x-rapidapi-host' => 'imdb8.p.rapidapi.com',
                'x-rapidapi-key' => 'f37c23ac37msh9a423d571a0f3ccp10a8f9jsn15dcae6c7f5a'
            ]
        ]);

        // Chuyển dữ liệu JSON trả về thành mảng
        $data = json_decode($response->getBody(), true);

        // Trả về dữ liệu qua view (trang index)
        return view('index', compact('data'));
    }
}

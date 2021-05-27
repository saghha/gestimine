<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;

class TestController extends Controller
{
    public function scraping () {
        $client = new Client();
        $scrawler = $client->request('GET' ,'https://usm.cl/');
        if ($client->getResponse()->getStatusCode() !== 200) throw new \Exception("Not Valid Request", 1); 
        $data = collect($scrawler->filter('h2')->each(function ($node) {
            return $node->text();
        }));
        dd($data);
    }
}

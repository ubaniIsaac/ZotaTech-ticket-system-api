<?php

namespace App\Helper;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;


class Helper {

    public static function getDomain(){
        $domain = $_SERVER['HTTP_HOST'];
        return $domain;
    }

    public static  function generateLink($title)
    { 
        $ulid = Str::ulid();
        $array = explode(' ', $title);
        $text = implode('-', $array);

        $domain = Helper::getDomain();

        $long_url = 'https://'. $domain .'/api/v1/events/'.$text. '-tickets-' .$ulid;
        $short_url = 'https://'. $domain .'/api/v1/e/'.$ulid;


        return [
            'long_url' => $long_url,
            'short_id' => $ulid,
            'short_url' => $short_url,
        ];
    }

    public static function getRandomImage()
    {
        $response = Http::get('https://robohash.org/' . Str::random(10));

    if ($response->ok()) {
        $contentType = $response->headers()['Content-Type'][0];
        $imageContent = $response->body();

        if (strpos($contentType, 'image') !== false) {
            $imageContent = mb_convert_encoding($imageContent, 'UTF-8', 'UTF-8');
            return $imageContent;
        }
    }

    return null;  // Return null or handle the error case as needed
    }
}
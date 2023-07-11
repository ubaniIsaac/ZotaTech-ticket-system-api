<?php

namespace App\Helper;

use Illuminate\Support\Str;


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

        $long_url = 'https://'. $domain .'/'.$text. '/tickets?' .$ulid;
        $short_url = 'https://'. $domain .'/'.$ulid;


        return [
            'long_url' => $long_url,
            'short_id' => $ulid,
            'short_url' => $short_url,
        ];
    }
}
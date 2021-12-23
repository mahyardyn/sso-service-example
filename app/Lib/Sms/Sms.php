<?php

namespace App\Lib\Sms;


class Sms
{

    protected
        $Username = "habibidev",
        $Password = "09366220",
        $From = 50002210004121,
        $Pattern = 'با سلام، کد تایید شما : %code%',
        $APIURL = "http://smspanel.Trez.ir/SendMessageWithUrl.ashx?";


    /**
     * Gets config parameters for sending request.
     *
     */
    public function __construct()
    {
        date_default_timezone_set("Asia/Tehran");
    }

    public function send($mobile,$code)
    {


        $parameter = [
            'PhoneNumber' => $this->From,
            'RecNumber' => $mobile,
            'MessageBody' => str_replace('%code%', $code, $this->Pattern),
            'Username' => $this->Username,
            'Password' => $this->Password
        ];

        $parameter = http_build_query($parameter);

        $url = "$this->APIURL{$parameter}";

        $handler = curl_init($url);
        curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($handler);

        if ($response >= 2000) {
            return true;
        }

        // echo $response; #get Error
        return $response;
    }
}
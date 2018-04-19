<?php

namespace checkmobi;

use GuzzleHttp\Client;

class CheckMobiRest
{
    const BASE_URL = "https://api.checkmobi.com";
    const API_VERSION = "v1";

    private $http_client;

    function __construct($auth_token, Client $client, $base_url = self::BASE_URL, $version = self::API_VERSION)
    {
        if ((!isset($auth_token)) || (!$auth_token))
            throw new CheckMobiError("no auth_token specified");

        $url = $base_url."/".$version;
        $this->http_client = $client;
    }

    public function GetAccountDetails()
    {
        return $this->http_client->request("GET", '/my-account');
    }

    public function GetCountriesList()
    {
        return $this->http_client->request("GET", '/countries');
    }

    public function GetPrefixes()
    {
        return $this->http_client->request("GET", '/prefixes');
    }

    public function CheckNumber($params)
    {
        return $this->http_client->request("POST", '/checknumber', [
            'body' => $params
        ]);
    }

    public function RequestValidation($params)
    {
        return $this->http_client->request("POST", '/validation/request', [
            'body' => $params
        ]);
    }

    public function VerifyPin($params)
    {
        return $this->http_client->request("POST", '/validation/verify', [
            'body' => $params
        ]);
    }

    public function ValidationStatus($params)
    {
        $id = $this->pop($params, "id");
        return $this->http_client->request("GET", '/validation/status/'.$id);
    }

    public function SendSMS($params)
    {
        return $this->http_client->request("POST", '/sms/send', [
            'body' => $params
        ]);
    }

    public function GetSmsDetails($params)
    {
        $id = $this->pop($params, "id");
        return $this->http_client->request("GET", '/sms/'.$id);
    }

    public function PlaceCall($params)
    {
        return $this->http_client->request("POST", '/call', [
            'body' => $params
        ]);
    }

    public function GetCallDetails($params)
    {
        $id = $this->pop($params, "id");
        return $this->http_client->request("GET", '/call/'.$id);
    }

    public function HangUpCall($params)
    {
        $id = $this->pop($params, "id");
        return $this->http_client->request("DELETE", '/call/'. $id);
    }

    private function pop($params, $key)
    {
        $val = $params[$key];

        if (!$val)
            throw new CheckMobiError($key." parameter not found");

        unset($params[$key]);
        return $val;
    }

}

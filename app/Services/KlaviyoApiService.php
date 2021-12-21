<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class KlaviyoApiService
{
    public $baseUrl = "https://a.klaviyo.com/api/v2/";
    public $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getListKlaviyo()
    {
        $response = Http::get($this->baseUrl.'lists', [
            'api_key' => $this->apiKey,
        ]);
        if ($response->status() == 200) {
            return [
                'lists' => $response->json()
            ];
        } else {
            return $response->json();
        }
    }

    public function addMemberToKlaviyo($profiles,$listId)
    {
        try{
            $response = Http::post($this->baseUrl.'list/' . $listId . '/members', [
                'profiles' => $profiles,
                'api_key' => $this->apiKey,
            ]);
        }
        catch(\Exception $ex)
        {
            report($ex);
            return false;
        }
        return true;
    }

    public function deleteMemberKlaviyo($profiles,$listId)
    {
        try{
            $response = Http::delete($this->baseUrl.'list/' . $listId . '/members', [
                'emails' => $profiles,
                'api_key' => $this->apiKey,
            ]);
        }
        catch(\Exception $ex)
        {
            report($ex);
            return false;
        }
        return true;
    }
}
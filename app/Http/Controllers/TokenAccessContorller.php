<?php

namespace App\Http\Controllers;

use App\Models\TokenAccess;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class TokenAccessContorller extends Controller
{
    protected $client_id;

    protected $client_secret;
    protected $url;
    protected $expire_in;
    protected $presentTime;

    public function __construct()
    {
        $this->client_id = env('CLIENT_ID') ?? '';

        $this->client_secret =  env('CLIENT_SECRET') ?? '';

        $this->url = env('URL_API') ?? '';

        $this->presentTime = 0;

        $this->expire_in =  14200;
    }

    public function getToken()
    {

        $data = $this->getAccessToken($this->client_id);
        if (!$data) {
            //kalau ngak ada
            $currentTimeAcess = now();

            $response = $this->Api($this->url, $this->client_id, $this->client_secret);
            $this->saveKey($response, $currentTimeAcess);
            $data = $this->getAccessToken($this->client_id);
            return response()->json(['status' => 'berhasil', 'message' => 'token berhasil dibuat', 'data' => $data, 'present_time' =>  $this->presentTime]);
        } else {
            //check
            $timelastget =  Carbon::parse($data->interval_access);
            $currentTime = now();

            $diference = (int) $this->checkTime($timelastget, $currentTime);


            if ($diference > $this->expire_in || $diference < 0) {

                //get Api kembali
                $currentTime = now();
                $response = $this->Api($this->url, $this->client_id, $this->client_secret);

                $this->updateToken($response, $this->client_id, $currentTime);


                return response()->json(['status' => 'berhasil', 'message' => 'token berhasil diperbarui', 'data' =>  $data, 'present_time' => $this->presentTime]);
            }
            $this->presentTime =  $diference;
            return response()->json(['status' => 'berhasil', 'message' => 'token masih berlum expired', 'data' =>  $data, 'present_time' => $this->presentTime]);
        }
    }
 

    protected function Api($url, $client_id, $client_secret)
    {
        $response = Http::asForm()->post($url . '/oauth2/v1/accesstoken?grant_type=client_credentials', [
            'client_id' => $client_id,
            'client_secret' => $client_secret,
        ]);

        $response = json_decode($response->body());
        return $response;
    }

    protected function saveKey($response, $time): void
    {

        $this->expire_in =  $response->expires_in;
        TokenAccess::create([
            'id' =>  Str::uuid(),
            'client_id' =>  $response->client_id,
            'token' =>  $response->access_token,
            'issued_at' =>  $response->issued_at,
            'aplication_name' => $response->application_name,
            'interval_access' =>  $time,
        ]);
    }

    protected function updateToken($response, $client_id, $time): void
    {
        $data = TokenAccess::Where('client_id', $client_id)->first();
        $data->update([
            'token' => $response->access_token,
            'interval_access' =>  $time,
            'issued_at' =>  $response->issued_at,
            'aplication_name' => $response->application_name,
        ]);
    }

    protected function getAccessToken($client_id)
    {
        $data = TokenAccess::where('client_id', $client_id)->first();
        return $data;
    }

    protected function checkTime($timelastget,  $timeNow)
    {

        $timeAllowAccess = $timelastget->diffInSeconds($timeNow, false);
        return $timeAllowAccess;
    }
}
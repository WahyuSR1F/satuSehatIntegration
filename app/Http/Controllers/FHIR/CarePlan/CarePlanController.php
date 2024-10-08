<?php

namespace App\Http\Controllers\FHIR\CarePlan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\TokenAccessContorller;

class CarePlanController extends Controller
{
    private $baseUrl;
    private $ecounter;
    public function __construct()
    {
        $this->baseUrl =  env('URL_API');
        $this->ecounter = env('E_COUNTER');
    }
    function createCarePlan(Request $request) {
        $tokenAccess = (new TokenAccessContorller())->getToken();
        $tokenAccess = $tokenAccess->getData();
    
        $organizationId = $request->org_id;
        $encounterUuid = $request->encounterUuid;
    
        $data = [
            "resourceType" => "CarePlan",
            "status" => "active",
            "intent" => "plan",
            "title" => "Rencana Perawatan Pasien",
            "description" => "Rujuk ke RS Rujukan Tumbuh Kembang level 1",
            "subject" => [
                "reference" => "Patient/100000030004",
                "display" => "Anak Smith"
            ],
            "encounter" => [
                "reference" => "Encounter/".$encounterUuid
            ],
            "created" => now()->format('Y-m-d\TH:i:sP'),
            "author" => [
                "reference" => "Practitioner/N10000001"
            ]
        ];
    
        $json = json_encode($data, JSON_PRETTY_PRINT);
    
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $tokenAccess->data->token
        ])->post($this->baseUrl . '/fhir-r4/v1/CarePlan', $data);
    
        if ($response->successful()) {
            return response()->json($response->json(), 201);
        } else {
            // handle error response
            return response()->json($response->json(), $response->status());
        }
    }
}

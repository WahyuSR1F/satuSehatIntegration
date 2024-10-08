<?php

namespace App\Http\Controllers\FHIR\Condition;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\TokenAccessContorller;

class ConditionController extends Controller
{
    private $baseUrl;
    private $ecounter;
    public function __construct()
    {
        $this->baseUrl =  env('URL_API');
        $this->ecounter = env('E_COUNTER');
    }
    function createCondition(Request $request) {
        $tokenAccess = (new TokenAccessContorller())->getToken();
        $tokenAccess = $tokenAccess->getData();
    
        $organizationId = $request->org_id;
        $encounterUuid = $request->encounter_uuid;
    
        $data = [
            "resourceType" => "Condition",
            "clinicalStatus" => [
                "coding" => [
                    [
                        "system" => "http://terminology.hl7.org/CodeSystem/condition-clinical",
                        "code" => "active",
                        "display" => "Active"
                    ]
                ]
            ],
            "category" => [
                [
                    "coding" => [
                        [
                            "system" => "http://terminology.hl7.org/CodeSystem/condition-category",
                            "code" => "encounter-diagnosis",
                            "display" => "Encounter Diagnosis"
                        ]
                    ]
                ]
            ],
            "code" => [
                "coding" => [
                    [
                        "system" => "http://hl7.org/fhir/sid/icd-10",
                        "code" => "K35.8",
                        "display" => "Acute appendicitis, other and unspecified"
                    ]
                ]
            ],
            "subject" => [
                "reference" => "Patient/100000030009",
                "display" => "Budi Santoso"
            ],
            "encounter" => [
                "reference" => "Encounter/".$encounterUuid,
                "display" => "Kunjungan Budi Santoso di hari Selasa, 14 Juni 2022"
            ]
        ];
    
        $json = json_encode($data, JSON_PRETTY_PRINT);
    
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $tokenAccess->data->token
        ])->post($this->baseUrl . '/fhir-r4/v1/Condition', $data);
    
        if ($response->successful()) {
             return response()->json($response->json(), 201);
        } else {
            // handle error response
            return response()->json($response->json(), $response->status());
        }
    }
}

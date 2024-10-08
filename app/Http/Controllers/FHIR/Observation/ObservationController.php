<?php

namespace App\Http\Controllers\FHIR\Observation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\TokenAccessContorller;

class ObservationController extends Controller
{
    private $baseUrl;
    private $ecounter;

    public function __construct()
    {
        $this->baseUrl =  env('URL_API');
        $this->ecounter = env('E_COUNTER');

    }

    function createObservation(Request $request) {
        $tokenAccess = (new TokenAccessContorller())->getToken();
        $tokenAccess = $tokenAccess->getData();
    
        $organizationId = $request->org_id;
        $encounterUuid = $this->ecounter;//$request->encounter_uuid;
    
        $data = [
            "resourceType" => "Observation",
            "status" => "final",
            "category" => [
                [
                    "coding" => [
                        [
                            "system" => "http://terminology.hl7.org/CodeSystem/observation-category",
                            "code" => "vital-signs",
                            "display" => "Vital Signs"
                        ]
                    ]
                ]
            ],
            "code" => [
                "coding" => [
                    [
                        "system" => "http://loinc.org",
                        "code" => "8867-4",
                        "display" => "Heart rate"
                    ]
                ]
            ],
            "subject" => [
                "reference" => "Patient/100000030009"
            ],
            "performer" => [
                [
                    "reference" => "Practitioner/N10000001"
                ]
            ],
            "encounter" => [
                "reference" => "Encounter/".$encounterUuid,
                "display" => "Pemeriksaan Fisik Nadi Budi Santoso di hari Selasa, 14 Juni 2022"
            ],
            "effectiveDateTime" => now()->format('Y-m-d\TH:i:sP'),
            "issued" => now()->format('Y-m-d\TH:i:sP'),
            "valueQuantity" => [
                "value" => 80,
                "unit" => "beats/minute",
                "system" => "http://unitsofmeasure.org",
                "code" => "/min"
            ]
        ];
    
        $json = json_encode($data, JSON_PRETTY_PRINT);
    
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $tokenAccess->data->token
        ])->post($this->baseUrl . '/fhir-r4/v1/Observation', $data);
    
        if ($response->successful()) {
            return response()->json($response->json(), 201);
        } else {
            // handle error response
            return response()->json($response->json(), $response->status());
        }
    }
}

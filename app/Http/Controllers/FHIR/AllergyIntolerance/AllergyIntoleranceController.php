<?php

namespace App\Http\Controllers\FHIR\AllergyIntolerance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\TokenAccessContorller;

class AllergyIntoleranceController extends Controller
{
    private $baseUrl;
    private $ecounter;
    public function __construct()
    {
        $this->baseUrl =  env('URL_API');
        $this->ecounter = env('E_COUNTER');
    }
    function createAllergyIntolerance(Request $request) {
        $tokenAccess = (new TokenAccessContorller())->getToken();
        $tokenAccess = $tokenAccess->getData();
    
        $organizationId = $request->org_id;
        $encounterUuid = $request->encounter_uuid;
    
        $data = [
            "resourceType" => "AllergyIntolerance",
            "identifier" => [
                [
                    "system" => "http://sys-ids.kemkes.go.id/allergy/665ed4bb-62dc-4fc8-bcb1-8f9c440102fb",
                    "use" => "official",
                    "value" => "98457729"
                ]
            ],
            "clinicalStatus" => [
                "coding" => [
                    [
                        "system" => "http://terminology.hl7.org/CodeSystem/allergyintolerance-clinical",
                        "code" => "active",
                        "display" => "Active"
                    ]
                ]
            ],
            "verificationStatus" => [
                "coding" => [
                    [
                        "system" => "http://terminology.hl7.org/CodeSystem/allergyintolerance-verification",
                        "code" => "confirmed",
                        "display" => "Confirmed"
                    ]
                ]
            ],
            "category" => [
                "food"
            ],
            "code" => [
                "coding" => [
                    [
                        "system" => "http://snomed.info/sct",
                        "code" => "89811004",
                        "display" => "Gluten"
                    ]
                ],
                "text" => "Alergi bahan gluten, khususnya ketika makan roti gandum"
            ],
            "patient" => [
                "reference" => "Patient/100000030009",
                "display" => "Budi Santoso"
            ],
            "encounter" => [
                "reference" => "Encounter/".$encounterUuid,
                "display" => "Kunjungan Budi Santoso di hari Selasa, 14 Juni 2022"
            ],
            "recordedDate" => now()->format('Y-m-d\TH:i:sP'),
            "recorder" => [
                "reference" => "Practitioner/N10000001"
            ]
        ];
    
        $json = json_encode($data, JSON_PRETTY_PRINT);
    
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $tokenAccess->data->token
        ])->post($this->baseUrl . '/fhir-r4/v1/AllergyIntolerance', $data);
    
      
        if ($response->successful()) {
            return response()->json($response->json(), 201);
        } else {
            // handle error response
            return response()->json($response->json(), $response->status());
        }
    }
}

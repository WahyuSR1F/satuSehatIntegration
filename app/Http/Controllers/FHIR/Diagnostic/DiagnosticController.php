<?php

namespace App\Http\Controllers\FHIR\Diagnostic;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\TokenAccessContorller;

class DiagnosticController extends Controller
{
    private $baseUrl;
    private $ecounter;

    

    public function __construct()
    {
        $this->baseUrl =  env('URL_API');
        $this->ecounter = env('E_COUNTER');

    }

    function createDiagnosticReport(Request $request) {
        $tokenAccess = (new TokenAccessContorller())->getToken();
        $tokenAccess = $tokenAccess->getData();
    
        $organizationId = $request->org_id;
        $observationId =  $request->observation_id;
        $encounterUuid =  $this->ecounter;//$request->encounter_uuid;
    
        $data = [
            "resourceType" => "DiagnosticReport",
            "identifier" => [
                [
                    "system" => "http://sys-ids.kemkes.go.id/diagnostic/".$organizationId."/lab",
                    "use" => "official",
                    "value" => $organizationId
                ]
            ],
            "status" => "final",
            "category" => [
                [
                    "coding" => [
                        [
                            "system" => "http://terminology.hl7.org/CodeSystem/v2-0074",
                            "code" => "MB",
                            "display" => "Microbiology"
                        ]
                    ]
                ]
            ],
            "code" => [
                "coding" => [
                    [
                        "system" => "http://loinc.org",
                        "code" => "11477-7",
                        "display" => "Microscopic observation [Identifier] in Sputum by Acid fast stain"
                    ]
                ]
            ],
            "subject" => [
                "reference" => "Patient/100000030009"
            ],
            "encounter" => [
                "reference" => "Encounter/".$encounterUuid
            ],
            "effectiveDateTime" => now()->format('Y-m-d\TH:i:sP'),
            "issued" => now()->format('Y-m-d\TH:i:sP'),
            "performer" => [
                [
                    "reference" => "Practitioner/N10000001"
                ],
                [
                    "reference" => "Organization/".$organizationId
                ]
            ],
            "result" => [
                [
                    "reference" => "Observation/".$observationId
                ]
            ],
            "specimen" => [
                [
                    "reference" => "Specimen/3095e36e-1624-487e-9ee4-737387e7b55f"
                ]
            ],
            "conclusionCode" => [
                [
                    "coding" => [
                        [
                            "system" => "http://snomed.info/sct",
                            "code" => "260347006",
                            "display" => "+"
                        ]
                    ]
                ]
            ],

        ];
    
        $json = json_encode($data, JSON_PRETTY_PRINT);
    
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $tokenAccess->data->token
        ])->post($this->baseUrl . '/fhir-r4/v1/DiagnosticReport', $data);
    
        if ($response->successful()) {
            return response()->json($response->json(), 201);
        } else {
            // handle error response
            return response()->json($response->json(), $response->status());
        }
    }
}

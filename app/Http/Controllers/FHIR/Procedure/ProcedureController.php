<?php

namespace App\Http\Controllers\FHIR\Procedure;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\TokenAccessContorller;

class ProcedureController extends Controller
{
    private $baseUrl;
    private $ecounter;
    public function __construct()
    {
        $this->baseUrl =  env('URL_API');
        $this->ecounter = env('E_COUNTER');
    }
    function createProcedure(Request $request) {
        $tokenAccess = (new TokenAccessContorller())->getToken();
        $tokenAccess = $tokenAccess->getData();
    
        $organizationId = $request->org_id;
        $encounterUuid = $request->encounter_uuid;
    
        $data = [
            "resourceType" => "Procedure",
            "status" => "completed",
            "category" => [
                "coding" => [
                    [
                        "system" => "http://snomed.info/sct",
                        "code" => "103693007",
                        "display" => "Diagnostic procedure"
                    ]
                ],
                "text" => "Diagnostic procedure"
            ],
            "code" => [
                "coding" => [
                    [
                        "system" => "http://hl7.org/fhir/sid/icd-9-cm",
                        "code" => "87.44",
                        "display" => "Routine chest x-ray, so described"
                    ]
                ]
            ],
            "subject" => [
                "reference" => "Patient/100000030009",
                "display" => "Budi Santoso"
            ],
            "encounter" => [
                "reference" => "Encounter/".$encounterUuid,
                "display" => "Tindakan Rontgen Dada Budi Santoso pada Selasa tanggal 14 Juni 2022"
            ],
            "performedPeriod" => [
                "start" => now()->format('Y-m-d\TH:i:sP'),
                "end" => now()->format('Y-m-d\TH:i:sP')
            ],
            "performer" => [
                [
                    "actor" => [
                        "reference" => "Practitioner/N10000001",
                        "display" => "Dokter Bronsig"
                    ]
                ]
            ],
            "reasonCode" => [
                [
                    "coding" => [
                        [
                            "system" => "http://hl7.org/fhir/sid/icd-10",
                            "code" => "A15.0",
                            "display" => "Tuberculosis of lung, confirmed by sputum microscopy with or without culture"
                        ]
                    ]
                ]
            ],
            "bodySite" => [
                [
                    "coding" => [
                        [
                            "system" => "http://snomed.info/sct",
                            "code" => "302551006",
                            "display" => "Entire Thorax"
                        ]
                    ]
                ]
            ],
            "note" => [
                [
                    "text" => "Rontgen thorax melihat perluasan infiltrat dan kavitas."
                ]
            ]
        ];
    
        $json = json_encode($data, JSON_PRETTY_PRINT);
    
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $tokenAccess->data->token
        ])->post($this->baseUrl . '/fhir-r4/v1/Procedure', $data);
    
        if ($response->successful()) {
            return response()->json($response->json(), 201);
        } else {
            // handle error response
            return response()->json($response->json(), $response->status());
        }
    }
}

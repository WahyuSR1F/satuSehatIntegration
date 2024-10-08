<?php

namespace App\Http\Controllers\FHIR\Composition;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\TokenAccessContorller;

class CompositionController extends Controller
{
    private $baseUrl;
    private $ecounter;
    public function __construct()
    {
        $this->baseUrl =  env('URL_API');
        $this->ecounter = env('E_COUNTER');
    }
    function createComposition(Request $request) {
        $tokenAccess = (new TokenAccessContorller())->getToken();
        $tokenAccess = $tokenAccess->getData();
    
        $organizationId = $request->org_id;
        $encounterUuid = $request->encounter_uuid;
    
        $data = [
            "resourceType" => "Composition",
            "identifier" => [
                "system" => "http://sys-ids.kemkes.go.id/composition/665ed4bb-62dc-4fc8-bcb1-8f9c440102fb",
                "value" => "P20240001"
            ],
            "status" => "final",
            "type" => [
                "coding" => [
                    [
                        "system" => "http://loinc.org",
                        "code" => "18842-5",
                        "display" => "Discharge summary"
                    ]
                ]
            ],
            "category" => [
                [
                    "coding" => [
                        [
                            "system" => "http://loinc.org",
                            "code" => "LP173421-1",
                            "display" => "Report"
                        ]
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
            ],
            "date" => now()->format('Y-m-d\TH:i:sP'),
            "author" => [
                [
                    "reference" => "Practitioner/N10000001",
                    "display" => "Dokter Bronsig"
                ]
            ],
            "title" => "Resume Medis Rawat Jalan",
            "custodian" => [
                "reference" => "Organization/665ed4bb-62dc-4fc8-bcb1-8f9c440102fb"
            ],
            "section" => [
                [
                    "code" => [
                        "coding" => [
                            [
                                "system" => "http://loinc.org",
                                "code" => "42344-2",
                                "display" => "Discharge diet (narrative)"
                            ]
                        ]
                    ],
                    "text" => [
                        "status" => "additional",
                        "div" => "Rekomendasi diet rendah lemak, rendah kalori"
                    ]
                ]
            ]
        ];
    
        $json = json_encode($data, JSON_PRETTY_PRINT);
    
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $tokenAccess->data->token
        ])->post($this->baseUrl . '/fhir-r4/v1/Composition', $data);
    
        if ($response->successful()) {
            return response()->json($response->json(), 201);
        } else {
            // handle error response
            return response()->json($response->json(), $response->status());
        }
    }
}

<?php

namespace App\Http\Controllers\UseCase\Skrining\SkriningPTM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SkriningMataController extends Controller
{
    private $baseUrl;
    private $ecounter;

    public function __construct()
    {
        $this->baseUrl = env('URL_API');
        $this->ecounter = env('E_COUNTER');
    }
    
    public function visusMataKanan (Request $request){
        $tokenAccess = (new TokenAccessController())->getToken();
        $tokenAccess = $tokenAccess->getData();

        $patientUuid = $request->patient_uuid;
        $encounterUuid = $request->encounter_uuid;

        $data = [
            "resourceType" => "Observation",
            "status" => "final",
            "category" => [
                [
                    "coding" => [
                        [
                            "system" => "http://terminology.hl7.org/CodeSystem/observation-category",
                            "code" => "exam",
                            "display" => "Exam"
                        ]
                    ]
                ]
            ],
            "code" => [
                "coding" => [
                    [
                        "system" => "http://loinc.org",
                        "code" => "79882-7",
                        "display" => "Visual acuity uncorrected Right eye by Snellen eye chart"
                    ]
                ]
            ],
            "performer" => [
                [
                    "reference" => "Practitioner/N10000001"
                ]
            ],
            "subject" => [
                "reference" => "Patient/" . $patientUuid,
                "display" => "patient 3"
            ],
            "encounter" => [
                "reference" => "Encounter/" . $encounterUuid
            ],
            "effectiveDateTime" => now()->format('Y-m-d\TH:i:sP'),
            "issued" => now()->format('Y-m-d\TH:i:sP'),
            "valueRatio" => [
                "numerator" => [
                    "value" => 6,
                    "unit" => "m",
                    "system" => "http://unitsofmeasure.org",
                    "code" => "m"
                ],
                "denominator" => [
                    "value" => 12,
                    "unit" => "m",
                    "system" => "http://unitsofmeasure.org",
                    "code" => "m"
                ]
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

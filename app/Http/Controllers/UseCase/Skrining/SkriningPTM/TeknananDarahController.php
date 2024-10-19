<?php

namespace App\Http\Controllers\UseCase\Skrining\SkriningPTM;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\TokenAccessContorller;

class TeknananDarahController extends Controller
{
    private $baseUrl;
    private $ecounter;

    public function __construct()
    {
        $this->baseUrl = env('URL_API');
        $this->ecounter = env('E_COUNTER');
    }
    public function setSistolk (Request $request) {
        $tokenAccess = (new TokenAccessContorller())->getToken();
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
                        "code" => "8480-6",
                        "display" => "Systolic blood pressure"
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
            "valueQuantity" => [
                "value" => 160,
                "unit" => "mmHg",
                "system" => "http://unitsofmeasure.org",
                "code" => "mm[Hg]"
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

    public function setDiastolk (Request $request){
        $tokenAccess = (new TokenAccessContorller())->getToken();
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
                        "code" => "8462-4",
                        "display" => "Diastolic blood pressure"
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
            "valueQuantity" => [
                "value" => 100,
                "unit" => "mmHg",
                "system" => "http://unitsofmeasure.org",
                "code" => "mm[Hg]"
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
    public function Hipertensi (Request $request){
        $tokenAccess = (new TokenAccessContorller())->getToken();
        $tokenAccess = $tokenAccess->getData();

        $patientUuid = $request->patient_uuid;
        $encounterUuid = $request->encounter_uuid;
        $sistolId = $request->sistol_id;
        $diastolId = $request->diastol_id;

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
                        "system" => "http://snomed.info/sct",
                        "code" => "268607006",
                        "display" => "Hypertension risk level"
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
                    "value" => 160,
                    "unit" => "mmHg",
                    "system" => "http://unitsofmeasure.org",
                    "code" => "mm[Hg]"
                ],
                "denominator" => [
                    "value" => 110,
                    "unit" => "mmHg",
                    "system" => "http://unitsofmeasure.org",
                    "code" => "mm[Hg]"
                ]
            ],
            "derivedFrom" => [
                [
                    "reference" => "Observation/" . $sistolId,
                    "display" => "Systolic Blood Pressure"
                ],
                [
                    "reference" => "Observation/" . $diastolId,
                    "display" => "Diastolic Blood Pressure"
                ]
            ],
            "interpretation" => [
                [
                    "coding" => [
                        [
                            "system" => "http://snomed.info/sct",
                            "code" => "827068008",
                            "display" => "Stage 2 hypertension"
                        ]
                    ]
                ]
            ],
            "referenceRange" => [
                [
                    "text" => "Tekanan Darah Optimal: < 120/80 mmHg"
                ],
                [
                    "text" => "Tekanan Darah Normal: 120/80 s.d. 129/84 mmHg"
                ],
                [
                    "text" => "Tekanan Darah Normal Tinggi: 130/85 s.d. 139/89 mmHg"
                ],
                [
                    "text" => "Hipertensi Derajat 1: 140/90 s.d. 159/99 mmHg"
                ],
                [
                    "text" => "Hipertensi Derajat 2: 160/100 s.d. 179/109 mmHg"
                ],
                [
                    "text" => "Hipertensi Derajat 3: >= 180/110 mmHg"
                ],
                [
                    "text" => "Hipertensi Sistolik Terisolasi: Sistolik >= 140 mmHg, Diastolik <= 90 mmHg"
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

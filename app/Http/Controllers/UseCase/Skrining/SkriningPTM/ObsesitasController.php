<?php

namespace App\Http\Controllers\UseCase\Skrining\SkriningPTM;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\TokenAccessContorller;

class ObsesitasController extends Controller
{
    private $baseUrl;
    private $ecounter;

    public function __construct()
    {
        $this->baseUrl = env('URL_API');
        $this->ecounter = env('E_COUNTER');
    }

    function createBeratBadan(Request $request)
    {
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
                        "code" => "29463-7",
                        "display" => "Body weight"
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
                "value" => 71,
                "unit" => "kg",
                "system" => "http://unitsofmeasure.org",
                "code" => "kg"
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

    public function tinggiBadanSet (Request $request){

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
                        "code" => "8302-2",
                        "display" => "Body height"
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
                "value" => 169.8,
                "unit" => "cm",
                "system" => "http://unitsofmeasure.org",
                "code" => "cm"
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

    public function IMTset (Request $request){
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
                        "code" => "39156-5",
                        "display" => "Body mass index (BMI) [Ratio]"
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
                "value" => 24.62538204025799,
                "unit" => "kg/m^2",
                "system" => "http://unitsofmeasure.org",
                "code" => "kg/m2"
            ],
            "interpretation" => [
                [
                    "coding" => [
                        [
                            "system" => "http://snomed.info/sct",
                            "code" => "248342006",
                            "display" => "Underweight"
                        ]
                    ]
                ]

            ],
            "referenceRange" => [
            [
                "high" => [
                    "value" => 16.9,
                    "unit" => "kg/m^2",
                    "system" => "http://unitsofmeasure.org",
                    "code" => "kg/m2"
                ],
                "text" => "Sangat Kurus"
            ],
            [
                "low" => [
                    "value" => 17,
                    "unit" => "kg/m^2",
                    "system" => "http://unitsofmeasure.org",
                    "code" => "kg/m2"
                ],
                "high" => [
                    "value" => 18.4,
                    "unit" => "kg/m^2",
                    "system" => "http://unitsofmeasure.org",
                    "code" => "kg/m2"
                ],
                "text" => "Kurus"
                ],
                [
                    "low" => [
                        "value" => 18.5,
                        "unit" => "kg/m^2",
                        "system" => "http://unitsofmeasure.org",
                        "code" => "kg/m2"
                    ],
                    "high" => [
                        "value" => 25,
                        "unit" => "kg/m^2",
                        "system" => "http://unitsofmeasure.org",
                        "code" => "kg/m2"
                    ],
                    "type" => [
                        "coding" => [
                            [
                                "system" => "https://www.hl7.org/fhir/R4/codesystem-referencerange-meaning.html",
                                "code" => "normal",
                                "display" => "Normal Range"
                            ]
                        ]
                    ],
                    "text" => "Normal"
                ],
                [
                    "low" => [
                        "value" => 25.1,
                        "unit" => "kg/m^2",
                        "system" => "http://unitsofmeasure.org",
                        "code" => "kg/m2"
                    ],
                    "high" => [
                        "value" => 27,
                        "unit" => "kg/m^2",
                        "system" => "http://unitsofmeasure.org",
                        "code" => "kg/m2"
                    ],
                    "text" => "Gemuk (Overweight)"
                ],
                [
                    "low" => [
                        "value" => 27.1,
                        "unit" => "kg/m^2",
                        "system" => "http://unitsofmeasure.org",
                        "code" => "kg/m2"
                    ],
                    "text" => "Obese"
                ]
            ],
            "derivedFrom" => [
                [
                    "reference" => "Observation/a9251115-4538-448d-bb06-895502da4fb0",
                    "display" => "Body Height"
                ],
                [
                    "reference" => "Observation/b8b07a34-b326-4447-aa65-f4f285b09640",
                    "display" => "Body Weight"
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

    public function lingkarPinggangSet (Request $request){
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
                        "system" => "http://snomed.info/sct",
                        "code" => "276361009",
                        "display" => "Waist circumference"
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
                "value" => 103,
                "unit" => "cm",
                "system" => "http://unitsofmeasure.org",
                "code" => "cm"
            ],
            "interpretation" => [
                [
                    "coding" => [
                        [
                            "system" => "http://terminology.hl7.org/CodeSystem/v3-ObservationInterpretation",
                            "code" => "N",
                            "display" => "Normal"
                        ]
                    ]
                ]
            ],
            "referenceRange" => [
                [
                    "high" => [
                        "value" => 79.9,
                        "unit" => "cm",
                        "system" => "http://unitsofmeasure.org",
                        "code" => "cm"
                    ],
                    "type" => [
                        "coding" => [
                            [
                                "system" => "http://terminology.hl7.org/CodeSystem/referencerange-meaning",
                                "code" => "normal",
                                "display" => "Normal Range"
                            ]
                        ]
                    ],
                    "text" => "Normal"
                ],
                [
                    "low" => [
                        "value" => 80,
                        "unit" => "cm",
                        "system" => "http://unitsofmeasure.org",
                        "code" => "cm"
                    ],
                    "text" => "Central Obesity"
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


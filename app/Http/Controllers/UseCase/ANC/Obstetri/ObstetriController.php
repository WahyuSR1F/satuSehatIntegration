<?php

namespace App\Http\Controllers\UseCase\ANC\Obstetri;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\TokenAccessContorller;

class ObstetriController extends Controller
{
    private $baseUrl;
    private $ecounter;

    public function __construct()
    {
        $this->baseUrl = env('URL_API');
        $this->ecounter = env('E_COUNTER');
    }

    public function createGravida(Request $request)
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
                            "code" => "survey",
                            "display" => "Survey"
                        ]
                    ]
                ]
            ],
            "code" => [
                "coding" => [
                    [
                        "system" => "http://loinc.org",
                        "code" => "11996-6",
                        "display" => "[#] Pregnancies"
                    ],
                    [
                        "system" => "http://fhir.org/guides/who/anc-cds/CodeSystem/anc-custom-codes",
                        "code" => "ANC.B6.DE24",
                        "display" => "Number of pregnancies (gravida)"
                    ]
                ]
            ],
            "subject" => [
                "reference" => "Patient/" . $patientUuid,
                "display" => "Jane Smith"
            ],
            "encounter" => [
                "reference" => "Encounter/" . $encounterUuid
            ],
            "effectiveDateTime" => now()->format('Y-m-d\TH:i:sP'),
            "issued" => now()->format('Y-m-d\TH:i:sP'),
            "performer" => [
                [
                    "reference" => "Practitioner/N10000001"
                ]
            ],
            "valueInteger" => 2
        ];

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

    public function setParitas(Request $request)
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
                            "code" => "survey",
                            "display" => "Survey"
                        ]
                    ]
                ]
            ],
            "code" => [
                "coding" => [
                    [
                        "system" => "http://loinc.org",
                        "code" => "11977-6",
                        "display" => "[#] Parity"
                    ],
                    [
                        "system" => "http://fhir.org/guides/who/anc-cds/CodeSystem/anc-custom-codes",
                        "code" => "ANC.B6.DE32",
                        "display" => "Parity"
                    ]
                ]
            ],
            "subject" => [
                "reference" => "Patient/" . $patientUuid,
                "display" => "Jane Smith"
            ],
            "encounter" => [
                "reference" => "Encounter/" . $encounterUuid
            ],
            "effectiveDateTime" => now()->format('Y-m-d\TH:i:sP'),
            "issued" => now()->format('Y-m-d\TH:i:sP'),
            "performer" => [
                [
                    "reference" => "Practitioner/N10000001"
                ]
            ],
            "valueInteger" => 1
        ];

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

    public function setAbortus(Request $request)
    {
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
                            "code" => "survey",
                            "display" => "Survey"
                        ]
                    ]
                ]
            ],
            "code" => [
                "coding" => [
                    [
                        "system" => "http://loinc.org",
                        "code" => "69043-8",
                        "display" => "Other pregnancy outcomes #"
                    ],
                    [
                        "system" => "http://fhir.org/guides/who/anc-cds/CodeSystem/anc-custom-codes",
                        "code" => "ANC.B6.DE25",
                        "display" => "Number of miscarriages and/or abortions"
                    ]
                ]
            ],
            "subject" => [
                "reference" => "Patient/" . $patientUuid,
                "display" => "Jane Smith"
            ],
            "encounter" => [
                "reference" => "Encounter/" . $encounterUuid
            ],
            "effectiveDateTime" => now()->format('Y-m-d\TH:i:sP'),
            "issued" => now()->format('Y-m-d\TH:i:sP'),
            "performer" => [
                [
                    "reference" => "Practitioner/N10000001"
                ]
            ],
            "valueInteger" => 0
        ];

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

    public function setHPHT(Request $request)
    {
        $tokenAccess = (new TokenAccessController())->getToken();
        $tokenAccess = $tokenAccess->getData();

        $patientUuid = $request->patient_uuid;
        $encounterUuid = $request->encounter_uuid;
        $haidTerakhir = $request->haid_terakhir;

        $data = [
            "resourceType" => "Observation",
            "status" => "final",
            "category" => [
                [
                    "coding" => [
                        [
                            "system" => "http://terminology.hl7.org/CodeSystem/observation-category",
                            "code" => "survey",
                            "display" => "Survey"
                        ]
                    ]
                ]
            ],
            "code" => [
                "coding" => [
                    [
                        "system" => "http://loinc.org",
                        "code" => "8665-2",
                        "display" => "Last menstrual period start date"
                    ],
                    [
                        "system" => "http://fhir.org/guides/who/anc-cds/CodeSystem/anc-custom-codes",
                        "code" => "ANC.B6.DE14",
                        "display" => "Last menstrual period (LMP) date"
                    ]
                ]
            ],
            "subject" => [
                "reference" => "Patient/" . $patientUuid,
                "display" => "Jane Smith"
            ],
            "encounter" => [
                "reference" => "Encounter/" . $encounterUuid
            ],
            "effectiveDateTime" => now()->format('Y-m-d\TH:i:sP'),
            "issued" => now()->format('Y-m-d\TH:i:sP'),
            "performer" => [
                [
                    "reference" => "Practitioner/N10000001"
                ]
            ],
            "valueDateTime" => $haidTerakhir
        ];

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

    public function setIMTSebelumHamil(Request $request)
    {
        $tokenAccess = (new TokenAccessContorller())->getToken();
        $tokenAccess = $tokenAccess->getData();

        $patientUuid = $request->patient_uuid;
        $encounterUuid = $request->encounter_uuid;
        $beratBadan = $request->berat_badan;

        $data = [
            "resourceType" => "Observation",
            "status" => "final",
            "category" => [
                [
                    "coding" => [
                        [
                            "system" => "http://terminology.hl7.org/CodeSystem/observation-category",
                            "code" => "survey",
                            "display" => "Survey"
                        ]
                    ]
                ]
            ],
            "code" => [
                "coding" => [
                    [
                        "system" => "http://loinc.org",
                        "code" => "56077-1",
                        "display" => "Body weight --pre current pregnancy"
                    ],
                    [
                        "system" => "http://fhir.org/guides/who/anc-cds/CodeSystem/anc-custom-codes",
                        "code" => "ANC.B8.DE2",
                        "display" => "Pre-gestational weight"
                    ]
                ]
            ],
            "subject" => [
                "reference" => "Patient/" . $patientUuid,
                "display" => "Jane Smith"
            ],
            "encounter" => [
                "reference" => "Encounter/" . $encounterUuid
            ],
            "effectiveDateTime" => now()->format('Y-m-d\TH:i:sP'),
            "issued" => now()->format('Y-m-d\TH:i:sP'),
            "performer" => [
                [
                    "reference" => "Practitioner/N10000001"
                ]
            ],
            "valueQuantity" => [
                "value" => $beratBadan,
                "unit" => "kg",
                "system" => "http://unitsofmeasure.org",
                "code" => "kg"
            ]
        ];

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

    public function setTargetKenaikanBeratBadan(Request $request)
    {
        $tokenAccess = (new TokenAccessContorller())->getToken();
        $tokenAccess = $tokenAccess->getData();

        $patientUuid = $request->patient_uuid;
        $encounterUuid = $request->encounter_uuid;
        $targetKenaikanBeratBadan = $request->target_kenaikan_berat_badan;

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
                        "system" => "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                        "code" => "OC000011",
                        "display" => "Target Kenaikan Berat Badan"
                    ],
                    [
                        "system" => "http://fhir.org/guides/who/anc-cds/CodeSystem/anc-custom-codes",
                        "code" => "ANC.B8.DE10",
                        "display" => "Expected weight gain"
                    ]
                ]
            ],
            "subject" => [
                "reference" => "Patient/" . $patientUuid,
                "display" => "Jane Smith"
            ],
            "encounter" => [
                "reference" => "Encounter/" . $encounterUuid
            ],
            "effectiveDateTime" => now()->format('Y-m-d\TH:i:sP'),
            "issued" => now()->format('Y-m-d\TH:i:sP'),
            "performer" => [
                [
                    "reference" => "Practitioner/N10000001"
                ]
            ],
            "valueCodeableConcept" => [
                "coding" => [
                    [
                        "system" => "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                        "code" => $targetKenaikanBeratBadan,
                        "display" => "7–11.5 kg"
                    ]
                ]
            ]
        ];

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

    public function setJarakKehamilan(Request $request)
    {
        $tokenAccess = (new TokenAccessContorller())->getToken();
        $tokenAccess = $tokenAccess->getData();

        $patientUuid = $request->patient_uuid;
        $encounterUuid = $request->encounter_uuid;
        $jarakKehamilan = $request->jarak_kehamilan;

        $data = [
            "resourceType" => "Observation",
            "status" => "final",
            "category" => [
                [
                    "coding" => [
                        [
                            "system" => "http://terminology.hl7.org/CodeSystem/observation-category",
                            "code" => "survey",
                            "display" => "Survey"
                        ]
                    ]
                ]
            ],
            "code" => [
                "coding" => [
                    [
                        "system" => "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                        "code" => "OC000001",
                        "display" => "Jarak kehamilan"
                    ],
                    [
                        "system" => "http://terminology.kemkes.go.id/CodeSystem/anc-custom-codes",
                        "code" => "ANC.SS.DE53",
                        "display" => "Jarak kehamilan"
                    ]
                ]
            ],
            "subject" => [
                "reference" => "Patient/" . $patientUuid,
                "display" => "Jane Smith"
            ],
            "encounter" => [
                "reference" => "Encounter/" . $encounterUuid
            ],
            "effectiveDateTime" => now()->format('Y-m-d\TH:i:sP'),
            "issued" => now()->format('Y-m-d\TH:i:sP'),
            "performer" => [
                [
                    "reference" => "Practitioner/N10000001"
                ]
            ],
            "valueQuantity" => [
                "value" => $jarakKehamilan,
                "unit" => "mo",
                "system" => "http://unitsofmeasure.org",
                "code" => "mo"
            ]
        ];

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

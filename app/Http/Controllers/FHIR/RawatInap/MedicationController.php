<?php

namespace App\Http\Controllers\FHIR\RawatInap;

use App\Models\KFA;
use Illuminate\Http\Request;
use App\Models\MedicationRequest;
use App\Models\ResponseMedication;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\TokenAccessContorller;

class MedicationController extends Controller
{
    private $baseUrl;
    private $OrgId ;
    private $ecounter;

    

    public function __construct()
    {
        $this->baseUrl =  env('URL_API');
        $this->OrgId =  env('ORG_ID');
        $this->ecounter = env('E_COUNTER');

    }

    public function createMedication(Request $request)
    {
        $tokenAccess = (new TokenAccessContorller())->getToken();
        $tokenAccess = $tokenAccess->getData();
        
        $data = KFA::where('kfa_code', $request->kfa_code)->first();
    
        $ingredient = $this->formatJsonIngredient($data->active_ingredients);
        
        
        
        // Prepare the data for the API request
        $data = [
            'resourceType' => 'Medication',
            'meta' => [
                'profile' => ['https://fhir.kemkes.go.id/r4/StructureDefinition/Medication']
            ],
            'identifier' => [
                [
                    'system' => 'http://sys-ids.kemkes.go.id/medication/'. $request->org_id,
                    'use' => 'official',
                    'value' => $request->org_id
                ]
            ],
            'code' => [
                'coding' => [
                    [
                        'system' => 'http://sys-ids.kemkes.go.id/kfa',
                        'code' => $data->kfa_code,
                        'display' => $data->name
                    ]
                ]
            ],
            'status' => "active",
            'manufacturer' => [
                'reference' => "Organization/". $request->org_id
            ],
            'form' => [
                'coding' => [
                    [
                        'system' => 'http://terminology.kemkes.go.id/CodeSystem/medication-form',
                        'code' => $data->dosage_form_code,
                        'display' => $data->dosage_form_name
                    ]
                ]
            ],
            'ingredient' => [
               $ingredient[0]
            ],
            'extension' => [
                [
                    'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType',
                    'valueCodeableConcept' => [
                        'coding' => [
                            [
                                'system' => 'http://terminology.kemkes.go.id/CodeSystem/medication-type',
                                'code' => "NC",
                                'display' => "Non-compound"
                            ]
                        ]
                    ]
                ]
            ]
        ];
       
        // Make the API request
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $tokenAccess->data->token
        ])->post($this->baseUrl . '/fhir-r4/v1/Medication', $data);

        // Handle the response
        if ($response->successful()) {
            $responseJson = json_decode($response->getBody()->getContents(), true);
            $organizationId = $responseJson['manufacturer']['reference'];
            $idMedication = $responseJson["id"];
            ResponseMedication::create([
                'id_medication' =>  $idMedication,
                'org_id' =>  $organizationId,
                'response' =>  $response->getBody(),
               
            ]);
            return response()->json($response->json(), 201);
        } else {
            return response()->json($response->json(), $response->status());
        }
    }

    protected function formatJsonIngredient ($jsonIngridient){
        $data = json_decode($jsonIngridient);

        $ingredient = [];

        foreach ($data as $items){
            $string =  $items->kekuatan_zat_aktif;
            list($value, $code) = explode(" ", $string);

            $ingredient[] = [
                        'itemCodeableConcept' => [
                            'coding' => [
                                [
                                    'system' => 'http://sys-ids.kemkes.go.id/kfa',
                                    'code' => $items->kfa_code,
                                    'display' => $items->zat_aktif
                                ]
                            ]
                        ],
                        'isActive' => $items->active,
                        'strength' => [
                            'numerator' => [
                                'value' => (int) $value,
                                'system' => 'http://unitsofmeasure.org',
                                'code' => $code
                            ],
                            'denominator' => [
                                'value' => 1,
                                'system' => 'http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm',
                                'code' => "TAB"
                            ]
                        ],

            ];
        }

        return $ingredient;
        
       
    }

    function createMedicationRequest(Request $request) {

        $tokenAccess = (new TokenAccessContorller())->getToken();
        $tokenAccess = $tokenAccess->getData();

        $medication = ResponseMedication::where('id_medication', $request->id_medication)->first();
        $organizationId = str_replace('Organization/', '', $medication->org_id);
        $responseMedication = $medication->response;
        $responseJson = json_decode($responseMedication, true);
     
    
        $data = [
            "resourceType" => "MedicationRequest",
            "identifier" => [
                [
                    "system" => "http://sys-ids.kemkes.go.id/prescription/".$organizationId,
                    "use" => "official",
                    "value" => $organizationId,
                ],
                [
                    "system" => "http://sys-ids.kemkes.go.id/prescription-item/".$organizationId,
                    "use" => "official",
                    "value" => $organizationId
                ]
            ],
            "status" => "completed",
            "intent" => "order",
            "category" => [
                [
                    "coding" => [
                        [
                            "system" => "http://terminology.hl7.org/CodeSystem/medicationrequest-category",
                            "code" => "outpatient",
                            "display" => "Outpatient"
                        ]
                    ]
                ]
            ],
            "priority" => "routine",
            "medicationReference" => [
                "reference" => "Medication/".$request->id_medication,
                "display" => $responseJson['code']['coding'][0]['display'],
            ],
            "subject" => [
                "reference" => "Patient/100000030009",
                "display" => "Budi Santoso"
            ],
            "encounter" => [
                "reference" => "Encounter/".$this->ecounter
            ],
            "authoredOn" => now()->format('Y-m-d\TH:i:sP'),
            "requester" => [
                "reference" => "Practitioner/N10000001",
                "display" => "Dokter Bronsig"
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
            "courseOfTherapyType" => [
                "coding" => [
                    [
                        "system" => "http://terminology.hl7.org/CodeSystem/medicationrequest-course-of-therapy",
                        "code" => "continuous",
                        "display" => "Continuing long term therapy"
                    ]
                ]
            ],
            "dosageInstruction" => [
                [
                    "sequence" => 1,
                    "text" => "4 tablet per hari",
                    "additionalInstruction" => [
                        [
                            "text" => "Diminum setiap hari"
                        ]
                    ],
                    "patientInstruction" => "4 tablet perhari, diminum setiap hari tanpa jeda sampai prose pengobatan berakhir",
                    "timing" => [
                        "repeat" => [
                            "frequency" => 1,
                            "period" => 1,
                            "periodUnit" => "d"
                        ]
                    ],
                    "route" => [
                        "coding" => [
                            [
                                "system" => "http://www.whocc.no/atc",
                                "code" => "O",
                                "display" => "Oral"
                            ]
                        ]
                    ],
                    "doseAndRate" => [
                        [
                            "type" => [
                                "coding" => [
                                    [
                                        "system" => "http://terminology.hl7.org/CodeSystem/dose-rate-type",
                                        "code" => "ordered",
                                        "display" => "Ordered"
                                    ]
                                ]
                            ],
                            "doseQuantity" => [
                                "value" => 4,
                                "unit" => "TAB",
                                "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                                "code" => "TAB"
                            ]
     ]
                    ]
                ]
            ],
            "dispenseRequest" => [
                "dispenseInterval" => [
                    "value" => 1,
                    "unit" => "days",
                    "system" => "http://unitsofmeasure.org",
                    "code" => "d"
                ],
                "validityPeriod" => [
                   "start" => now()->format('Y-m-d\TH:i:sP'),
                   "end" => now()->addDays(2)->format('Y-m-d\TH:i:sP'),
                ],
                "numberOfRepeatsAllowed" => 0,
                "quantity" => [
                    "value" => 120,
                    "unit" => "TAB",
                    "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                    "code" => "TAB"
                ],
                "expectedSupplyDuration" => [
                    "value" => 30,
                    "unit" => "days",
                    "system" => "http://unitsofmeasure.org",
                    "code" => "d"
                ],
                "performer" => [
                    "reference" => "Organization/".$this->OrgId
                ]
            ]
        ];
        $json = json_encode($data, JSON_PRETTY_PRINT);
      
       
        
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $tokenAccess->data->token
        ])->post($this->baseUrl . '/fhir-r4/v1/MedicationRequest', $data);


        if ($response->successful()) {
            $responseJson = json_decode($response->getBody()->getContents(), true);
            $idMedicationRequest = $responseJson["id"];
            MedicationRequest::create([
                'id_medication_request' =>  $idMedicationRequest,
                'response' => $response->getBody()
             ]);
             return response()->json($response->json(), 201);
        } else {
            // handle error response
            return response()->json($response->json(), $response->status());
        }
    }

    public function createMedicationDispense (Request $request) {
        $tokenAccess = (new TokenAccessContorller())->getToken();
        $tokenAccess = $tokenAccess->getData();

        $medicationRequest = MedicationRequest::where('id_medication_request', $request->id_medication_request)->first();
   
        $responseMedicationRequest = $medicationRequest->response;
        $responseJson = json_decode($responseMedicationRequest, true);
       
        $organizationId = $responseJson["dispenseRequest"]["performer"]["reference"];
        $organizationId = str_replace('Organization/', '', $organizationId);
        
        

        $data = [
            "resourceType" => "MedicationDispense",
            "identifier" => [
                [
                    "system" => "http://sys-ids.kemkes.go.id/prescription/".$organizationId,
                    "use" => "official",
                    "value" => $organizationId,
                ],
                [
                    "system" => "http://sys-ids.kemkes.go.id/prescription-item/".$organizationId,
                    "use" => "official",
                    "value" => $organizationId."-1"
                ]
            ],
            "status" => "completed",
            "category" => [
                "coding" => [
                    [
                        "system" => "http://terminology.hl7.org/fhir/CodeSystem/medicationdispense-category",
                        "code" => "outpatient",
                        "display" => "Outpatient"
                    ]
                ]
            ],
            "medicationReference" => [
                "reference" => $responseJson['medicationReference']['reference'],
                "display" => $responseJson['medicationReference']['display'],
            ],
            "subject" => [
                "reference" => "Patient/100000030009",
                "display" => "Budi Santoso"
            ],
            "context" => [
                "reference" => "Encounter/".$this->ecounter
            ],
            "performer" => [
                [
                    "actor" => [
                        "reference" => "Practitioner/N10000001",
                        "display" => "Dokter Bronsig"
                    ]
                ]
            ],
            "location" => [
                "reference" => "Location/52e135eb-1956-4871-ba13-e833e662484d",
                "display" => "Apotek RSUD Jati Asih"
            ],
            "authorizingPrescription" => [
                [
                    "reference" => "MedicationRequest/".$request->id_medication_request
                ]
            ],
            "quantity" => [
                "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                "code" => "TAB",
                "value" => 120
            ],
            "daysSupply" => [
                "value" => 30,
                "unit" => "Day",
                "system" => "http://unitsofmeasure.org",
                "code" => "d"
            ],
            "whenPrepared" => now()->format('Y-m-d\TH:i:sP'),
            "whenHandedOver" => now()->format('Y-m-d\TH:i:sP'),
            "dosageInstruction" => [
                [
                    "sequence" => 1,
                    "text" => "Diminum 4 tablet sekali dalam sehari",
                    "timing" => [
                        "repeat" => [
                            "frequency" => 1,
                            "period" => 1,
                            "periodUnit" => "d"
                        ]
                    ],
                    "doseAndRate" => [
                        [
                            "type" => [
                                "coding" => [
                                    [
                                        "system" => "http://terminology.hl7.org/CodeSystem/dose-rate-type",
                                        "code" => "ordered",
                                        "display" => "Ordered"
                                    ]
                                ]
                            ],
                            "doseQuantity" => [
                                "value" => 4,
                                "unit" => "TAB",
                                "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                                "code" => "TAB"
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $json = json_encode($data, JSON_PRETTY_PRINT);
        

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $tokenAccess->data->token
        ])->post($this->baseUrl . '/fhir-r4/v1/MedicationDispense', $data);

        if ($response->successful()) {
            return response()->json($response->json(), 201);
        } else {
            // handle error response
            return response()->json($response->json(), $response->status());
        }

    }
    
}

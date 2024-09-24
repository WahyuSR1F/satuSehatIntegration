<?php

namespace App\Http\Controllers\RawatInap;

use App\Models\KFA;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\TokenAccessContorller;

class MedicationController extends Controller
{
    private $baseUrl = 'https://api-satusehat-stg.dto.kemkes.go.id/fhir-r4/v1';
    private $OrgId ;

    

    public function __construct()
    {
 
        $this->OrgId =  env('ORG_ID');

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
                    'system' => 'http://sys-ids.kemkes.go.id/medication/'. $this->OrgId,
                    'use' => 'official',
                    'value' => '123456789'
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
                'reference' => "Organization/". $this->OrgId
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
        ])->post($this->baseUrl . '/Medication', $data);

        // Handle the response
        if ($response->successful()) {
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
}

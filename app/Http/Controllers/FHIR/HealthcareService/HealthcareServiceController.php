<?php

namespace App\Http\Controllers\FHIR\HealthcareService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HealthcareServiceController extends Controller
{
    private $baseUrl;
    private $ecounter;
    public function __construct()
    {
        $this->baseUrl =  env('URL_API');
        $this->ecounter = env('E_COUNTER');
    }
     
    function createHealthcareService(Request $request) {
        $tokenAccess = (new TokenAccessContorller())->getToken();
        $tokenAccess = $tokenAccess->getData();
    
        $organizationId = $request->org_id;
    
        $data = [
            "resourceType" => "HealthcareService",
            "identifier" => [
                [
                    "system" => "http://sys-ids.kemkes.go.id/healthcareservice/665ed4bb-62dc-4fc8-bcb1-8f9c440102fb",
                    "value" => "HS-19920029"
                ]
            ],
            "active" => true,
            "providedBy" => [
                "reference" => "Organization/".$organizationId
            ],
            "type" => [
                [
                    "coding" => [
                        [
                            "system" => "http://sys-ids.kemkes.go.id/bpjs-poli",
                            "code" => "JAN",
                            "display" => "Poli Jantung"
                        ]
                    ]
                ],
                [
                    "coding" => [
                        [
                            "system" => "http://terminology.hl7.org/CodeSystem/service-type",
                            "code" => "305",
                            "display" => "Counselling"
                        ]
                    ]
                ],
                [
                    "coding" => [
                        [
                            "system" => "http://terminology.hl7.org/CodeSystem/service-type",
                            "code" => "221",
                            "display" => "Surgery - General"
                        ]
                    ]
                ]
            ],
            "specialty" => [
                [
                    "coding" => [
                        [
                            "system" => "http://terminology.kemkes.go.id/CodeSystem/clinical-speciality",
                            "code" => "S001.09",
                            "display" => "Penyakit dalam kardiovaskular "
                        ]
                    ]
                ]
            ],
            "location" => [
                [
                    "reference" => "Location/b017aa54-f1df-4ec2-9d84-8823815d7228",
                    "display" => "Ruang 1A, Poliklinik Bedah Rawat Jalan Terpadu, Lantai 2, Gedung G"
                ]
            ],
            "name" => "Poliklinik Bedah Rawat Jalan Terpadu",
            "program" => [
                [
                    "coding" => [
                        [
                            "system" => "http://terminology.kemkes.go.id/CodeSystem/program",
                            "code" => "1000200",
                            "display" => "Program JKN"
                        ]
                    ]
                ]
            ]
        ];
    
        $json = json_encode($data, JSON_PRETTY_PRINT);
    
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $tokenAccess->data->token
        ])->post($this->baseUrl . '/fhir-r4/v1/HealthcareService', $data);
    
        if ($response->successful()) {
            return $response->json()['id'];
        } else {
            // handle error response
            return null;
        }
    }
}

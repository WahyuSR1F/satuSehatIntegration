<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FHIR\CarePlan\CarePlanController;
use App\Http\Controllers\FHIR\Condition\ConditionController;
use App\Http\Controllers\FHIR\Procedure\ProcedureController;
use App\Http\Controllers\FHIR\RawatInap\MedicationController;
use App\Http\Controllers\FHIR\Diagnostic\DiagnosticController;
use App\Http\Controllers\FHIR\Composition\CompositionController;
use App\Http\Controllers\FHIR\Observation\ObservationController;
use App\Http\Controllers\FHIR\HealthcareService\HealthcareServiceController;
use App\Http\Controllers\FHIR\AllergyIntolerance\AllergyIntoleranceController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/medication-create',[MedicationController::class, 'createMedication']);
Route::post('/medication-request-create',[MedicationController::class, 'createMedicationRequest']);
Route::post('/medication-dispense',[MedicationController::class, 'createMedicationDispense']);
Route::post('/diagnostic-create',[DiagnosticController::class, 'createDiagnosticReport']);
Route::post('/observation-create',[ObservationController::class, 'createObservation']);
Route::post('/procedure-create',[ProcedureController::class, 'createProcedure']);
Route::post('/condition-create',[ConditionController::class, 'createCondition']);
Route::post('/careplan-create',[CarePlanController::class,'createCarePlan']);
Route::post('/composition-create',[CompositionController::class,'createComposition']);
Route::post('/allintolerance-create',[AllergyIntoleranceController::class,'createAllergyIntolerance']);
Route::post('/healthcare-create',[HealthcareServiceController::class,'createHealthcareService']);

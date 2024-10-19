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
use App\Http\Controllers\UseCase\Skrining\SkriningPTM\ObsesitasController;
use App\Http\Controllers\FHIR\HealthcareService\HealthcareServiceController;
use App\Http\Controllers\UseCase\Skrining\SkriningPTM\SkriningMataController;
use App\Http\Controllers\FHIR\AllergyIntolerance\AllergyIntoleranceController;
use App\Http\Controllers\UseCase\Skrining\SkriningPTM\TeknananDarahController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::group(['controller' => MedicationController::class], function () {
    Route::post('/medication-create', 'createMedication');
    Route::post('/medication-request-create', 'createMedicationRequest');
    Route::post('/medication-dispense', 'createMedicationDispense');
});

Route::group(['controller' => DiagnosticController::class], function () {
    Route::post('/diagnostic-create', 'createDiagnosticReport');
});

Route::group(['controller' => ObservationController::class], function () {
    Route::post('/observation-create', 'createObservation');
});

Route::group(['controller' => ProcedureController::class], function () {
    Route::post('/procedure-create', 'createProcedure');
});

Route::group(['controller' => ConditionController::class], function () {
    Route::post('/condition-create', 'createCondition');
});

Route::group(['controller' => CarePlanController::class], function () {
    Route::post('/careplan-create', 'createCarePlan');
});

Route::group(['controller' => CompositionController::class], function () {
    Route::post('/composition-create', 'createComposition');
});

Route::group(['controller' => AllergyIntoleranceController::class], function () {
    Route::post('/allintolerance-create', 'createAllergyIntolerance');
});

Route::group(['controller' => HealthcareServiceController::class], function () {
    Route::post('/healthcare-create', 'createHealthcareService');
});

Route::group(['controller' => ObsesitasController::class], function () {
    Route::post('/beratBadan-set', 'createBeratBadan');
    Route::post('/tinggiBadan-set','tinggiBadanSet');
    Route::post('/imt-set','IMTset');
    Route::post('/lingkar-pinggang-set','lingkarPinggangSet');

});

Route::group(['controller' => TeknananDarahController::class], function () {
    Route::post('/set-sistolk', 'setSistolk');
    Route::post('/set-diastolk','setDiastolk');
    Route::post('/set-hipertensi', 'Hipertensi');
});

Route::group(['controller' => SkriningMataController::class], function () {
    Route::post('/visus-mata-kanan', 'visusMataKanan');
   
});


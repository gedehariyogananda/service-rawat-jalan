<?php
$router->group(['prefix' => 'medicine'], function () use ($router) {
    $router->get('/', 'PharmacyController@listMedicine');
    $router->get('/{id}', 'PharmacyController@detailMedicine');
    $router->post('/destroy', 'PharmacyController@destroyDrug');
    $router->post('/', 'PharmacyController@createDrug');
    $router->post('/update', 'PharmacyController@updateDrug');
    $router->post('/update/stock', 'PharmacyController@updateDrugStock');
});

$router->group(['prefix' => 'prescription'], function () use ($router) {
    $router->get('/', 'PharmacyController@getPrescriptionApi');
    $router->get('/detail', 'PharmacyController@getPrescriptionDetailApi');
    $router->post('/detail/destroy', 'PharmacyController@destroyDrugInPrescription');
    $router->post('/createDrugInPrescription', 'PharmacyController@createDrugInPrescription');
    $router->post('/updateDrugInPrescription', 'PharmacyController@updateDrugInPrescription');
    $router->get('/checkout', 'PharmacyController@checkoutPrescription');
});

<?php
$router->group(['prefix' => 'kunjungan'], function () use ($router) {
    $router->get('/', 'RawatjalanController@index');
    $router->get('/{id}', 'RawatjalanController@getDataKunjunganById');
    $router->get('/get-submission-data/{id}', 'RawatjalanController@addDataKunjungan');
    $router->patch('/add-data-submission/{id}', 'RawatjalanController@addDataSubmission');
    $router->patch('/add-diagnosa-resep/{id}', 'RawatJalanController@addDiagnosaResep');
    $router->get('/get-update-kunjungan/{id}', 'RawatJalanController@getUpdateKunjungan');
    $router->patch('/update-result-kunjungan/{id}', 'RawatJalanController@updateKunjungan');
    $router->delete('/delete-kunjungan/{id}', 'RawatJalanController@deleteKunjungan');
    $router->get('/sorting/sudah-pemeriksaan', 'RawatJalanController@getKunjunganSudahPemeriksaan');
    $router->get('/sorting/belum-pemeriksaan', 'RawatJalanController@getKunjunganBelumPemeriksaan');
});

$router->group(['prefix' => 'set'], function () use ($router) {
    $router->get('/pemeriksaan', 'RawatJalanController@getSetPemeriksaan');
    $router->get('/pemeriksaan/apotik/{id}', 'RawatJalanController@setPemeriksaan');
});

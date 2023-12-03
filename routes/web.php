<?php
$router->group(['prefix' => 'kunjungan'], function () use ($router) {
    $router->get('/', 'RawatjalanController@index');
    $router->get('/{id}', 'RawatjalanController@getDataKunjunganById');
    $router->get('/get-submission-data/{id}', 'RawatjalanController@addDataKunjungan');
    $router->patch('/add-data-submission/{id}', 'RawatjalanController@addDataSubmission');
    $router->patch('/add-diagnosa-resep/{id}', 'RawatjalanController@addDiagnosaResep');
    $router->get('/get-update-kunjungan/{id}', 'RawatjalanController@getUpdateKunjungan');
    $router->patch('/update-result-kunjungan/{id}', 'RawatjalanController@updateKunjungan');
    $router->delete('/delete-kunjungan/{id}', 'RawatjalanController@deleteKunjungan');
    $router->get('/sorting/sudah-pemeriksaan', 'RawatjalanController@getKunjunganSudahPemeriksaan');
    $router->get('/sorting/belum-pemeriksaan', 'RawatjalanController@getKunjunganBelumPemeriksaan');
});

$router->group(['prefix' => 'set'], function () use ($router) {
    $router->get('/pemeriksaan', 'RawatjalanController@getSetPemeriksaan');
    $router->get('/pemeriksaan/apotik/{id}', 'RawatjalanController@setPemeriksaan');
});

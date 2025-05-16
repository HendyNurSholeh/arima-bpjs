<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', function () {
    return redirect()->to('/login');
});
$routes->get('/admin/dashboard', 'AdminController::dashboard');
// dataset menu
$routes->get('/admin/dataset', 'AdminController::dataset');
$routes->post('/admin/dataset/import', 'AdminController::import');
$routes->get('/admin/dataset/clear', 'AdminController::clear');

$routes->get('/admin/peforma', 'AdminController::peforma');
$routes->get('/admin/peforma/uji-akurasi', 'AdminController::ujiAkurasi');
$routes->get('/admin/prediksi', 'AdminController::prediksi');
$routes->get('/admin/akun', 'AdminController::akun');
$routes->post('/admin/akun/reset_password', 'AdminController::reset_password');
$routes->get('/login', 'LoginController::login');
$routes->get('/logout', 'LoginController::logout');
$routes->post('/login', 'LoginController::postLogin');
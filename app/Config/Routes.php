<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

/*

| PUBLIC ROUTES (NO LOGIN REQUIRED
*/
$routes->match(['get', 'post'], '/', 'StaffController::login');
$routes->match(['get', 'post'], 'login', 'StaffController::login');

$routes->get('logout', 'StaffController::logout');
$routes->get('admin/logout', 'StaffController::logout');


/*
| PUBLIC APPOINTMENT (QR SCAN – NO LOGIN)
*/
$routes->get('appointment/form/(:num)', 'Appointment::form/$1');
$routes->post('appointment/submit', 'Appointment::submit');
$routes->get('appointment/success', 'Appointment::success');


/*
| PROTECTED ROUTES (LOGIN REQUIRED)
*/
$routes->group('', ['filter' => 'auth'], function ($routes) {

    /*
    | STAFF ROUTES
    */
    $routes->group('staff', function ($routes) {
        $routes->get('/', 'StaffController::index');
        $routes->match(['get', 'post'], 'create', 'StaffController::create');
        $routes->match(['get', 'post'], 'update/(:any)', 'StaffController::update/$1');
        $routes->get('delete/(:any)', 'StaffController::delete/$1');
    });

    /*
    | ADMIN ROUTES
    */
    $routes->group('admin', function ($routes) {
        $routes->get('dashboard', 'AdminDashboard::index');
        $routes->get('appointments', 'AdminAppointments::index');
        $routes->get('appointment/approve/(:num)', 'AdminAppointments::approve/$1');
        $routes->get('appointment/reject/(:num)', 'AdminAppointments::reject/$1');
    });

    /*
    | DEV ONLY

    */
    $routes->get('gen-pass', function () {
        echo password_hash('123456', PASSWORD_DEFAULT);
    });
});

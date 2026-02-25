<?php

// use CodeIgniter\Router\RouteCollection;

// /**
//  * @var RouteCollection $routes
//  */

// /*

// | PUBLIC ROUTES (NO LOGIN REQUIRED
// */
// $routes->match(['get', 'post'], '/', 'StaffController::login');
// $routes->match(['get', 'post'], 'login', 'StaffController::login');

// $routes->get('logout', 'StaffController::logout');
// $routes->get('admin/logout', 'StaffController::logout');


// /*
// | PUBLIC APPOINTMENT (QR SCAN – NO LOGIN)
// */
// $routes->get('appointment/form/(:num)', 'Appointment::form/$1');
// $routes->post('appointment/submit', 'Appointment::submit');
// $routes->get('appointment/success', 'Appointment::success');


// /*
// | PROTECTED ROUTES (LOGIN REQUIRED)
// */
// $routes->group('', ['filter' => 'auth'], function ($routes) {

//     /*
//     | STAFF ROUTES
//     */
//     $routes->group('staff', function ($routes) {
//         $routes->get('appointments', 'StaffController::appointments');
//         $routes->get('/', 'StaffController::index');
//         $routes->get('dashboard', 'StaffController::dashboard');
//         $routes->match(['get', 'post'], 'create', 'StaffController::create');
//         $routes->match(['get', 'post'], 'update/(:any)', 'StaffController::update/$1');
//         $routes->get('delete/(:any)', 'StaffController::delete/$1');
//         $routes->get('appointment/approve/(:num)', 'StaffController::approve/$1');
//         $routes->get('appointment/reject/(:num)', 'StaffController::reject/$1');
//     });

//     /*
//     | ADMIN ROUTES
//     */
//     $routes->group('admin', function ($routes) {
//         $routes->get('dashboard', 'AdminDashboard::index');
//         $routes->get('appointments', 'AdminAppointments::index');
//         $routes->get('appointment/approve/(:num)', 'AdminAppointments::approve/$1');
//         $routes->get('appointment/reject/(:num)', 'AdminAppointments::reject/$1');
//     });
//     //   | SECURITY ROUTES  ← ADD HERE
//  $routes->group('security', function ($routes) {
//     $routes->get('/', 'SecurityController::index');
//     $routes->get('dashboard', 'SecurityController::index');
//     $routes->get('checkin/(:num)', 'SecurityController::checkin/$1');
//    $routes->get('checkout/(:num)', 'SecurityController::checkout/$1');

// });

//     /*
//     | DEV ONLY

//     */
//     $routes->get('gen-pass', function () {
//         echo password_hash('123456', PASSWORD_DEFAULT);
//     });
// });

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

/*
| PUBLIC ROUTES
*/
$routes->match(['get', 'post'], '/', 'StaffController::login');
$routes->match(['get', 'post'], 'login', 'StaffController::login');

$routes->get('logout', 'StaffController::logout');
$routes->get('admin/logout', 'StaffController::logout');

/*
| PUBLIC APPOINTMENT
*/
$routes->get('appointment/form/(:num)', 'Appointment::form/$1');
$routes->post('appointment/submit', 'Appointment::submit');
$routes->get('appointment/success', 'Appointment::success');

// |appoiment QR
$routes->get('appointment/qr/(:num)', 'Appointment::generateQR/$1');
$routes->get('appointment/view/(:num)', 'Appointment::view/$1');
$routes->get('appointment/edit/(:num)', 'Appointment::edit/$1');
$routes->post('appointment/update/(:num)', 'Appointment::update/$1');
/*
| PROTECTED ROUTES
*/
$routes->group('', ['filter' => 'auth'], function ($routes) {

    // USERS LIST
    $routes->get('users', 'StaffController::list');

    /*
    | STAFF ROUTES
    */
    $routes->group('staff', function ($routes) {

        $routes->get('dashboard', 'StaffController::dashboard');
        // CREATE
        $routes->get('create', 'StaffController::create', ['filter' => 'admin']);
        $routes->post('save', 'StaffController::save', ['filter' => 'admin']);
        // VIEW FORM
      $routes->get('edit/(:any)', 'StaffController::edit/$1', ['filter' => 'admin']);
        // UPDATE
      $routes->post('update/(:any)', 'StaffController::update/$1', ['filter' => 'admin']);
        // DELETE
        $routes->get('delete/(:any)', 'StaffController::delete/$1', ['filter' => 'admin']);
        // APPOINTMENTS
        $routes->get('appointment/approve/(:num)', 'StaffController::approve/$1');
        $routes->get('appointment/reject/(:num)', 'StaffController::reject/$1');
    });

    /*
    | ADMIN ROUTES
    */
    $routes->group('admin', function ($routes) {
        $routes->get('dashboard', 'AdminDashboard::index');
        $routes->get('appointments', 'AdminAppointments::index');
        $routes->get('appointment/approve/(:num)', 'AdminDashboard::approve/$1');
        $routes->get('appointment/reject/(:num)', 'AdminDashboard::reject/$1');
    });

    /*
    | SECURITY ROUTES
    */
    $routes->group('security', function ($routes) {
        $routes->get('/', 'SecurityController::index');
        $routes->get('dashboard', 'SecurityController::index');
        $routes->get('checkin/(:num)', 'SecurityController::checkin/$1');
        $routes->get('checkout/(:num)', 'SecurityController::checkout/$1');
    });

    /*
    | DEV
    */
    $routes->get('gen-pass', function () {
        echo password_hash('123456', PASSWORD_DEFAULT);
    });
});

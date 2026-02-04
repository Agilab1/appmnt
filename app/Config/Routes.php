<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// 🌐 ROOT URL
$routes->match(['get','post'], '/', 'StaffController::login');

// 🔐 LOGIN / LOGOUT (NO AUTH)
$routes->match(['get','post'], 'staff/login', 'StaffController::login');
$routes->get('staff/logout', 'StaffController::logout');

// 🔒 AUTH PROTECTED ROUTES
$routes->group('', ['filter' => 'auth'], function ($routes) {

    $routes->get('staff', 'StaffController::index');
    $routes->match(['get','post'], 'staff/create', 'StaffController::create');
    $routes->match(['get','post'], 'staff/update/(:any)', 'StaffController::update/$1');
    $routes->get('staff/delete/(:any)', 'StaffController::delete/$1');

});

<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

$routes = Services::routes();

if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

// Auth Routes
$routes->get('auth', 'AuthController::index');
$routes->get('auth/login', 'AuthController::login');
$routes->post('auth/login', 'AuthController::login');
$routes->get('auth/logout', 'AuthController::logout');

// Protected Routes
$routes->group('', ['filter' => 'auth'], function($routes) {
    // Dashboard Route
    $routes->get('dashboard', 'DashboardController::index');
    
    // Profile and Account Routes
    $routes->get('profile', 'ProfileController::index');
    $routes->get('profile/edit', 'ProfileController::edit');
    $routes->post('profile/update', 'ProfileController::update');
    $routes->get('account/edit', 'AccountController::edit');
    $routes->post('account/update', 'AccountController::update');

    // Member Routes
    $routes->get('member', 'MemberController::index');
    $routes->get('member/create', 'MemberController::create');
    $routes->post('member/store', 'MemberController::store');
    $routes->get('member/edit/(:num)', 'MemberController::edit/$1');
    $routes->post('member/update/(:num)', 'MemberController::update/$1');
    $routes->get('member/delete/(:num)', 'MemberController::delete/$1');
    $routes->get('member/detail/(:num)', 'MemberController::detail/$1');

    // Membership Type Routes
    $routes->get('membershiptype', 'MembershipTypeController::index');
    $routes->get('membershiptype/create', 'MembershipTypeController::create');
    $routes->post('membershiptype/store', 'MembershipTypeController::store');
    $routes->get('membershiptype/edit/(:num)', 'MembershipTypeController::edit/$1');
    $routes->post('membershiptype/update/(:num)', 'MembershipTypeController::update/$1');
    $routes->get('membershiptype/delete/(:num)', 'MembershipTypeController::delete/$1');

    // Transaction Routes
    $routes->group('transaction', function($routes) {
        $routes->get('/', 'TransactionController::index');
        $routes->post('store', 'TransactionController::store');
        $routes->post('delete/(:num)', 'TransactionController::delete/$1', ['filter' => 'role:pemilik']);
        $routes->get('receipt/(:num)', 'TransactionController::receipt/$1');
    });

    // Financial Report Routes
    $routes->get('financial-report', 'FinancialReportController::index');
    $routes->get('financial-report/daily', 'FinancialReportController::daily');
    $routes->get('financial-report/monthly', 'FinancialReportController::monthly');
    $routes->get('financial-report/yearly', 'FinancialReportController::yearly');

    // Check In/Out Routes
    $routes->get('check-in-out', 'CheckInOutController::index');
    $routes->post('check-in-out/check-in', 'CheckInOutController::checkIn');
    $routes->post('check-in-out/check-out', 'CheckInOutController::checkOut');
    
    // Absensi Karyawan (Employee Attendance) Routes
    $routes->get('absensi_karyawan', 'AbsensiKaryawanController::index', ['filter' => 'auth']);
    $routes->get('absensi_karyawan/laporan', 'AbsensiKaryawanController::laporan', ['filter' => 'auth']);
    $routes->get('absensi_karyawan/edit/(:num)', 'AbsensiKaryawanController::edit/$1', ['filter' => 'auth']);
    $routes->get('absensi_karyawan/delete/(:num)', 'AbsensiKaryawanController::delete/$1', ['filter' => 'auth']);
    $routes->get('absensi_karyawan/rekap/(:num)', 'AbsensiKaryawanController::rekapKaryawan/$1', ['filter' => 'auth']);
    $routes->post('absensi_karyawan/clockin', 'AbsensiKaryawanController::clockIn', ['filter' => 'auth']);
    $routes->post('absensi_karyawan/clockout', 'AbsensiKaryawanController::clockOut', ['filter' => 'auth']);
    $routes->post('absensi_karyawan/update/(:num)', 'AbsensiKaryawanController::update/$1', ['filter' => 'auth']);
});

// Home Route
$routes->get('/', function() {
    return redirect()->to('auth/login');
});

// Karyawan Routes
$routes->group('karyawan', function($routes) {
    $routes->get('', 'KaryawanController::index', ['filter' => 'role:admin,pemilik']);
    $routes->get('new', 'KaryawanController::new', ['filter' => 'role:admin,pemilik']);
    $routes->post('create', 'KaryawanController::create', ['filter' => 'role:admin,pemilik']);
    $routes->get('edit/(:num)', 'KaryawanController::edit/$1', ['filter' => 'role:admin,pemilik']);
    $routes->post('update/(:num)', 'KaryawanController::update/$1', ['filter' => 'role:admin,pemilik']);
    $routes->get('delete/(:num)', 'KaryawanController::delete/$1', ['filter' => 'role:admin,pemilik']);
    $routes->get('(:num)', 'KaryawanController::show/$1', ['filter' => 'role:admin,pemilik']);
    $routes->get('(:num)/createaccount', 'KaryawanController::createAccount/$1', ['filter' => 'role:admin,pemilik']);
    $routes->post('(:num)/storeaccount', 'KaryawanController::storeAccount/$1', ['filter' => 'role:admin,pemilik']);
    $routes->get('(:num)/editaccount', 'KaryawanController::editAccount/$1', ['filter' => 'role:admin,pemilik']);
    $routes->post('(:num)/updateaccount', 'KaryawanController::updateAccount/$1', ['filter' => 'role:admin,pemilik']);
    $routes->get('(:num)/deleteaccount', 'KaryawanController::deleteAccount/$1', ['filter' => 'role:admin,pemilik']);
    $routes->get('(:num)/togglestatus', 'KaryawanController::toggleStatus/$1', ['filter' => 'role:admin,pemilik']);
});

// API Routes
$routes->group('api', function($routes) {
    $routes->get('karyawan', 'KaryawanController::getKaryawanAPI');
    $routes->get('karyawan/(:num)', 'KaryawanController::getKaryawanAPI/$1');
});

// Explicitly define routes for access to Home controller methods
$routes->get('/', 'Home::index');
$routes->get('/home', 'Home::index');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it. 
 */

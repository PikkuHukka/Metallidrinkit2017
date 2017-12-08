<?php

function check_logged_in() {
    BaseController::check_logged_in();
}

function check_logged_out() {
    BaseController::check_logged_out();
}

function check_logged_admin() {
    BaseController::check_logged_admin();
}

$routes->get('/', function() {
    DrinkkiController::index();
});

$routes->get('/login', function() {
    DrinkkiController::login();
});

$routes->get('/drinkit', function() {
    DrinkkiController::drinkit();
});

$routes->post('/drinkit', function() {
    DrinkkiController::drinkitHaku();
});


$routes->post('/drinkit/uusi', 'check_logged_in', function() {
    DrinkkiController::store();
});
$routes->get('/drinkit/uusi', 'check_logged_in', function() {
    DrinkkiController::lisays();
});

$routes->get('/drinkit/:id', function($id) {
    DrinkkiController::show($id);
});

$routes->get('/drinkit/:id/edit', 'check_logged_in', function($id) {
    DrinkkiController::edit($id);
});
$routes->post('/drinkit/:id/edit', 'check_logged_in', function($id) {
    DrinkkiController::update($id);
});

$routes->post('/drinkit/:id/poista', 'check_logged_in', function($id) {
    DrinkkiController::destroy($id);
});
$routes->get('/login', 'check_logged_out', function() {
    UserController::login();
});
$routes->post('/login', function() {
    UserController::handle_login();
});
$routes->post('/logout', function() {
    UserController::logout();
});

$routes->get('/login/uusikayttaja', 'check_logged_out', function() {
    UserController::uusikayttaja();
});

$routes->post('/login/uusikayttaja', function() {
    UserController::luokayttaja();
});

$routes->get('/kayttajahallinta', 'check_logged_admin', function() {
    UserController::kayttajat();
});
$routes->get('/kayttajahallinta/:id/edit', 'check_logged_admin', function($id) {
    UserController::edit($id);
});

$routes->post('/kayttajahallinta/:id/poista', 'check_logged_admin', function($id) {
    UserController::destroy($id);
});
$routes->post('/kayttajahallinta', function() {
    UserController::kayttajaHaku();
});

$routes->post('/kayttajahallinta/:id/salasana', 'check_logged_admin', function($id) {
    UserController::salasananVaihto($id);
});
$routes->post('/kayttajahallinta/:id/edit', 'check_logged_admin', function($id) {
    UserController::update($id);
});


$routes->get('/ainesosat', function() {
    AinesosaController::ainesosat();
});

$routes->get('/ainesosat/:id', function($id) {
    AinesosaController::drinkitAinesosienMukaan($id);
});

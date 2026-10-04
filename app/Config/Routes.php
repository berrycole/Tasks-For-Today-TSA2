<?php
use CodeIgniter\Router\RouteCollection;
/** @var RouteCollection $routes */
$routes->setAutoRoute(false);
$routes->get('/', 'Pages::index');
$routes->get('about', 'Pages::about');
$routes->get('profile', 'Pages::profile');
$routes->get('tasks', 'Tasks::index');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::authenticate');
$routes->post('logout', 'Auth::logout');
$routes->group('', ['filter' => 'auth'], static function (RouteCollection $routes) {
    $routes->get('tasks/new', 'Tasks::new');
    $routes->post('tasks', 'Tasks::create');
    $routes->get('tasks/(:num)/edit', 'Tasks::edit/$1');
    $routes->post('tasks/(:num)', 'Tasks::update/$1');
    $routes->post('tasks/(:num)/delete', 'Tasks::delete/$1');
});

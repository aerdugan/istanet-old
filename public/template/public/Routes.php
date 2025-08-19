

// Admin RBAC protected routes
$routes->group('users', ['filter' => 'adminonly'], function($routes){
    $routes->get('/', 'Modules\Users\Controllers\UsersController::index');
    $routes->get('getUsers', 'Modules\Users\Controllers\UsersController::getUsers');
    $routes->post('saveUser', 'Modules\Users\Controllers\UsersController::saveUser');
    $routes->post('deleteUser/(:num)', 'Modules\Users\Controllers\UsersController::deleteUser/$1');
});

$routes->group('groups', ['filter' => 'adminonly'], function($routes){
    $routes->get('/', 'Modules\Users\Controllers\GroupsController::index');
    $routes->get('getGroups', 'Modules\Users\Controllers\GroupsController::getGroups');
    $routes->post('saveGroup', 'Modules\Users\Controllers\GroupsController::saveGroup');
    $routes->post('deleteGroup/(:num)', 'Modules\Users\Controllers\GroupsController::deleteGroup/$1');
});

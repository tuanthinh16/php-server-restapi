<?php

require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../utils/LoggerTrait.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../utils/RateLimiter.php';

$logger = (new class {
    use LoggerTrait;
})->log('api');

$userController = new UserController();
$authController = new AuthController();

Flight::before('start', function () use ($logger) {
    $method = $_SERVER['REQUEST_METHOD'];
    $uri = $_SERVER['REQUEST_URI'];
    $input = Flight::request()->data->getData();

    $logger->info("{$method} {$uri}", (array) $input);
});
// Authentication routes
Flight::route('POST /api/login', function () use ($authController) {
    $data = Flight::request()->data->getData();
    $authController->login($data);
});

// User routes
Flight::route('GET /api/users', function () use ($userController) {
    $userController->getAll();
});
//add user
Flight::route('POST /api/users', function () use ($userController) {

    RateLimiter::limit('user-api', 30, 60);
    $data = Flight::request()->data->getData();
    $userController->create($data);
});
//find user
Flight::route('GET /api/users/@id', function ($id) use ($userController) {
    $userController->getUserById($id);
});


//Cache
Flight::route('DELETE /api/resetcache', function () {
    AuthMiddleware::handle();
    $redis = RedisHelper::getInstance();
    $redis->flushdb(); // Xóa toàn bộ cache DB hiện tại

    Flight::json(['message' => 'All Redis cache cleared']);
});
Flight::route('DELETE /api/resetcache/@prefix', function ($prefix) {
    $redis = RedisHelper::getInstance();
    $keys = $redis->keys($prefix . '*');

    if (!empty($keys)) {
        $redis->del($keys);
    }

    Flight::json([
        'message' => "Deleted keys with prefix '$prefix'",
        'count' => count($keys)
    ]);
});

//test instance nginx
Flight::route('GET /ping', function () {
    echo 'Instance: ' . gethostname();
});

<?php

declare(strict_types=1);

session_start();

$config = require __DIR__ . '/config/config.php';
$GLOBALS['config'] = $config;

require __DIR__ . '/core/Database.php';
require __DIR__ . '/core/View.php';
require __DIR__ . '/core/Auth.php';
require __DIR__ . '/core/Router.php';
require __DIR__ . '/models/Admin.php';
require __DIR__ . '/controllers/AdminAuthController.php';
require __DIR__ . '/controllers/AdminController.php';
require __DIR__ . '/controllers/AdminProfileController.php';
require __DIR__ . '/models/FoodCategory.php';
require __DIR__ . '/models/FoodItem.php';
require __DIR__ . '/controllers/FoodCategoryController.php';
require __DIR__ . '/controllers/FoodItemController.php';
require __DIR__ . '/controllers/FoodApiController.php';
require __DIR__ . '/controllers/UploadController.php';
require __DIR__ . '/models/TravelCategory.php';
require __DIR__ . '/models/TravelItem.php';
require __DIR__ . '/controllers/TravelCategoryController.php';
require __DIR__ . '/controllers/TravelItemController.php';
require __DIR__ . '/controllers/TravelApiController.php';

$requireFirstExisting = static function (array $paths): void {
    foreach ($paths as $path) {
        if (is_file($path)) {
            require $path;
            return;
        }
    }
};

$requireFirstExisting([
    __DIR__ . '/models/ShopCategory.php',
    __DIR__ . '/models/shopcategory.php',
]);
$requireFirstExisting([
    __DIR__ . '/models/ShopItem.php',
    __DIR__ . '/models/shopitem.php',
]);
$requireFirstExisting([
    __DIR__ . '/controllers/ShopCategoryController.php',
    __DIR__ . '/controllers/shopcategorycontroller.php',
]);
$requireFirstExisting([
    __DIR__ . '/controllers/ShopItemController.php',
    __DIR__ . '/controllers/shopitemcontroller.php',
]);
$requireFirstExisting([
    __DIR__ . '/controllers/ShopApiController.php',
    __DIR__ . '/controllers/shopapicontroller.php',
]);

$requireFirstExisting([
    __DIR__ . '/models/LiveCategory.php',
    __DIR__ . '/models/livecategory.php',
]);
$requireFirstExisting([
    __DIR__ . '/models/LiveItem.php',
    __DIR__ . '/models/liveitem.php',
]);
$requireFirstExisting([
    __DIR__ . '/controllers/LiveCategoryController.php',
    __DIR__ . '/controllers/livecategorycontroller.php',
]);
$requireFirstExisting([
    __DIR__ . '/controllers/LiveItemController.php',
    __DIR__ . '/controllers/liveitemcontroller.php',
]);
$requireFirstExisting([
    __DIR__ . '/controllers/LiveApiController.php',
    __DIR__ . '/controllers/liveapicontroller.php',
]);

$router = new Router();

$router->get('/admin/login', [AdminAuthController::class, 'showLogin']);
$router->post('/admin/login', [AdminAuthController::class, 'login']);
$router->post('/admin/logout', [AdminAuthController::class, 'logout']);

$router->get('/admin/profile', [AdminProfileController::class, 'edit']);
$router->post('/admin/profile', [AdminProfileController::class, 'update']);

$router->get('/admin/admins', [AdminController::class, 'index']);
$router->get('/admin/admins/create', [AdminController::class, 'create']);
$router->post('/admin/admins/create', [AdminController::class, 'store']);
$router->get('/admin/admins/edit', [AdminController::class, 'edit']);
$router->post('/admin/admins/edit', [AdminController::class, 'update']);
$router->post('/admin/admins/delete', [AdminController::class, 'destroy']);

$router->get('/admin/food/categories', [FoodCategoryController::class, 'index']);
$router->get('/admin/food/categories/create', [FoodCategoryController::class, 'create']);
$router->post('/admin/food/categories/create', [FoodCategoryController::class, 'store']);
$router->get('/admin/food/categories/edit', [FoodCategoryController::class, 'edit']);
$router->post('/admin/food/categories/edit', [FoodCategoryController::class, 'update']);
$router->post('/admin/food/categories/delete', [FoodCategoryController::class, 'destroy']);

$router->get('/admin/food/items', [FoodItemController::class, 'index']);
$router->get('/admin/food/items/create', [FoodItemController::class, 'create']);
$router->post('/admin/food/items/create', [FoodItemController::class, 'store']);
$router->get('/admin/food/items/edit', [FoodItemController::class, 'edit']);
$router->post('/admin/food/items/edit', [FoodItemController::class, 'update']);
$router->post('/admin/food/items/delete', [FoodItemController::class, 'destroy']);

$router->post('/admin/upload', [UploadController::class, 'upload']);
$router->get('/admin/upload/list', [UploadController::class, 'list']);

$router->get('/api/food/cover', [FoodApiController::class, 'cover']);
$router->get('/api/food/list', [FoodApiController::class, 'list']);
$router->get('/api/food/detail', [FoodApiController::class, 'detail']);

$router->get('/admin/travel/categories', [TravelCategoryController::class, 'index']);
$router->get('/admin/travel/categories/create', [TravelCategoryController::class, 'create']);
$router->post('/admin/travel/categories/create', [TravelCategoryController::class, 'store']);
$router->get('/admin/travel/categories/edit', [TravelCategoryController::class, 'edit']);
$router->post('/admin/travel/categories/edit', [TravelCategoryController::class, 'update']);
$router->post('/admin/travel/categories/delete', [TravelCategoryController::class, 'destroy']);

$router->get('/admin/travel/items', [TravelItemController::class, 'index']);
$router->get('/admin/travel/items/create', [TravelItemController::class, 'create']);
$router->post('/admin/travel/items/create', [TravelItemController::class, 'store']);
$router->get('/admin/travel/items/edit', [TravelItemController::class, 'edit']);
$router->post('/admin/travel/items/edit', [TravelItemController::class, 'update']);
$router->post('/admin/travel/items/delete', [TravelItemController::class, 'destroy']);

$router->get('/api/travel/cover', [TravelApiController::class, 'cover']);
$router->get('/api/travel/list', [TravelApiController::class, 'list']);
$router->get('/api/travel/detail', [TravelApiController::class, 'detail']);

if (class_exists(ShopCategoryController::class) && class_exists(ShopItemController::class) && class_exists(ShopApiController::class)) {
    $router->get('/admin/shop/categories', [ShopCategoryController::class, 'index']);
    $router->get('/admin/shop/categories/create', [ShopCategoryController::class, 'create']);
    $router->post('/admin/shop/categories/create', [ShopCategoryController::class, 'store']);
    $router->get('/admin/shop/categories/edit', [ShopCategoryController::class, 'edit']);
    $router->post('/admin/shop/categories/edit', [ShopCategoryController::class, 'update']);
    $router->post('/admin/shop/categories/delete', [ShopCategoryController::class, 'destroy']);

    $router->get('/admin/shop/items', [ShopItemController::class, 'index']);
    $router->get('/admin/shop/items/create', [ShopItemController::class, 'create']);
    $router->post('/admin/shop/items/create', [ShopItemController::class, 'store']);
    $router->get('/admin/shop/items/edit', [ShopItemController::class, 'edit']);
    $router->post('/admin/shop/items/edit', [ShopItemController::class, 'update']);
    $router->post('/admin/shop/items/delete', [ShopItemController::class, 'destroy']);

    $router->get('/api/shop/cover', [ShopApiController::class, 'cover']);
    $router->get('/api/shop/list', [ShopApiController::class, 'list']);
    $router->get('/api/shop/detail', [ShopApiController::class, 'detail']);
}

if (class_exists(LiveCategoryController::class) && class_exists(LiveItemController::class) && class_exists(LiveApiController::class)) {
    $router->get('/admin/live/categories', [LiveCategoryController::class, 'index']);
    $router->get('/admin/live/categories/create', [LiveCategoryController::class, 'create']);
    $router->post('/admin/live/categories/create', [LiveCategoryController::class, 'store']);
    $router->get('/admin/live/categories/edit', [LiveCategoryController::class, 'edit']);
    $router->post('/admin/live/categories/edit', [LiveCategoryController::class, 'update']);
    $router->post('/admin/live/categories/delete', [LiveCategoryController::class, 'destroy']);

    $router->get('/admin/live/items', [LiveItemController::class, 'index']);
    $router->get('/admin/live/items/create', [LiveItemController::class, 'create']);
    $router->post('/admin/live/items/create', [LiveItemController::class, 'store']);
    $router->get('/admin/live/items/edit', [LiveItemController::class, 'edit']);
    $router->post('/admin/live/items/edit', [LiveItemController::class, 'update']);
    $router->post('/admin/live/items/delete', [LiveItemController::class, 'destroy']);

    $router->get('/api/live/cover', [LiveApiController::class, 'cover']);
    $router->get('/api/live/list', [LiveApiController::class, 'list']);
    $router->get('/api/live/detail', [LiveApiController::class, 'detail']);
}

$router->dispatch();

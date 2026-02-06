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
require __DIR__ . '/models/FoodCategory.php';
require __DIR__ . '/models/FoodItem.php';
require __DIR__ . '/controllers/FoodCategoryController.php';
require __DIR__ . '/controllers/FoodItemController.php';
require __DIR__ . '/controllers/FoodApiController.php';
require __DIR__ . '/controllers/UploadController.php';

$router = new Router();

$router->get('/admin/login', [AdminAuthController::class, 'showLogin']);
$router->post('/admin/login', [AdminAuthController::class, 'login']);
$router->post('/admin/logout', [AdminAuthController::class, 'logout']);

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

$router->dispatch();

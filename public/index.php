<?php

declare(strict_types=1);

use App\Controller\ArticleController;
use App\Controller\CategoryController;
use App\Controller\HomeController;
use App\Core\Database;
use App\Core\Request;
use App\Core\Router;
use App\Core\View;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Service\ArticleService;
use App\Service\CategoryService;

require dirname(__DIR__) . '/vendor/autoload.php';

$config = require dirname(__DIR__) . '/config/config.php';

$pdo = Database::connect($config['db']);
$view = new View($config['app']['base_path'] . '/templates');

$categoryRepository = new CategoryRepository($pdo);
$articleRepository = new ArticleRepository($pdo);

$categoryService = new CategoryService($categoryRepository, $articleRepository);
$articleService = new ArticleService($articleRepository, $categoryRepository);

$router = new Router();
$router->get('/', [new HomeController($view, $categoryService), 'index']);
$router->get('/category/{id}', [new CategoryController($view, $categoryService), 'show']);
$router->get('/article/{id}', [new ArticleController($view, $articleService), 'show']);

$request = Request::fromGlobals();
$response = $router->dispatch($request);

http_response_code($response['status']);
echo $response['body'];

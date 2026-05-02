<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Request;
use App\Core\View;
use App\Service\CategoryService;

final class HomeController
{
    public function __construct(
        private readonly View $view,
        private readonly CategoryService $categoryService
    ) {
    }

    public function index(Request $request): string
    {
        return $this->view->render('home.tpl', [
            'title' => 'Blog',
            'categories' => $this->categoryService->getHomeCategories(),
        ]);
    }
}

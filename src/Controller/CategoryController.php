<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Request;
use App\Core\View;
use App\Service\CategoryService;

final class CategoryController
{
    public function __construct(
        private readonly View $view,
        private readonly CategoryService $categoryService
    ) {
    }

    /**
     * @return string|array{status:int,body:string}
     */
    public function show(Request $request, string $id): string|array
    {
        $page = filter_var($request->query('page', 1), FILTER_VALIDATE_INT) ?: 1;
        $sort = (string) $request->query('sort', 'date');
        $data = $this->categoryService->getCategoryPage((int) $id, $sort, $page);

        if ($data === null) {
            return ['status' => 404, 'body' => '<h1>Category not found</h1>'];
        }

        return $this->view->render('category.tpl', [
            'title' => $data['category']->title,
            ...$data,
        ]);
    }
}

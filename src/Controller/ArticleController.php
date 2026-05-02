<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Request;
use App\Core\View;
use App\Service\ArticleService;

final class ArticleController
{
    public function __construct(
        private readonly View $view,
        private readonly ArticleService $articleService
    ) {
    }

    /**
     * @return string|array{status:int,body:string}
     */
    public function show(Request $request, string $id): string|array
    {
        $data = $this->articleService->getArticlePage((int) $id);

        if ($data === null) {
            return ['status' => 404, 'body' => '<h1>Article not found</h1>'];
        }

        return $this->view->render('article.tpl', [
            'title' => $data['article']->title,
            ...$data,
        ]);
    }
}

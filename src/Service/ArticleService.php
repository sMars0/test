<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;

final class ArticleService
{
    public function __construct(
        private readonly ArticleRepository $articleRepository,
        private readonly CategoryRepository $categoryRepository
    ) {
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getArticlePage(int $id): ?array
    {
        $article = $this->articleRepository->findById($id);
        if ($article === null) {
            return null;
        }

        $this->articleRepository->incrementViews($id);
        $article = $this->articleRepository->findById($id);

        return [
            'article' => $article,
            'categories' => $this->categoryRepository->findByArticleId($id),
            'relatedArticles' => $this->articleRepository->findRelated($id, 3),
        ];
    }
}

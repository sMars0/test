<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;

final class CategoryService
{
    private const PER_PAGE = 5;

    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly ArticleRepository $articleRepository
    ) {
    }

    /**
     * @return list<array{category:object,articles:array}>
     */
    public function getHomeCategories(): array
    {
        $items = [];

        foreach ($this->categoryRepository->findWithArticles() as $category) {
            $items[] = [
                'category' => $category,
                'articles' => $this->articleRepository->findLatestByCategory($category->id, 3),
            ];
        }

        return $items;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getCategoryPage(int $id, string $sort, int $page): ?array
    {
        $category = $this->categoryRepository->findById($id);
        if ($category === null) {
            return null;
        }

        $sort = $sort === 'views' ? 'views' : 'date';
        $page = max(1, $page);
        $total = $this->articleRepository->countByCategory($id);
        $totalPages = max(1, (int) ceil($total / self::PER_PAGE));
        $page = min($page, $totalPages);
        $offset = ($page - 1) * self::PER_PAGE;

        return [
            'category' => $category,
            'articles' => $this->articleRepository->findByCategory($id, $sort, self::PER_PAGE, $offset),
            'sort' => $sort,
            'pagination' => [
                'current' => $page,
                'total' => $totalPages,
                'pages' => $this->buildPaginationLinks('/category/' . $id, $sort, $totalPages),
            ],
        ];
    }

    /**
     * @return list<array{number:int,url:string}>
     */
    private function buildPaginationLinks(string $baseUrl, string $sort, int $totalPages): array
    {
        $pages = [];

        for ($page = 1; $page <= $totalPages; $page++) {
            $pages[] = [
                'number' => $page,
                'url' => $baseUrl . '?' . http_build_query(['sort' => $sort, 'page' => $page]),
            ];
        }

        return $pages;
    }
}

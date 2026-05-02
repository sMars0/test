<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Article;
use PDO;

final class ArticleRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * @return list<Article>
     */
    public function findLatestByCategory(int $categoryId, int $limit): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT a.*
            FROM articles a
            INNER JOIN article_category ac ON ac.article_id = a.id
            WHERE ac.category_id = :category_id
            ORDER BY a.published_at DESC
            LIMIT :limit'
        );
        $stmt->bindValue('category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $this->mapRows($stmt->fetchAll());
    }

    /**
     * @return list<Article>
     */
    public function findByCategory(int $categoryId, string $sort, int $limit, int $offset): array
    {
        $orderBy = $sort === 'views' ? 'a.views_count DESC, a.published_at DESC' : 'a.published_at DESC';

        $stmt = $this->pdo->prepare(
            "SELECT a.*
            FROM articles a
            INNER JOIN article_category ac ON ac.article_id = a.id
            WHERE ac.category_id = :category_id
            ORDER BY {$orderBy}
            LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue('category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $this->mapRows($stmt->fetchAll());
    }

    public function countByCategory(int $categoryId): int
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM article_category WHERE category_id = :category_id');
        $stmt->execute(['category_id' => $categoryId]);

        return (int) $stmt->fetchColumn();
    }

    public function findById(int $id): ?Article
    {
        $stmt = $this->pdo->prepare('SELECT * FROM articles WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? Article::fromRow($row) : null;
    }

    public function incrementViews(int $id): void
    {
        $stmt = $this->pdo->prepare('UPDATE articles SET views_count = views_count + 1 WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    /**
     * @return list<Article>
     */
    public function findRelated(int $articleId, int $limit): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT DISTINCT a.*
            FROM articles a
            INNER JOIN article_category ac ON ac.article_id = a.id
            WHERE ac.category_id IN (
                SELECT category_id FROM article_category WHERE article_id = :category_article_id
            )
            AND a.id != :excluded_article_id
            ORDER BY a.published_at DESC
            LIMIT :limit'
        );
        $stmt->bindValue('category_article_id', $articleId, PDO::PARAM_INT);
        $stmt->bindValue('excluded_article_id', $articleId, PDO::PARAM_INT);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $this->mapRows($stmt->fetchAll());
    }

    /**
     * @param list<array<string, mixed>> $rows
     * @return list<Article>
     */
    private function mapRows(array $rows): array
    {
        return array_map(static fn (array $row): Article => Article::fromRow($row), $rows);
    }
}

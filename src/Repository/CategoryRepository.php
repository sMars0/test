<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Category;
use PDO;

final class CategoryRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * @return list<Category>
     */
    public function findWithArticles(): array
    {
        $sql = 'SELECT DISTINCT c.*
            FROM categories c
            INNER JOIN article_category ac ON ac.category_id = c.id
            INNER JOIN articles a ON a.id = ac.article_id
            ORDER BY c.title';

        $rows = $this->pdo->query($sql)->fetchAll();

        return array_map(static fn (array $row): Category => Category::fromRow($row), $rows);
    }

    public function findById(int $id): ?Category
    {
        $stmt = $this->pdo->prepare('SELECT * FROM categories WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? Category::fromRow($row) : null;
    }

    /**
     * @return list<Category>
     */
    public function findByArticleId(int $articleId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT c.*
            FROM categories c
            INNER JOIN article_category ac ON ac.category_id = c.id
            WHERE ac.article_id = :article_id
            ORDER BY c.title'
        );
        $stmt->execute(['article_id' => $articleId]);

        return array_map(static fn (array $row): Category => Category::fromRow($row), $stmt->fetchAll());
    }
}

<?php

declare(strict_types=1);

namespace App\Entity;

final class Article
{
    public function __construct(
        public readonly int $id,
        public readonly string $image,
        public readonly string $title,
        public readonly string $description,
        public readonly string $content,
        public readonly int $viewsCount,
        public readonly string $publishedAt,
        public readonly string $createdAt
    ) {
    }

    /**
     * @param array{id:int|string,image:string,title:string,description:string,content:string,views_count:int|string,published_at:string,created_at:string} $row
     */
    public static function fromRow(array $row): self
    {
        return new self(
            (int) $row['id'],
            $row['image'],
            $row['title'],
            $row['description'],
            $row['content'],
            (int) $row['views_count'],
            $row['published_at'],
            $row['created_at']
        );
    }
}

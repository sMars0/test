<?php

declare(strict_types=1);

namespace App\Entity;

final class Category
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $description,
        public readonly string $createdAt
    ) {
    }

    /**
     * @param array{id:int|string,title:string,description:string,created_at:string} $row
     */
    public static function fromRow(array $row): self
    {
        return new self((int) $row['id'], $row['title'], $row['description'], $row['created_at']);
    }
}

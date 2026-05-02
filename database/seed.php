<?php

declare(strict_types=1);

use App\Core\Database;

require dirname(__DIR__) . '/vendor/autoload.php';

$config = require dirname(__DIR__) . '/config/config.php';
$pdo = Database::connect($config['db']);

$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$pdo->exec('TRUNCATE TABLE article_category');
$pdo->exec('TRUNCATE TABLE articles');
$pdo->exec('TRUNCATE TABLE categories');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

$categoryStmt = $pdo->prepare('INSERT INTO categories (title, description) VALUES (:title, :description)');
$categories = [
    ['PHP', 'Articles about modern PHP development.'],
    ['MySQL', 'Database design and SQL examples.'],
    ['Architecture', 'Simple architecture decisions for small projects.'],
    ['Tools', 'Developer tools and workflow notes.'],
];

$categoryIds = [];
foreach ($categories as $category) {
    $categoryStmt->execute([
        'title' => $category[0],
        'description' => $category[1],
    ]);
    $categoryIds[$category[0]] = (int) $pdo->lastInsertId();
}

$articleStmt = $pdo->prepare(
    'INSERT INTO articles (image, title, description, content, views_count, published_at)
    VALUES (:image, :title, :description, :content, :views_count, :published_at)'
);
$linkStmt = $pdo->prepare('INSERT INTO article_category (article_id, category_id) VALUES (:article_id, :category_id)');

$articles = [
    ['PHP Basics', ['PHP'], 14],
    ['Working with PDO', ['PHP', 'MySQL'], 27],
    ['Prepared Statements', ['PHP', 'MySQL'], 19],
    ['Simple Routing', ['PHP', 'Architecture'], 34],
    ['Manual Dependency Injection', ['PHP', 'Architecture'], 42],
    ['Pagination with SQL', ['MySQL'], 31],
    ['Sorting Articles', ['MySQL', 'Architecture'], 25],
    ['Indexes for Blog Pages', ['MySQL'], 55],
    ['Repository Layer', ['Architecture'], 21],
    ['Service Layer', ['Architecture'], 37],
    ['Composer Autoloading', ['Tools', 'PHP'], 18],
    ['Docker for Local PHP', ['Tools'], 46],
    ['Smarty Templates', ['Tools', 'PHP'], 29],
    ['Clean Seeder Scripts', ['Tools', 'MySQL'], 11],
    ['Small MVC Structure', ['Architecture', 'PHP'], 63],
];

foreach ($articles as $index => [$title, $articleCategories, $views]) {
    $publishedAt = (new DateTimeImmutable('2026-04-01'))->modify('+' . $index . ' days')->format('Y-m-d H:i:s');

    $articleStmt->execute([
        'image' => 'https://picsum.photos/seed/blog-' . ($index + 1) . '/900/500',
        'title' => $title,
        'description' => 'Short introduction for ' . strtolower($title) . '.',
        'content' => "This is a seeded article for {$title}.\n\nIt contains enough text to test the article page layout and related articles block.",
        'views_count' => $views,
        'published_at' => $publishedAt,
    ]);

    $articleId = (int) $pdo->lastInsertId();
    foreach ($articleCategories as $categoryTitle) {
        $linkStmt->execute([
            'article_id' => $articleId,
            'category_id' => $categoryIds[$categoryTitle],
        ]);
    }
}

echo "Database seeded successfully.\n";

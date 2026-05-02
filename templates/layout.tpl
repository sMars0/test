<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$title|default:'Blog'}</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <a class="logo" href="/">Simple Blog</a>
    </header>

    <main class="container">
        {block name=content}{/block}
    </main>
</body>
</html>

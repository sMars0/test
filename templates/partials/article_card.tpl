<article class="article-card">
    <a href="/article/{$article->id}">
        <img src="{$article->image}" alt="{$article->title}">
    </a>
    <div class="article-card-body">
        <h3><a href="/article/{$article->id}">{$article->title}</a></h3>
        <p>{$article->description}</p>
        <div class="meta">
            {$article->publishedAt|date_format:'%Y-%m-%d'} |
            {$article->viewsCount} views
        </div>
    </div>
</article>

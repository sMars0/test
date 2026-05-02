{extends file='layout.tpl'}

{block name=content}
    <p><a href="/">Back to home</a></p>

    <article class="article">
        <img class="article-image" src="{$article->image}" alt="{$article->title}">
        <h1>{$article->title}</h1>
        <p class="lead">{$article->description}</p>

        <div class="meta">
            Published: {$article->publishedAt|date_format:'%Y-%m-%d'} |
            Views: {$article->viewsCount}
        </div>

        <div class="categories">
            {foreach $categories as $category}
                <a href="/category/{$category->id}">{$category->title}</a>
            {/foreach}
        </div>

        <div class="content">
            {$article->content|escape|nl2br nofilter}
        </div>
    </article>

    <section class="related">
        <h2>Related articles</h2>
        <div class="article-grid">
            {foreach $relatedArticles as $article}
                {include file='partials/article_card.tpl' article=$article}
            {foreachelse}
                <p>No related articles.</p>
            {/foreach}
        </div>
    </section>
{/block}

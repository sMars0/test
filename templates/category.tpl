{extends file='layout.tpl'}

{block name=content}
    <p><a href="/">Back to home</a></p>

    <h1>{$category->title}</h1>
    <p class="lead">{$category->description}</p>

    <div class="sort-links">
        Sort:
        <a class="{if $sort === 'date'}active{/if}" href="/category/{$category->id}?sort=date">Publication date</a>
        <a class="{if $sort === 'views'}active{/if}" href="/category/{$category->id}?sort=views">Views count</a>
    </div>

    <div class="article-list">
        {foreach $articles as $article}
            {include file='partials/article_card.tpl' article=$article}
        {foreachelse}
            <p>No articles in this category.</p>
        {/foreach}
    </div>

    {include file='partials/pagination.tpl' pagination=$pagination}
{/block}

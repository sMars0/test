{extends file='layout.tpl'}

{block name=content}
    <h1>Blog</h1>

    {foreach $categories as $item}
        <section class="category-section">
            <div class="section-header">
                <h2>{$item.category->title}</h2>
                <a href="/category/{$item.category->id}">All articles</a>
            </div>

            <div class="article-grid">
                {foreach $item.articles as $article}
                    {include file='partials/article_card.tpl' article=$article}
                {/foreach}
            </div>
        </section>
    {foreachelse}
        <p>No articles yet.</p>
    {/foreach}
{/block}

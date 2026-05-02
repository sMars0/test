{if $pagination.total > 1}
    <nav class="pagination">
        {foreach $pagination.pages as $page}
            {if $page.number === $pagination.current}
                <span>{$page.number}</span>
            {else}
                <a href="{$page.url}">{$page.number}</a>
            {/if}
        {/foreach}
    </nav>
{/if}

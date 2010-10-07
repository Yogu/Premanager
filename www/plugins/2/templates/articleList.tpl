{extends "../../Premanager/templates/main.tpl"}

{block "toolbar"}
	{if $Me_right_Blog_createArticles}
		<ul class="toolbar">
			<li><a href="{$Client_internalRequestURL}?add" class="tool-add-article" title="{string Blog addArticleDescription}">{string Blog addArticle}</a></li>
		</ul>	
	{/if}
{/block}

{block "content"}
	{if $Blog_Articles_list}
		<ul class="list">
			{$Blog_Articles_list}					
		</ul>
	{else}
		<p>{string Blog articleListEmptyMessage}</p>
	{/if}
{/block}

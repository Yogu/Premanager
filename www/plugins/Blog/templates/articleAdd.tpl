{extends "articleForm.tpl"}

{block "toolbar"}
	<ul class="toolbar">
		<li><a href="{$Client_internalRequestURL}?add" class="tool-add-article active" title="{string Blog addArticleDescription}">{string Blog addArticle}</a></li>
	</ul>	
{/block}
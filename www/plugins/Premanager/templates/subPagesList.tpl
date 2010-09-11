{extends "../../Premanager/templates/main.tpl"}

{block "content"}
	{if $UserNode_list}
		<ul class="list">
			{$UserNode_list}
		</ul>
	{else}
		<p>{string Premanager defaultPage}</p>
	{/if}
{/block}
																				
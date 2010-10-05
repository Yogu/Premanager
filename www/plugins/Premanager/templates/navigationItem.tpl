<li{if $node[0]->equals($activeNode)} class="active"{/if}>
	<a href="{html $node[0]->url}">{html $node[0]->title}</a>
	{if count($node[1])}
		<ul>
			{foreach $node[1] child}
				{include file='navigationItem.tpl' node=$child activeNode=$activeNode}
			{/foreach}
		</ul>
	{/if}
</li>

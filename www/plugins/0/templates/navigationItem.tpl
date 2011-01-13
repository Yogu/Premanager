<li{if $node[0]->equals($activeNode) || ($activeNode && !count($node[1]) && $activeNode->getParent() && $node[0]->equals($activeNode->getParent()))} class="active"{/if}>
	<a href="./{html $node[0]->getURL()}">{html $node[0]->getTitle()}</a>
	{if count($node[1])}
		<ul>
			{foreach $node[1] child}
				{include file='navigationItem.tpl' node=$child activeNode=$activeNode}
			{/foreach}
		</ul>
	{/if}
</li>

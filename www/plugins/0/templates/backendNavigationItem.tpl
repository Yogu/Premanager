<node{if $node[0]->equals($activeNode)} active="active"{/if}>
	<url>{html $node[0]->getURL()}</url>
	<title>{html $node[0]->getTitle()}</title>
	{foreach $node[1] child}
		{include file='backendNavigationItem.tpl' node=$child activeNode=$activeNode}
	{/foreach}
</node>

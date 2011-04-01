{if count($children)}
	<ul class="list">
		{foreach $children node}
			<li><a href="./{$node->getURL()}">{html $node->getTitle()}</a></li>
		{/foreach}
	</ul>
{else}
	<p>{string Premanager.Widgets subpagesWidgetNoSubpagesMessage}</p>
{/if}
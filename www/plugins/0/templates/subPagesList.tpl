{if count($list)}
	<ul class="list">
		{foreach $list node}
			<li><a href="./{html $node->getURL()}">{html $node->getTitle()}</a></li>
		{/foreach}
	</ul>
{else}
	<p>{string Premanager defaultPage}</p>
{/if}

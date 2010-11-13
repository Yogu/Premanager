{if count($list)}
	<ul class="list">
		{foreach $list node}
			<li><a href="./{html $node->url}">{html $node->title}</a></li>
		{/foreach}
	</ul>
{else}
	<p>{string Premanager defaultPage}</p>
{/if}

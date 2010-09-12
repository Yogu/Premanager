{if count($list)}
	<ul class="list">
		{foreach $list as $node}
			<li><a href="{html $node->url}">{html $node->title}</a></li>
	</ul>
{else}
	<p>{string Premanager defaultPage}</p>
{/if}
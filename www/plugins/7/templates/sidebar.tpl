{if count($widgets)}
	<aside id="sidebar">
		{foreach $widgets widget}
			{$widget->getHTML()}
		{/foreach}
	</aside>
{/if}
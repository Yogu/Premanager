{if $sessions}
	<ul class="list">
		{foreach $sessions session}
			<li><a href="./{treeURL Premanager users}/{url $session->getUser()->getName()}}"><span class="user user-name" style="color: #{$session->getUser()->getColor()};">{html $session->getUser()->getName()}</span></a></li>
		{/foreach}
	</ul>
{else}
	<span>{string Premanager.Widgets noUserOnline}</span>
{/if}
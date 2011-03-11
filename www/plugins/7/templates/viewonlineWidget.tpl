{if $sessions}
	<ul class="list">
		{foreach $sessions session}
			<li><a class="user" style="color: #{$session->getUser()->getColor()};" href="./{treeURL Premanager users}/{url $session->getUser()->getName()}}">{html $session->getUser()->getName()}</a></li>
		{/foreach}
	</ul>
{else}
	<span>{string Premanager.Widgets noUserOnline}</span>
{/if}
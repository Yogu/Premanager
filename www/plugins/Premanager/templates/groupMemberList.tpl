<ul class="list">
	{foreach $members user}	
		<li>
			<a href="./{html treeURL(Premanager users)}/{url $user->getName()}">
				<span class="avatar"><img alt="{string Premanager avatarOf array(userName=$user->getName())}" src="./{html treeURL(Premanager users)}/{html $user->getName()}/avatar" /></span>
				<span class="user-name user" style="color: #{$user->getColor()};">{html $user->getName()}</span>
				<span class="user-title">{html $user->getTitle()}</span>
			</a>
		</li>
	{/foreach}
</ul>
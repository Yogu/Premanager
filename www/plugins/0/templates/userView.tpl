<div class="info-list">
	<dl>
		<dt>{string Premanager label array(label=string(Premanager userName))}</dt>
		<dd>{html $user->getName()}</dd>
	</dl>

	<dl>
		<dt>{string Premanager label array(label=string(Premanager userTitle))}</dt>
		<dd><span style="color: #{$user->getColor()}">{html $user->getTitle()}</span></dd>
	</dl>   

	{if $user->hasAvatar()}
		<dl>
			<dt>{string Premanager label array(label=string(Premanager avatar))}</dt>
			<dd><img alt="{string Premanager avatarOf array(userName=$user->getName())}" src="./{$node->getURL()}/avatar" /></dd>
		</dl>   
	{/if}

	<dl>
		<dt>{string Premanager label array(label=string(Premanager userRegistrationTime))}</dt>
		<dd>{$user->getRegistrationTime()->format()}</dd>
	</dl>
	
	{if $user->getLastVisibleLoginTime()}
		<dl>
			<dt>{string Premanager label array(label=string(Premanager userLastLoginTime))}</dt>
			<dd>{$user->getLastVisibleLoginTime()->format()}</dd>
		</dl>
	{/if}
</div>

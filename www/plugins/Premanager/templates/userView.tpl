<div class="info-list">
	<dl>
		<dt>{string Premanager label array(label=string(Premanager userName))}</dt>
		<dd>{html $user->name}</dd>
	</dl>

	<dl>
		<dt>{string Premanager label array(label=string(Premanager userTitle))}</dt>
		<dd><span style="color: #{$user->color}">{html $user->title}</span></dd>
	</dl>   

	{if $user->hasAvatar}
		<dl>
			<dt>{string Premanager label array(label=string(Premanager avatar))}</dt>
			<dd><img alt="{string Premanager avatarOf array(userName=$user->name)}" src="./{$node->url}/avatar" /></dd>
		</dl>   
	{/if}

	<dl>
		<dt>{string Premanager label array(label=string(Premanager userRegistrationTime))}</dt>
		<dd>{$user->registrationTime->format()}</dd>
	</dl>
	
	{if $user->lastVisibleLoginTime}
		<dl>
			<dt>{string Premanager label array(label=string(Premanager userLastLoginTime))}</dt>
			<dd>{$user->lastVisibleLoginTime->format()}</dd>
		</dl>
	{/if}
</div>

{foreach $users user}
	<tr>
		<td>
			<a href="./{$node->url}/{url $user->name}">
				<span class="avatar"><img alt="{string Premanager avatarOf array(userName=$user->name)}" src="./{$node->url}{html $user->name}/avatar" /></span>
				<span class="user-name user" style="color: #{$user->color};">{html $user->name}</span>
				<span class="user-title">{html $user->title}</span>
			</a>
		</td>
		<td>{$user->registrationTime->format()}</td>
		<td>{if $user->lastVisibleLoginTime}{$user->lastVisibleLoginTime->format()}{else}{string Premanager literalNone}{/if}</td>
	</tr>
{/foreach}
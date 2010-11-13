{foreach $users user}
	<tr>
		<td>
			<a href="./{$node->geturl()}/{url $user->getName()}">
				<span class="avatar"><img alt="{string Premanager avatarOf array(userName=$user->getName())}" src="./{$node->getURL()}{html $user->getName()}/avatar" /></span>
				<span class="user-name user" style="color: #{$user->getColor()};">{html $user->getName()}</span>
				<span class="user-title">{html $user->gettitle()}</span>
			</a>
		</td>
		<td>{$user->getRegistrationTime()->format()}</td>
		<td>{if $user->getLastVisibleLoginTime()}{$user->getLastVisibleLoginTime()->format()}{else}{string Premanager literalNone}{/if}</td>
	</tr>
{/foreach}

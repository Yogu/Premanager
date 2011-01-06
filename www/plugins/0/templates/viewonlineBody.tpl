{foreach $sessions session}
	<tr>
		<td>
			<a href="./{treeURL Premanager users}/{url $session->getUser()->getName()}">
				<span class="avatar">
					<img alt="{string Premanager avatarOf array(userName=$session->getUser()->getName())}" src="./{treeURL Premanager users}/{url $session->getUser()->getName()}/avatar" />
				</span>
				<span class="user-name user" style="color: #{$session->getUser()->getColor()};">{html $session->getUser()->getName()}</span>
				<span class="user-title">{html $session->getUser()->getTitle()}</span>
			</a>
		</td>
		<td><span class="short-time" title="{$session->getLastRequestTime()->format()}">{$session->getLastRequestTime()->format('long-relative')}</span></td>
		<td>
			<ul class="location">
				<li><a href="./">{html $organization->getTitle()}</a></li>
				{if $session->getProject()->getID()}
					<li><a href="./{url $session->getProject()->getName()}">{html $session->getProject()->getTitle()}</a></li>
				{/if}
			</ul>
		</td>
	</tr>
{/foreach}

{foreach $groups group}
	<tr>
		<td><a href="./{$node->getURL()}/{url $group->getName()}" style="color: #{$group->getColor()};">{html $group->getName()}</a></td>
		<td>{$group->getMemberCount()}</td>
	</tr>
{/foreach}

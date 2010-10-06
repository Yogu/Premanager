{foreach $groups group}
	<tr>
		<td><a href="./{$node->url}/{url $group->name}" style="color: #{$group->color};">{html $group->name}</a></td>
		<td>{$group->getMemberCount()}</td>
	</tr>
{/foreach}

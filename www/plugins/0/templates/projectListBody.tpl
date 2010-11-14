{foreach $list project}
	<tr>
		<td><a href="./{$node->getURL()}/{if $project->getID()}{url $project->getName()}{else}-{/if}">{html $project->getTitle()}</a></td>
		<td>{$project->getDescription()}</td>
	</tr>
{/foreach}

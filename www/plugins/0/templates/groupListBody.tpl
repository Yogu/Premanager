{foreach $groups group}
	{if !$isProjectView}
		{if $group->getProject() != $lastProject}
			<tr class="group-header">
				<td colspan="2">
					<a href="./{$node->getURL()}/{if $group->getProject()->getID()}{$group->getProject()->getName()}{else}-{/if}">
						{html $group->getProject()->getTitle()}
					</a>
				</td>
			</tr>
		{/if}
		{$lastProject = $group->getProject()}
	{/if}
	<tr>
		<td><a href="./{$node->getURL()}/{if !$isProjectView}{if $group->getProject()->getID()}{url $project->getName()}{else}-{/if}/{/if}{url $group->getName()}" style="color: #{$group->getColor()};">{html $group->getName()}</a></td>
		<td>{$group->getMemberCount()}</td>
	</tr>
{/foreach}

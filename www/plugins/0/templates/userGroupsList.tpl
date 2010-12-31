<ul class="list">
	{foreach $groups group}
		{if $group->getProject() != $lastProject}
			<li class="group-header">
				<a href="./{treeURL Premanager projects}/{if $group->getProject()->getID()}{$group->getProject()->getName()}{else}-{/if}">
					{html $group->getProject()->getTitle()}
				</a>
			</li>
		{/if}
		{$lastProject = $group->getProject()}
		<li>
			<ul class="toolbar">
				{$var = $node->getURL()}
				{toolBarItem
					title=string(Premanager userLeaveGroup)
					description=string(Premanager userLeaveGroupDescription)
					url=concat($var, '/leave-group')
					method='POST'
					postName=concat('leave-group-', $group->getID())
					iconURL='Premanager/images/tools/leave-group.png'
				}
			</ul>
			<a href="./{treeURL Premanager groups}/{if $group->getProject()->getID()}{url $group->getProject()->getName()}{else}-{/if}/{url $group->getName()}" style="color: #{$group->getColor()};">{html $group->getName()}</a></td>
		</li>
	{/foreach}
</ul>

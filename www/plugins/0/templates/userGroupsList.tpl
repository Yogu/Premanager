<ul class="list">
	{foreach $groups group}
		{if $group->getProject() != $lastProject}
			<li class="group-header">
				<a href="./{html treeURL(Premanager projects)}/{if $group->getProject()->getID()}{$group->getProject()->getName()}{else}-{/if}">
					{html $group->getProject()->getTitle()}
				</a>
			</li>
		{/if}
		{$lastProject = $group->getProject()}
		<li>
			<?php 
				$right = Premanager\Models\Right::getByName('Premanager', 'manageGroupMemberships');
				if (Premanager\Execution\Rights::hasRight($right, $this->scope['group']->getProject()))
					$this->scope['canLeave'] = true;
				else if ($this->scope['group']->getProject()->getID()) {
					$right = Premanager\Models\Right::getByName('Premanager', 'manageGroupMembershipsOfProjectMembers');
					$this->scope['canLeave'] = Premanager\Execution\Rights::hasRight($right, $this->scope['group']->getProject());
				}
			?>
			{if $canLeave}
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
			{/if}
			<a href="./{html treeURL(Premanager groups)}/{if $group->getProject()->getID()}{url $group->getProject()->getName()}{else}-{/if}/{url $group->getName()}" style="color: #{$group->getColor()};">{html $group->getName()}</a></td>
		</li>
	{/foreach}
</ul>

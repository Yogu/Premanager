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
			<a href="./{treeURL Premanager groups}/{if $group->getProject()->getID()}{url $group->getProject()->getName()}{else}-{/if}/{url $group->getName()}" style="color: #{$group->getColor()};">{html $group->getName()}</a></td>
		</li>
	{/foreach}
</ul>

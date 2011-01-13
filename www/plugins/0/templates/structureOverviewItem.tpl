<li>
	<div>
		<a href="./{$node->getURL()}">{html $node->getTitle()}</a>
		
		<ul class="toolbar">
			{$url = $node->getURL()}
			{toolbarItem
				title=string(Premanager editNode)
				description=string(Premanager editNodeDescription)
				url=concat($url, '/+edit')
				iconURL='Premanager/images/tools/edit.png'
			}
			
			{$realURL = $node->getRealURL()}
			{toolbarItem
				title=string(Premanager gotoNode)
				description=string(Premanager gotoNodeDescription)
				url=$realURL
				iconURL='Premanager/images/tools/goto-page.png'
			}
			
			{if $node->getStructureNode()->getTreeClass()}
				{$description = addTreeNodeChildError}
				{$enabled = false}
			{else}
				{$description = addNodeDescription}
				{$enabled = true}
			{/if}
			{toolbarItem
				title=string(Premanager addNode)
				description=string(Premanager $description)
				url=concat($url, '/+add')
				iconURL='Premanager/images/tools/add.png'
				enabled=$enabled
			}
			
			{if $node->getStructureNode()->getParent()}
				{toolbarItem
					title=string(Premanager moveNode)
					description=string(Premanager moveNodeDescription)
					url=concat($url, '/+move')
					iconURL='Premanager/images/tools/move.png'
				}
				
				{toolbarItem
					title=string(Premanager nodePermissions)
					description=string(Premanager nodePermissionsDescription)
					url=concat($url, '/+rights')
					iconURL='Premanager/images/tools/rights.png'
				}
				
				{if $node->getStructureNode()->canDelete()}
					{$description = deleteNodeDescription}
					{$enabled = true}
				{else}
					{$description = deleteTreeNodeError}
					{$enabled = false}
				{/if}
				{toolbarItem
					title=string(Premanager deleteNode)
					description=string(Premanager $description)
					url=concat($url, '/+delete')
					iconURL='Premanager/images/tools/delete.png'
					enabled=$enabled
				}
			{/if}
		</ul>
	</div>	
	{$children = $node->getChildren()}
	{if count($children)}
		<ul>
			{foreach $children child}
				{include file='structureOverviewItem.tpl' node=$child}
			{/foreach}
		</ul>
	{/if}
</li>

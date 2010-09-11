<li>
	<div>
		<a href="./{if $Premanager_Node_parentID}{$Premanager_Node_url}{/if}" title="{string Premanager gotoNodeDescription}" class="name">
			{if $Premanager_Node_parentID}{html $Premanager_Node_name}{else}{string Premanager structureRootNodeName}{/if}
		</a>
		<a href="./{treeURL Premanager StructureNode}/_{if $Premanager_Node_parentID}/{$Premanager_Node_url}{/if}" title="{string Premanager editNodeDescription}" class="title">{html $Premanager_Node_title}</a>
		
		<ul class="toolbar">                                                   
			<li><a href="./{treeURL Premanager StructureNode}/_{if $Premanager_Node_parentID}/{$Premanager_Node_url}{/if}" class="tool-edit-node" title="{string Premanager editNodeDescription}">{string Premanager editNode}</a></li>    
			<li><a href="./{if $Premanager_Node_parentID}{$Premanager_Node_url}{/if}" class="tool-goto-node" title="{string Premanager gotoNodeDescription}">{string Premanager gotoNode}</a></li>   
			<li><a href="./{treeURL Premanager StructureNode}/_{if $Premanager_Node_parentID}/{$Premanager_Node_url}{/if}?add" class="tool-add-node" title="{string Premanager addNodeDescription}">{string Premanager addNode}</a></li>           
			{if $Premanager_Node_parentID}                       
				<li><a href="./{treeURL Premanager StructureNode}/_/{$Premanager_Node_url}?move" class="tool-move-node" title="{string Premanager moveNodeDescription}">{string Premanager moveNode}</a></li>           
				<li><a href="./{treeURL Premanager StructureNode}/_/{$Premanager_Node_url}?permissions" class="tool-node-permissions" title="{string Premanager nodePermissionsDescription}">{string Premanager nodePermissions}</a></li>   
				{if !$Premanager_Node_treeID}
					<li><a href="./{treeURL Premanager StructureNode}/_/{$Premanager_Node_url}?delete" class="tool-delete-node" title="{string Premanager deleteNodeDescription}">{string Premanager deleteNode}</a></li>
				{else}
					<li><span class="tool-delete-node disabled" title="{string Premanager deleteTreeNodeError}">{string Premanager deleteNode}</span></li>
				{/if}		 
			{/if}
		</ul>
	</div>
	
	{if $Premanager_Node_innerList}    
		<ul>
			{$Premanager_Node_innerList}
		</ul>	
	{/if}
</li>

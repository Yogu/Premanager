<li{if $Premanager_OwnerNode_formParentID == $Premanager_Node_id} class="selected"{elseif $Premanager_OwnerNode_id == $Premanager_Node_id} class="active"{/if}>
	<div>
		<a href="./{if $Premanager_Node_parentID}{$Premanager_Node_url}{/if}" title="{string Premanager gotoNodeDescription}" class="name">
			{if $Premanager_Node_parentID}{html $Premanager_Node_name}{else}{string Premanager structureRootNodeName}{/if}
		</a>
		<a href="./{treeURL Premanager StructureNode}/_{if $Premanager_Node_parentID}/{$Premanager_Node_url}{/if}" title="{string Premanager editNodeDescription}" class="title">{html $Premanager_Node_title}</a>
		
		<ul class="toolbar">        
			{if $Premanager_Node_id == $Premanager_OwnerNode_parentID}
				<li><span class="tool-move-node disabled" title="{string Premanager moveTargetNotChangedError}">{string Premanager insertNodeHere}</a></li>
			{elseif $Premanager_Node_id == $Premanager_OwnerNode_id}
				<li><span class="tool-move-node disabled" title="{string Premanager moveTargetIsChildError}">{string Premanager insertNodeHere}</a></li>
			{elseif $Premanager_Node_treeID}
				<li><span class="tool-move-node disabled" title="{string Premanager moveIntoTreeNodeError}">{string Premanager insertNodeHere}</a></li>
			{else}
				<li><a href="./{Client internalRequestURL}?move&amp;target={url '/'}{if $Premanager_Node_parentID}{url $Premanager_Node_url}{/if}" class="tool-move-node" title="{string Premanager insertNodeHereDescription}">{string Premanager insertNodeHere}</a></li>
			{/if}
			<li><a href="./{treeURL Premanager StructureNode}/_{if $Premanager_Node_parentID}/{$Premanager_Node_url}{/if}" class="tool-edit-node" title="{string Premanager editNodeDescription}">{string Premanager editNode}</a></li>	 
		</ul>
	</div>
	
	{if $Premanager_Node_innerList}
		<ul>
			{$Premanager_Node_innerList}      
		</ul>	
	{/if}
</li>

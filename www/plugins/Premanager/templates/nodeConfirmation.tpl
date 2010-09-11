{extends "../../Premanager/templates/confirmation.tpl"}     

{block "toolbar"}
	<ul class="toolbar">
		<li><a href="./{$Client_internalRequestURL}" class="tool-edit-node" title="{string Premanager editNodeDescription}">{string Premanager editNode}</a></li>           
		<li><a href="./{$Client_internalRequestURL}?add" class="tool-add-node" title="{string Premanager addNodeDescription}">{string Premanager addNode}</a></li>        
		{if $Premanager_Node_parentID}
			<li><a href="./{$Client_internalRequestURL}?move" class="tool-move-node" title="{string Premanager moveNodeDescription}">{string Premanager moveNode}</a></li>
			<li><a href="./{$Client_internalRequestURL}?permissions" class="tool-node-permissions" title="{string Premanager nodePermissionsDescription}">{string Premanager nodePermissions}</a></li>
			{if !$Premanager_Node_treeID}
				<li><a href="./{$Client_internalRequestURL}?delete" class="tool-delete-node{if $Premanager_Node_mode == 'delete'} active{/if}" title="{string Premanager deleteNodeDescription}">{string Premanager deleteNode}</a></li>
			{else}
				<li><span class="tool-delete-node disabled" title="{string Premanager deleteTreeNodeError}">{string Premanager deleteNode}</span></li>
			{/if}
		{/if}              
		<li class="new-group"><a href="./{$Premanager_Node_url}" class="tool-goto-node" title="{string Premanager gotoNodeDescription}">{string Premanager gotoNode}</a></li>
	</ul>
{/block}

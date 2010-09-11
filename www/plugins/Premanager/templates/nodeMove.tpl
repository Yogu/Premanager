{extends "../../Premanager/templates/main.tpl"}       

{block "head"}
	<link rel="stylesheet" type="text/css" href="{$Config_staticURLPrefix}Premanager/styles/common.css" />
{/block}

{block "toolbar"}
	<ul class="toolbar">                               
		<li><a href="./{$Client_internalRequestURL}" class="tool-edit-node" title="{string Premanager editNodeDescription}">{string Premanager editNode}</a></li>               
		<li><a href="./{$Client_internalRequestURL}?add" class="tool-add-node" title="{string Premanager addNodeDescription}">{string Premanager addNode}</a></li>
		<li><a href="./{$Client_internalRequestURL}?move" class="tool-move-node active" title="{string Premanager moveNodeDescription}">{string Premanager moveNode}</a></li>
		<li><a href="./{$Client_internalRequestURL}?permissions" class="tool-node-permissions" title="{string Premanager nodePermissionsDescription}">{string Premanager nodePermissions}</a></li>
		{if !$Premanager_Node_treeID}
			<li><a href="./{$Client_internalRequestURL}?delete" class="tool-delete-node" title="{string Premanager deleteNodeDescription}">{string Premanager deleteNode}</a></li>
		{else}
			<li><span class="tool-delete-node disabled" title="{string Premanager deleteTreeNodeError}">{string Premanager deleteNode}</span></li>
		{/if}   
		<li class="new-group"><a href="./{$Premanager_Node_url}" class="tool-goto-node" title="{string Premanager gotoNodeDescription}">{string Premanager gotoNode}</a></li>
	</ul>
{/block}

{block "content"}
	{if $Premanager_Node_list}
		<p>{string Premanager moveNodeMessage array(title=$Premanager_Node_title)}</p>
	{else}
		<p>{string Premanager structureEmpty}</p>
	{/if}   
	{if $Premanager_Node_inputErrors}
		<ul class="input-errors">
			{$Premanager_Node_inputErrors}	
		</ul>
	{/if}
{/block}

{block "after"} 
	{if $Premanager_Node_list}
		<dl class="block">
			<dt>{string Premanager structureTitle}</dt>
			<dd>
				<ul class="structure-admin">
					{$Premanager_Node_list}
				</ul>
			</dd>
		</dl>
	{/if}	
{/block}

{extends "../../Premanager/templates/main.tpl"}       

{block "toolbar"}
	<ul class="toolbar">                              
		<li><a href="./{$Client_internalRequestURL}" class="tool-edit-node" title="{string Premanager editNodeDescription}">{string Premanager editNode}</a></li>
		<li><a href="./{$Client_internalRequestURL}?add" class="tool-add-node" title="{string Premanager addNodeDescription}">{string Premanager addNode}</a></li>
		<li><a href="./{$Client_internalRequestURL}?move" class="tool-move-node" title="{string Premanager moveNodeDescription}">{string Premanager moveNode}</a></li>           
		<li><a href="./{$Client_internalRequestURL}?permissions" class="tool-node-permissions active" title="{string Premanager nodePermissionsDescription}">{string Premanager nodePermissions}</a></li>
		{if !$Premanager_Node_treeID}
			<li><a href="./{$Client_internalRequestURL}?delete" class="tool-delete-node" title="{string Premanager deleteNodeDescription}">{string Premanager deleteNode}</a></li>
		{else}
			<li><span class="tool-delete-node disabled" title="{string Premanager deleteTreeNodeError}">{string Premanager deleteNode}</span></li>
		{/if}  
		<li class="new-group"><a href="./{$Premanager_Node_url}" class="tool-goto-node" title="{string Premanager gotoNodeDescription}">{string Premanager gotoNode}</a></li>
	</ul>
{/block}

{block "content"}
	{if $Premanager_Node_noAccessRestriction}
		<p>{string Premanager nodePermissionsNoAccessRestrictionMessage array(title=$Premanager_Node_title)}</p>
		<form action="{html $Client_requestURL}" method="post">
			<fieldset class="buttons">
				<input type="submit" name="Premanager_Node_setAccessRestriction" value="{string Premanager nodeAccessRestrictionButton}" />
			</fieldset>		
		</form>
	{else}
		<p>{string Premanager nodePermissionsAccessRestrictionMessage array(title=$Premanager_Node_title)}</p>
		<form action="{html $Client_requestURL}" method="post">
			<fieldset class="buttons">
				<input type="submit" name="Premanager_Node_setNoAccessRestriction" value="{string Premanager nodeNoAccessRestrictionButton}" />
			</fieldset>		
		</form>
	{/if}
	   
	{if $Premanager_Node_inputErrors}
		<ul class="input-errors">
			{$Premanager_Node_inputErrors}	
		</ul>
	{/if}
{/block}

{block "after"}
	{if !$Premanager_Node_noAccessRestriction}
		<dl class="block">
			<dt>{string Premanager nodePermissionsGroupListTitle}</dt>
			<dd>     
				{if $Premanager_Node_groupList}
					<ul class="list">
						{$Premanager_Node_groupList}
					</ul>
				{else}
					<p>{string Premanager nodePermissionsGroupListEmptyMessage}</p>
				{/if}
			</dd>
		</dl>
		
		<dl class="block">
			<dt>{string Premanager nodePermissionsFreeGroupListTitle}</dt>
			<dd>     
				{if $Premanager_Node_freeGroupList}
					<ul class="list">
						{$Premanager_Node_freeGroupList}
					</ul>
				{else}
					<p>{string Premanager nodePermissionsFreeGroupListEmptyMessage}</p>
				{/if}
			</dd>
		</dl>
	{/if}
{/block}

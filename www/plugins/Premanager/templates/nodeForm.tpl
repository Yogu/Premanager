{extends "../../Premanager/templates/main.tpl"}

{block "toolbar"}
	<ul class="toolbar">
		<li><a href="./{$Client_internalRequestURL}" class="tool-edit-node{if $Premanager_Node_mode == 'edit'} active{/if}" title="{string Premanager editNodeDescription}">{string Premanager editNode}</a></li>           
		<li><a href="./{$Client_internalRequestURL}?add" class="tool-add-node{if $Premanager_Node_mode == 'add'} active{/if}" title="{string Premanager addNodeDescription}">{string Premanager addNode}</a></li>        
		{if $Premanager_Node_parentID}
			<li><a href="./{$Client_internalRequestURL}?move" class="tool-move-node" title="{string Premanager moveNodeDescription}">{string Premanager moveNode}</a></li>
			<li><a href="./{$Client_internalRequestURL}?permissions" class="tool-node-permissions" title="{string Premanager nodePermissionsDescription}">{string Premanager nodePermissions}</a></li>
			{if !$Premanager_Node_treeID}
				<li><a href="./{$Client_internalRequestURL}?delete" class="tool-delete-node" title="{string Premanager deleteNodeDescription}">{string Premanager deleteNode}</a></li>
			{else}
				<li><span class="tool-delete-node disabled" title="{string Premanager deleteTreeNodeError}">{string Premanager deleteNode}</span></li>
			{/if}
		{/if}              
		<li class="new-group"><a href="./{$Premanager_Node_url}" class="tool-goto-node" title="{string Premanager gotoNodeDescription}">{string Premanager gotoNode}</a></li>
	</ul>
{/block}

{block "content"}
	<form action="{html $Client_requestURL}" method="post">
		{if $Premanager_Node_inputErrors}
			<ul class="input-errors">
				{$Premanager_Node_inputErrors}
			</ul>
		{/if}

		<fieldset class="inputs">
			{if $Premanager_Node_parentID || $Premanager_Node_mode == 'add'}
				<dl>
					<dt><label for="Premanager_Node_name">{string Premanager label array(label=string(Premanager nodeNameLabel))}</label></dt>
					<dd>
						<input type="text" name="Premanager_Node_name" id="Premanager_Node_name" value="{$Premanager_Node_formName}" />
						<p>{string Premanager nodeNameDescription}</p>
					</dd>
				</dl>
			{/if}
			
			<dl>
				<dt><label for="Premanager_Node_title">{string Premanager label array(label=string(Premanager nodeTitleLabel))}</label></dt>
				<dd>
					<input type="text" name="Premanager_Node_title" id="Premanager_Node_title" value="{$Premanager_Node_formTitle}" />
					<p>{string Premanager nodeTitleDescription}</p>
				</dd>
			</dl>  
		</fieldset>
		
		<fieldset class="buttons">
			<input type="submit" name="Premanager_Node_form" class="main" value="{string Premanager submitButton}" />
		</fieldset>
	</form>
{/block}

{block "after"}
	{if $Premanager_Node_mode == 'edit'}
		<dl class="block">       
			<dt>{string Premanager nodeContentTitle}</dt>
			<dd>
				{if $Premanager_Node_treeID}
				<p>{string Premanager treeNodeDescription array(plugin=$Premanager_Node_treePlugin class=$Premanager_Node_treeClass)}</p>
				{elseif $Premanager_Node_hasPanel}
					<p>{string Premanager panelNodeDescription}</p>
					<p><a href="{$Client_internalRequestURL}?remove-panel">{string Premanager nodeRemovePanel}</a></p>
				{else}
					<p>{string Premanager commonNodeDescription}</p>
					<p><a href="{$Client_internalRequestURL}?create-panel">{string Premanager nodeCreatePanel}</a></p>
				{/if}
			</dd>	
		</dl>	
	{/if}
{/block}

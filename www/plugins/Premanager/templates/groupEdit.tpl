{extends "groupForm.tpl"}

{block "toolbar"}        
	{if $Premanager_Group_meCanEdit}
		<ul class="toolbar">   
			<li><a href="./{treeURL Premanager Groups}/{html $Premanager_Group_name}?edit" class="tool-edit-group active" title="{string Premanager editGroupDescription}">{string Premanager editGroup}</a></li>
			<li><a href="./{treeURL Premanager Groups}/{html $Premanager_Group_name}?delete" class="tool-delete-group" title="{string Premanager deleteGroupDescription}">{string Premanager deleteGroup}</a></li>	
			{if $Me_right_Premanager_manageRights}                 
				<li><a href="./{treeURL Premanager Groups}/{url $Premanager_Group_name}?rights" class="tool-edit-group-rights" title="{string Premanager editGroupRightsDescription}">{string Premanager editGroupRights}</a></li>
			{/if}      
			{if $Me_right_Premanager_lockGroups} 
				{if $Premanager_Group_isLocked}                                
					<li><a href="./{treeURL Premanager Groups}/{url $Premanager_Group_name}?unlock" class="tool-unlock-group" title="{string Premanager unlockGroupDescription}">{string Premanager unlockGroup}</a></li>
				{else}
					<li><a href="./{treeURL Premanager Groups}/{url $Premanager_Group_name}?lock" class="tool-lock-group" title="{string Premanager lockGroupDescription}">{string Premanager lockGroup}</a></li>
				{/if}
			{/if}
		</ul>
	{/if}
{/block}

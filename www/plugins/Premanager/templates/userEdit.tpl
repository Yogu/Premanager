{extends "userForm.tpl"}

{block "toolbar"}        
	{if ($Me_loggedIn && $Premanager_User_id == $Me_id) || $Me_right_Premanager_manageUsers || $Me_right_manageRights}
		<ul class="toolbar">                  
			{if $Me_right_Premanager_manageUsers}
				<li><a href="./{treeURL Premanager Users}/{url $Premanager_User_name}?edit" class="tool-edit-user active" title="{string Premanager editUserDescription}">{string Premanager editUser}</a></li>
				{if $Premanager_User_id}
					<li><a href="./{treeURL Premanager Users}/{url $Premanager_User_name}?delete" class="tool-delete-user" title="{string Premanager deleteUserDescription}">{string Premanager deleteUser}</a></li>
				{else}
					<li><span class="disabled tool-delete-user" title="{string Premanager deleteGuestErrorMessage}">{string Premanager deleteUser}</a></li>
				{/if}    
			{/if}
			{if ($Me_loggedIn && $Premanager_User_id == $Me_id) || $Me_right_Premanager_manageUsers}
				<li><a href="./{treeURL Premanager Users}/{url $Premanager_User_name}?join-group" class="tool-user-join-group" title="{string Premanager userJoinGroupDescription}">{string Premanager userJoinGroup}</a></li>
			{/if}	
			{if $Me_right_Premanager_manageRights}             
				<li><a href="./{treeURL Premanager Users}/{url $Premanager_User_name}?rights" class="tool-view-user-rights" title="{string Premanager viewUserRightsDescription}">{string Premanager viewUserRights}</a></li>
			{/if} 
			{if $Me_right_Premanager_changeForeignAvatars}
				<li><a href="./{treeURL Premanager ChangeAvatar}?user={url $Premanager_User_name}" class="tool-change-avatar" title="{string Premanager changeAvatarDescription}">{string Premanager changeAvatar}</a></li>
			{/if}
		</ul>
	{/if}
{/block}

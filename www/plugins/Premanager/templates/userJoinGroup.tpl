{extends "../../Premanager/templates/main.tpl"}

{block "toolbar"}
	{if ($Me_loggedIn && $Premanager_User_id == $Me_id) || $Me_right_Premanager_manageUsers || $Me_right_manageRights}
		<ul class="toolbar">                   
			{if $Me_right_Premanager_manageUsers}
				<li><a href="./{treeURL Premanager Users}/{url $Premanager_User_name}?edit" class="tool-edit-user" title="{string Premanager editUserDescription}">{string Premanager editUser}</a></li>
				{if $Premanager_User_id}
					<li><a href="./{treeURL Premanager Users}/{url $Premanager_User_name}?delete" class="tool-delete-user" title="{string Premanager deleteUserDescription}">{string Premanager deleteUser}</a></li>
				{else}
					<li><span class="disabled tool-delete-user" title="{string Premanager deleteGuestErrorMessage}">{string Premanager deleteUser}</a></li>
				{/if}    
			{/if}
			{if ($Me_loggedIn && $Premanager_User_id == $Me_id) || $Me_right_Premanager_manageUsers}
				<li><a href="./{treeURL Premanager Users}/{url $Premanager_User_name}?join-group" class="tool-user-join-group active" title="{string Premanager userJoinGroupDescription}">{string Premanager userJoinGroup}</a></li>
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

{block "content"}
	{if $Premanager_User_groupList}
		<p>{string Premanager userJoinGroupMessage array(name=$Premanager_User_name)}</p>
	{elseif $Premanager_User_id == $Me_id}
		<p>{string Premanager userMeJoinGroupEmptyMessage}</p>	
	{else}
		<p>{string Premanager userJoinGroupEmptyMessage array(name=$Premanager_User_name)}</p>
	{/if}   
	{if $Premanager_User_inputErrors}
		<ul class="input-errors">
			{$Premanager_User_inputErrors}	
		</ul>
	{/if}
{/block}

{block "after"}
	{if $Premanager_User_groupList}
		<dl class="block">
			<dt>{string Premanager userGroupList}</dt>
			<dd>
				<ul class="list">
					{$Premanager_User_groupList}
				</ul>
			</dd>
		</dl>
	{/if}
{/block}

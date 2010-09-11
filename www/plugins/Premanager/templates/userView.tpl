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

{block "content"}
	<div class="info-list">
		<dl>
			<dt>{string Premanager label array(label=string(Premanager userName))}</dt>
			<dd>{html $Premanager_User_name}</dd>
		</dl>

		<dl>
			<dt>{string Premanager label array(label=string(Premanager userTitle))}</dt>
			<dd><span style="color: #{$Premanager_User_color}">{html $Premanager_User_title}</span></dd>
		</dl>   

		{if $Premanager_User_hasAvatar}
			<dl>
				<dt>{string Premanager label array(label=string(Premanager avatar))}</dt>
				<dd><img alt="{string Premanager avatarOf array(userName=$Premanager_User_name)}" src="./{treeURL Premanager Users}/{url $Premanager_User_name}?avatar" /></dd>
			</dl>   
		{/if}

		<dl>
			<dt>{string Premanager label array(label=string(Premanager userRegistrationTime))}</dt>
			<dd>{longDateTime $Premanager_User_registrationTime}</dd>
		</dl>
		
		{if $Premanager_User_lastLoginTime}
			<dl>
				<dt>{string Premanager label array(label=string(Premanager userLastLoginTime))}</dt>
				<dd>{longDateTime $Premanager_User_lastLoginTime}</dd>
			</dl>
		{/if}
	</div>
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

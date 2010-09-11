{if $Premanager_User_isLeader != $Premanager_User_oldIsLeader}
	<li class="group-header">
		{if $Premanager_User_isLeader}
			{string Premanager groupLeaderHeader}
		{else}
			{string Premanager groupMemberHeader}
		{/if}
	</li>
{/if}

<li>
	<a href="./{treeURL Premanager Users}/{url $Premanager_User_name}">
		<span class="avatar"><img alt="{string Premanager avatarOf array(userName=$Premanager_User_name)}" src="./{treeURL Premanager Users}/{url $Premanager_User_name}?avatar" /></span>
		<span class="user-name user" style="color: #{$Premanager_User_color};">{html $Premanager_User_name}</span>
		<span class="user-title">{html $Premanager_User_title}</span>
	</a>
</li>

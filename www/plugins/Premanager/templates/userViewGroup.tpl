<li>
	{if $Premanager_Group_meCanEdit && (($Me_loggedIn && $Premanager_User_id == $Me_id) || $Me_right_Premanager_manageUsers)}
		<ul class="toolbar">
			{if $Premanager_Group_isLeader}
				<li><a href="./{treeURL Premanager Users}/{url $Premanager_User_name}?demote&amp;group={url $Premanager_Group_name}" class="tool-demote-user" title="{string Premanager demoteUserDescription}">{string Premanager demoteUser}</a></li>
			{else}
				<li><a href="./{treeURL Premanager Users}/{url $Premanager_User_name}?promote&amp;group={url $Premanager_Group_name}" class="tool-promote-user" title="{string Premanager promoteUserDescription}">{string Premanager promoteUser}</a></li>
			{/if}
			<li><a href="./{treeURL Premanager Users}/{url $Premanager_User_name}?leave-group&amp;group={url $Premanager_Group_name}" class="tool-user-leave-group" title="{string Premanager userLeaveGroupDescription}">{string Premanager userLeaveGroup}</a></li>
		</ul>
	{/if}

	<a href="./{treeURL Premanager Groups}/{url $Premanager_Group_name}">
		<span class="title" style="color: #{$Premanager_Group_color};">{html $Premanager_Group_name}</span>
		{if $Premanager_Group_isLeader}
			<span class="detail">{string Premanager brackets array(content=string(Premanager isGroupLeaderAppendix))}</span>
		{/if}
	</a>
</li>

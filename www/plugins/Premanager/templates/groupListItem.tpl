<li>
	{if $Premanager_Group_meCanEdit}
		<ul class="toolbar">
			<li><a href="./{treeURL Premanager Groups}/{url $Premanager_Group_name}?edit" class="tool-edit-group" title="{string Premanager editGroupDescription}">{string Premanager editGroup}</a></li>
			<li><a href="./{treeURL Premanager Groups}/{url $Premanager_Group_name}?delete" class="tool-delete-group" title="{string Premanager deleteGroupDescription}">{string Premanager deleteGroup}</a></li>
		</ul>
	{/if}
	
	<a href="./{treeURL Premanager Groups}/{url $Premanager_Group_name}">
		<span class="title" style="color: #{$Premanager_Group_color};">{html $Premanager_Group_name}</span>
		{if $Premanager_Group_memberCount}
			<span class="detail">({string Premanager groupMemberCount array(count=$Premanager_Group_memberCount)})</span>
		{/if}
	</a>
</li>

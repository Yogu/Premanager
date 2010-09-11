{extends "userForm.tpl"}

{block "toolbar"}
	<ul class="toolbar">     
		{if $Me_right_Premanager_manageUsers}
			<li><a href="./{treeURL Premanager Users}?add" class="tool-add-user active" title="{string Premanager addUserDescription}">{string Premanager addUser}</a></li>
		{/if}	 
		<li class="new-group"><a href="./{treeURL Premanager Groups}" class="tool-goto-groups" title="{string Premanager gotoGroupsDescription}">{string Premanager gotoGroups}</a></li>	
	</ul>
{/block}

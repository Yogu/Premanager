{extends "groupForm.tpl"}

{block "toolbar"}
	<ul class="toolbar">      
		{if $Me_right_Premanager_createGroups}
			<li><a href="./{treeURL Premanager Groups}?add" class="tool-add-group active" title="{string Premanager addGroupDescription}">{string Premanager addGroup}</a></li>
		{/if}	  	 
		<li class="new-group"><a href="./{treeURL Premanager Users}" class="tool-goto-users" title="{string Premanager gotoUsersDescription}">{string Premanager gotoUsers}</a></li>
	</ul>
{/block}

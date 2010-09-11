{extends "../../Premanager/templates/main.tpl"} 

{block "toolbar"}
	<ul class="toolbar">  
		{if $Me_right_Premanager_createGroups}
			<li><a href="./{treeURL Premanager Groups}?add" class="tool-add-group" title="{string Premanager addGroupDescription}">{string Premanager addGroup}</a></li>
		{/if}	  	 
		<li class="new-group"><a href="./{treeURL Premanager Users}" class="tool-goto-users" title="{string Premanager gotoUsersDescription}">{string Premanager gotoUsers}</a></li>
	</ul>
{/block}

{block "content"}
	{if $Premanager_Groups_list}
		<ul class="list">
			{$Premanager_Groups_list}
		</ul>
	{else}
		<p>{string Premanager groupListEmpty}</p>
	{/if}	
{/block}

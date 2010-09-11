{extends "../../Premanager/templates/main.tpl"} 

{block "toolbar"}
	{if $Me_right_Premanager_manageProjects}
		<ul class="toolbar"> 
			<li><a href="./{treeURL Premanager ProjectsNode}?add" class="tool-add-project" title="{string Premanager addProjectDescription}">{string Premanager addProject}</a></li>
		</ul>
	{/if}
{/block}

{block "content"}
	{if $Premanager_ProjectsNode_list}
		<ul class="list">
			{$Premanager_ProjectsNode_list}
		</ul>
	{else}
		<p>{string Premanager projectListEmptyMessage}</p>
	{/if}	
{/block}

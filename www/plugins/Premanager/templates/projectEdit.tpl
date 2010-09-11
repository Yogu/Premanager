{extends "projectForm.tpl"}

{block "toolbar"}    
	<ul class="toolbar">
		<li><a href="./{treeURL Premanager ProjectsNode}/{html $Premanager_ProjectNode_storedName}?edit" class="tool-edit-project active" title="{string Premanager editProjectDescription}">{string Premanager editProject}</a></li>
		{if $Premanager_Project_id}
			<li><a href="./{treeURL Premanager ProjectsNode}/{html $Premanager_ProjectNode_storedName}?delete" class="tool-delete-project" title="{string Premanager deleteProjectDescription}">{string Premanager deleteProject}</a></li>   
		{else}
			<li><span class="tool-delete-project disabled" title="{string Premanager deleteOrganizationError}">{string Premanager deleteProject}</a></li>
		{/if}
		<li class="new-group"><a href="http://{urlTemplate(null, null, $Premanager_ProjectNode_storedName)}" class="tool-goto-project" title="{string Premanager gotoProjectDescription}">{string Premanager gotoProject}</a></li>	
	</ul>
{/block}

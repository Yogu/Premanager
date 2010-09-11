{extends "../../Premanager/templates/confirmation.tpl"}     

{block "toolbar"}     
	<ul class="toolbar">
		<li><a href="./{treeURL Premanager ProjectsNode}/{html $Premanager_ProjectNode_storedName}?edit" class="tool-edit-project" title="{string Premanager editProjectDescription}">{string Premanager editProject}</a></li>
		<li><a href="./{treeURL Premanager ProjectsNode}/{html $Premanager_ProjectNode_storedName}?delete" class="tool-delete-project active" title="{string Premanager deleteProjectDescription}">{string Premanager deleteProject}</a></li>
		<li class="new-group"><a href="http://{urlTemplate(null, null, $Premanager_ProjectNode_storedName)}" class="tool-goto-project" title="{string Premanager gotoProjectDescription}">{string Premanager gotoProject}</a></li>
	</ul>
{/block}

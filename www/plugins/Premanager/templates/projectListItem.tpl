<li>
	<ul class="toolbar">
		<li><a href="./{treeURL Premanager ProjectsNode}/{url $Premanager_ProjectNode_name}?edit" class="tool-edit-project" title="{string Premanager editProjectDescription}">{string Premanager editProject}</a></li>
		{if $Premanager_ProjectNode_id}
			<li><a href="./{treeURL Premanager ProjectsNode}/{url $Premanager_ProjectNode_name}?delete" class="tool-delete-project" title="{string Premanager deleteProjectDescription}">{string Premanager deleteProject}</a></li>
		{else}
			<li><span class="tool-delete-project disabled" title="{string Premanager deleteOrganizationError}">{string Premanager deleteProject}</a></li>
		{/if}
	</ul>
	
	<a href="./{treeURL Premanager ProjectsNode}/{url $Premanager_ProjectNode_name}">
		<span class="title">{if $Premanager_ProjectNode_id}{html $Premanager_ProjectNode_title}{else}{string(Premanager brackets array(content=html($Premanager_ProjectNode_title)))}{/if}</span>
		{if !$Premanager_ProjectNode_id}<span class="detail">{string Premanager projectIsOrganization}</span>{/if}
	</a>
</li>

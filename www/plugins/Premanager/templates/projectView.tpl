{extends "../../Premanager/templates/main.tpl"}   

{block "toolbar"}       
	<ul class="toolbar">     
		{if $Me_right_Premanager_manageProjects}
			<li><a href="./{treeURL Premanager ProjectsNode}/{html $Premanager_ProjectNode_name}?edit" class="tool-edit-project" title="{string Premanager editProjectDescription}">{string Premanager editProject}</a></li>
			{if $Premanager_ProjectNode_id}
				<li><a href="./{treeURL Premanager ProjectsNode}/{html $Premanager_ProjectNode_name}?delete" class="tool-delete-project" title="{string Premanager deleteProjectDescription}">{string Premanager deleteProject}</a></li>   
			{else}
				<li><span class="tool-delete-project disabled" title="{string Premanager deleteOrganizationError}">{string Premanager deleteProject}</a></li>
			{/if}	
		{/if}    
		<li class="new-group"><a href="http://{urlTemplate(null, null, $Premanager_ProjectNode_name)}" class="tool-goto-project" title="{string Premanager gotoProjectDescription}">{string Premanager gotoProject}</a></li>
	</ul>
{/block}

{block "content"}
	<div class="info-list">
		<dl>
			<dt>{string Premanager label array(label=string(Premanager projectTitle))}</dt>
			<dd><span class="title">{html $Premanager_ProjectNode_title}</span>{if $Premanager_ProjectNode_subTitle}{string Premanager titleDivider}{html $Premanager_ProjectNode_subTitle}{/if}</dd>
		</dl>  
		
		<dl>
			<dt>{string Premanager label array(label=string(Premanager projectAuthor))}</dt>
			<dd>{html $Premanager_ProjectNode_author}</dd>
		</dl>     
		
		<dl>
			<dt>{string Premanager label array(label=string(Premanager projectDescription))}</dt>
			<dd>{html $Premanager_ProjectNode_description}</dd>
		</dl>       
		
		<dl>
			<dt>{string Premanager label array(label=string(Premanager projectKeywords))}</dt>
			<dd>{html $Premanager_ProjectNode_keywords}</dd>
		</dl>     
	</div>
{/block}

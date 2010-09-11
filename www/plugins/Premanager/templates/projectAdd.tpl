{extends "projectForm.tpl"}

{block "toolbar"}
	<ul class="toolbar">
		<li><a href="./{treeURL Premanager ProjectsNode}?add" class="tool-add-project active" title="{string Premanager addProjectDescription}">{string Premanager addProject}</a></li>
	</ul>
{/block}

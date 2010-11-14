<div class="info-list">
	<dl>
		<dt>{string Premanager label array(label=string(Premanager projectTitle))}</dt>
		<dd><span class="title">{html $project->getTitle()}</span>{if $project->getSubtitle()}{string Premanager titleDivider}{html $project->getSubtitle()}{/if}</dd>
	</dl>  
	
	<dl>
		<dt>{string Premanager label array(label=string(Premanager projectAuthor))}</dt>
		<dd>{html $project->getAuthor()}</dd>
	</dl>     
	
	<dl>
		<dt>{string Premanager label array(label=string(Premanager projectDescription))}</dt>
		<dd>{html $project->getDescription()}</dd>
	</dl>       
	
	<dl>
		<dt>{string Premanager label array(label=string(Premanager projectKeywords))}</dt>
		<dd>{html $project->getKeywords()}</dd>
	</dl>     
</div>
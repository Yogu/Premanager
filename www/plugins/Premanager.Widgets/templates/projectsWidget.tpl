<ul class="list">
	{foreach $projects project}
		<li><a href="./{if $project->getID()}{url $project->getName()}{/if}">{html $project->getTitle()}</a></li>
	{/foreach}
</ul>

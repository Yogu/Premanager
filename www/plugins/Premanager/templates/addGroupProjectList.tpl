<ul class="list">
	{foreach $list project}
		<li>
			<a href="./{$node->getParent()->getURL()}/{if $project->getID()}{url $project->getName()}{else}-{/if}/+">{html $project->getTitle()}</a>
		</li>
	{/foreach}
</ul>
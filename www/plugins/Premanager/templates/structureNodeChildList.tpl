<ul class="list">
	{if $node->getStructureNode()->getParent()}
		<li class="go-up">
			<a href="./{$node->getParent()->getURL()}">{string Premanager upperStructureNode array(title=$node->getParent()->getTitle())}</a></td>
		</li>
	{/if}
	{foreach $children structureNode}
		<li>
			<a href="./{$node->getURL()}/{html $structureNode->getName()}">{html $structureNode->getTitle()}</a></td>
		</li>
	{/foreach}
</ul>

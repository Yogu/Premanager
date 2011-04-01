<li{if $selected==$node} class="selected"{/if}{if $node==$structureNode} class="active"{/if}>
	<div>
		<a href="./{html treeURL(Premanager structure)}/{$url}">{html $node->getTitle()}</a>
		
		<ul class="toolbar show-titles">
			{if $node != $structureNode}
				{if $node == $structureNode->getParent()}
					{$error = 'moveTargetNotChangedError'}
				{elseif $node->isChildOf($structureNode)}
					{$error = 'moveTargetIsChildError'}
				{elseif $node->getTreeClass()}
					{$error = 'moveIntoTreeNodeError'}
				{elseif (!$node->areNamesAvailable($structureNode))}
					{$error = 'nodeNameAlreadyExistsInTargetError'}
				{else}
					{$error = false;}
				{/if}
			
				{if $error}
					{toolbarItem
						title=string(Premanager insertNodeHere)
						description=string(Premanager $error)
						url=$pageNode->getURL()
						iconURL='Premanager/images/tools/move.png'
						enabled=false
					}
				{else}
					{toolbarItem
						title=string(Premanager insertNodeHere)
						description=string(Premanager insertNodeHereDescription)
						url=$pageNode->getURL()
						iconURL='Premanager/images/tools/move.png'
						method=POST
						postName=concat('move-into-', $node->getID())
					}
				{/if}
			{/if}
		</ul>
	</div>
	{$children = $node->getChildren()}
	{if count($children)}
		<ul>
			{foreach $children child}
				{$name = url($child->getName())}
				{include file='structureNodeMoveItem.tpl' node=$child pageNode=$pageNode selected=$selected url=concat(concat($url, '/'), $name)}
			{/foreach}
		</ul>
	{/if}
</li>

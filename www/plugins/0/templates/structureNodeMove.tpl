{if $error}
	<ul class="input-errors">
		<li>{$error}</li>
	</ul>
{/if}

<ul class="tree">
	{include file='structureNodeMoveItem.tpl' url='-' node=$rootNode pageNode=$node selected=$selectedNode}		
</ul>
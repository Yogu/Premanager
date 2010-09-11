{extends "../../Premanager/templates/widget.tpl"}

{block "title"}<a href="./{treeURL Premanager Viewonline}" title="{htmlstring Premanager viewonlineDetailLinkTitle}">{htmlstring Premanager viewonline}</a>{/block}
{block "content"} 
	<ul class="list">
		{$Premanager_ViewonlineWidget_list}
	</ul>
{/block}

{extends "../../Premanager/templates/main.tpl"}

{block "content"}                          
	{if !($UserNode_panelHead || $UserNode_panelFoot || count($UserNode_panelCol))}
		<p>{string Premanager defaultPage}</p>
	{/if}
{/block}
								
{block "after"}
	{if $UserNode_panelHead || $UserNode_panelFoot || count($UserNode_panelCol)}
		<div class="widget-panel">
			{if $UserNode_panelHead}
				<div class="widget-panel-group">
					{$UserNode_panelHead}
				</div>
			{/if}
			{if count($UserNode_panelCol)}
				<div class="widget-panel-body cols-{count($UserNode_panelCol)}">
					{foreach $UserNode_panelCol col}
						<div class="widget-panel-group widget-panel-col">
							{$col}				
						</div>			
					{/foreach}
				</div>
			{/if}
			{if $UserNode_panelFoot}
				<div class="widget-panel-group">
					{$UserNode_panelFoot}
				</div>
			{/if}
		</div>
	{/if}
{/block}												
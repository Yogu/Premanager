{extends "../../Premanager/templates/main.tpl"}

{block "head"}
	<link rel="stylesheet" type="text/css" href="{$Config_staticURLPrefix}Premanager/styles/common.css" />
{/block}

{block "content"}
	{if $Premanager_Structure_list}
		<p>{string Premanager structureMessage}</p>
	{else}
		<p>{string Premanager structureEmpty}</p>
	{/if}
{/block}

{block "after"} 
	{if $Premanager_StructureNode_list}
		<dl class="block">
			<dt>{string Premanager structureTitle}</dt>
			<dd>
				<ul class="structure-admin">
					{$Premanager_StructureNode_list}
				</ul>
			</dd>
		</dl>
	{/if}	
{/block}

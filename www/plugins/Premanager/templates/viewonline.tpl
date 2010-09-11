{extends "../../Premanager/templates/main.tpl"}

{block "content"}
	{if $Premanager_Viewonline_list}
		<p>{string Premanager viewonlineMessage array(timeSpan=$Options_Premanager_viewonlineLength/60)}</p>
	{else}
		<p>{string Premanager viewonlineEmpty}</p>
	{/if}
{/block}

{block "after"} 
	{if $Premanager_Viewonline_list}
		<div class="block">
			<table>
				<thead>
					<tr>
						<th>{string Premanager viewonlineUser}</th>
						<th>{string Premanager viewonlineLastRequest}</th>
						<th>{string Premanager viewonlineLocation}</th>
					</tr>
				</thead>
				<tbody>
					{$Premanager_Viewonline_list}
				</tbody>
			</table>
		</div>
	{/if}
{/block}

{extends "../../Premanager/templates/main.tpl"}

{block "toolbar"}
	<ul class="toolbar">
		{if $Me_right_Premanager_manageUsers}
			<li><a href="./{treeURL Premanager Users}?add" class="tool-add-user" title="{string Premanager addUserDescription}">{string Premanager addUser}</a></li>
		{/if}	 
		<li class="new-group"><a href="./{treeURL Premanager Groups}" class="tool-goto-groups" title="{string Premanager gotoGroupsDescription}">{string Premanager gotoGroups}</a></li>	
	</ul>
{/block}

{block "content"}
	{if $Premanager_Users_list}
		<p>{string Premanager userListMessage}</p>
	{else}
		<p>{string Premanager userListEmpty}</p>
	{/if}
{/block}

{block "after"} 
	{if $Premanager_Users_list}
		<div class="block">
			<table>
				<thead>
					<tr>
						<th>{string Premanager userListName}</th>
						<th>{string Premanager userRegistrationTime}</th>
						<th>{string Premanager userLastLoginTime}</th>
					</tr>
				</thead>
				<tbody>
					{$Premanager_Users_list}
				</tbody>
			</table>
		</div>
	{/if}	
{/block}

{foreach $rights right}
	{if ($project->getID() && $right->getScope() != 0) || (!$project->getID() && $right->getScope() != 1)} {* ORGANIZATION = 0; PROJECTS = 1 *}
		<tr>
			<td>
				<span class="title">{$right->getTitle()}</span>
				<span class="detail">{$right->getDescription()}</span>
			</td>
			{*<td>
				<a href="./{treeURL Premanager groups}/{url $Premanager_User_groupName}">
					<span class="title" style="color: #{$Premanager_User_groupColor};">{html $Premanager_User_groupName}</span>
				</a>
			</td>*}
		</tr>
	{/if}
{/foreach}

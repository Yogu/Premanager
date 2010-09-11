{extends "../../Premanager/templates/main.tpl"} 

{block "toolbar"}
	{if $Premanager_Group_meCanEdit}
		<ul class="toolbar">
			<li><a href="./{treeURL Premanager Groups}/{html $Premanager_Group_name}?edit" class="tool-edit-group" title="{string Premanager editGroupDescription}">{string Premanager editGroup}</a></li>
			<li><a href="./{treeURL Premanager Groups}/{html $Premanager_Group_name}?delete" class="tool-delete-group" title="{string Premanager deleteGroupDescription}">{string Premanager deleteGroup}</a></li>	
			{if $Me_right_Premanager_manageRights}                 
				<li><a href="./{treeURL Premanager Groups}/{url $Premanager_Group_name}?rights" class="tool-edit-group-rights" title="{string Premanager editGroupRightsDescription}">{string Premanager editGroupRights}</a></li>
			{/if}         
			{if $Me_right_Premanager_lockGroups} 
				{if $Premanager_Group_isLocked}                                
					<li><a href="./{treeURL Premanager Groups}/{url $Premanager_Group_name}?unlock" class="tool-unlock-group" title="{string Premanager unlockGroupDescription}">{string Premanager unlockGroup}</a></li>
				{else}
					<li><a href="./{treeURL Premanager Groups}/{url $Premanager_Group_name}?lock" class="tool-lock-group" title="{string Premanager lockGroupDescription}">{string Premanager lockGroup}</a></li>
				{/if}
			{/if}
		</ul>
	{/if}
{/block}

{block "content"}
	<div class="info-list">
		<dl>
			<dt>{string Premanager label array(label=string(Premanager groupName))}</dt>
			<dd><span class="title" style="color: #{$Premanager_Group_color}">{html $Premanager_Group_name}</span></dd>
		</dl>

		<dl>
			<dt>{string Premanager label array(label=string(Premanager groupTitle))}</dt>
			<dd>{html $Premanager_Group_title}</dd>
		</dl> 
		
		<dl>
			<dt>{string Premanager label array(label=string(Premanager groupText))}</dt>
			<dd>{html $Premanager_Group_text}</dd>
		</dl>
	</div>
{/block}      

{block "after"}
	{if $Premanager_Group_memberList}
		<dl class="block">
			<dt>{string Premanager groupMemberList} {if $Premanager_Group_pageCount > 1}{string Premanager brackets array(content=string(Premanager pageXOfY array(page=$Premanager_Group_page, pageCount=$Premanager_Group_pageCount)))}{/if}</dt>
			<dd>
				<ul class="list">
					{$Premanager_Group_memberList}
				</ul>
			</dd>
		</dl>
	{/if}
{/block}

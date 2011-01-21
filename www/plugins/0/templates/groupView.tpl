<div class="info-list">
	<dl>
		<dt>{string Premanager label array(label=string(Premanager groupName))}</dt>
		<dd><span style="color: #{$group->getColor()}">{html $group->getName()}</span></dd>
	</dl>

	<dl>
		<dt>{string Premanager label array(label=string(Premanager groupTitle))}</dt>
		<dd>{html $group->getTitle()}</dd>
	</dl>  

	<dl>
		<dt>{string Premanager label array(label=string(Premanager groupProject))}</dt>
		<dd><a href="./{treeURL Premanager projects}/{if $group->getProject()->getID()}{url $group->getProject()->getName()}{else}-{/if}">{html $group->getProject()->getTitle()}</a></dd>
	</dl> 

	<dl>
		<dt>{string Premanager label array(label=string(Premanager groupMemberCountLabel))}</dt>
		<dd>{$group->getMembers()->getCount()}</dd>
	</dl>

	<dl>
		<dt>{string Premanager label array(label=string(Premanager groupText))}</dt>
		<dd>{$group->getText()}</dd>
	</dl>
</div>

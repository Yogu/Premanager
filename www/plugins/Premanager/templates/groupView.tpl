<div class="info-list">
	<dl>
		<dt>{string Premanager label array(label=string(Premanager groupName))}</dt>
		<dd><span style="color: #{$group->color}">{html $group->name}</span></dd>
	</dl>

	<dl>
		<dt>{string Premanager label array(label=string(Premanager groupTitle))}</dt>
		<dd>{html $group->title}</dd>
	</dl>  

	<dl>
		<dt>{string Premanager label array(label=string(Premanager groupMemberCountLabel))}</dt>
		<dd>{$group->getMemberCount()}</dd>
	</dl>

	<dl>
		<dt>{string Premanager label array(label=string(Premanager groupText))}</dt>
		<dd>{$group->text}</dd>
	</dl>
</div>

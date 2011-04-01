<div class="info-list">
	<dl>
		<dt>{string Premanager label array(label=string(Premanager nodeTitleLabel))}</dt>
		<dd>{html $structureNode->getTitle()}</dd>
	</dl>
	
	{if $structureNode->getParent()}
		<dl>
			<dt>{string Premanager label array(label=string(Premanager nodeNameLabel))}</dt>
			<dd>
				<div>{html $structureNode->getName()}</div>
				<p class="description">{string Premanager nodeNameDescription}</p>
			</dd>
		</dl>
	{/if}
</div>

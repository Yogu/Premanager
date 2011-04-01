{form showErrors=true}
	<fieldset class="inputs">
		{if count($rights)}
			{$lastPlugin = ''}
			{foreach $rights right}
				{if $lastPlugin != $right->getPlugin()->getName()}
					{if $lastPlugin}
						</dl>
					{/if}
					{$lastPlugin = $right->getPlugin()->getName()}
					<dl><dt><label>{html string(Premanager label array(label=$lastPlugin))}</label></dt>
				{/if}
				<dd>
					{formElement
						name=$right->getID()
						label=$right->getTitle()
						description=$right->getDescription()
						type=checkbox
						leftColumn=false
					}
				</dd>
			{/foreach}
			</dl>
		{/if}
	</fieldset>
	
	<fieldset class="buttons">
		<input type="submit" name="submit" class="main" value="{string Premanager submitButton}" />
	</fieldset>
{/form}

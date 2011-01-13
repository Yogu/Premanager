{form showErrors=true}
	<fieldset class="inputs">
		{formElement
			name="title"
			label=string(Premanager nodeTitleLabel)
			description=string(Premanager nodeTitleDescription)
			type=text
		}
	
		{if $structureNode->getParent() || $add}
			{formElement
				name="name"
				label=string(Premanager nodeNameLabel)
				description=string(Premanager nodeNameDescription)
				type=text
			}
		{/if}
	</fieldset>
	
	<fieldset class="buttons">
		<input type="submit" name="submit" class="main" value="{string Premanager submitButton}" />
		<input type="submit" name="cancel" value="{string Premanager confirmationCancelButton}" />
	</fieldset>
{/form}

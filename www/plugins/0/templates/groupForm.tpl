{form showErrors=true}
	<fieldset class="inputs">
		{formElement
			name="name"
			label=string(Premanager groupName)
			description=string(Premanager groupNameDescription)
			type=text
		}
		
		{formElement
			name="title"
			label=string(Premanager groupTitle)
			description=string(Premanager groupTitleDescription)
			type=text
		}
		
		{formElement
			name="color"
			label=string(Premanager groupColor)
			description=string(Premanager groupColorDescription)
			type=color
		}
		
		{if $group && !$group->getProject()->getID()}
			{formElement
				name="priority"
				label=string(Premanager groupPriority)
				description=string(Premanager groupPriorityDescription)
				type=text
			}
		{/if}
		
		{formElement
			name="text"
			label=string(Premanager groupText)
			description=string(Premanager groupTextDescription)
			type=textarea
		}
		
		{formElement
			name="autoJoin"
			label=string(Premanager groupAutoJoinLabel)
			description=string(Premanager groupAutoJoinDescription)
			type=checkbox
		}
		
		{formElement
			name="loginConfirmationRequired"
			label=string(Premanager groupLoginConfirmationRequiredLabel)
			description=string(Premanager groupLoginConfirmationRequiredDescription)
			type=checkbox
		}
	</fieldset>
	
	<fieldset class="buttons">
		<input type="submit" name="form-submit" class="main" value="{string Premanager submitButton}" />
	</fieldset>
{/form}
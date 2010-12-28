{form showErrors=true}
	<fieldset class="inputs">
		{formElement
			name="name"
			label=string(Premanager userName)
			description=string(Premanager userNameDescription)
			type=text
		}
		
		{formElement
			name="password"
			label=string(Premanager passwordLabel)
			description=string(Premanager passwordDescription)
			type=password
			attributes=array(autocomplete='off')
		}
		
		{formElement
			name="passwordConfirmation"
			label=string(Premanager passwordConfirmationLabel)
			description=string(Premanager passwordConfirmationDescription)
			type=password
			attributes=array(autocomplete='off')
		}
		
		{formElement
			name="email"
			label=string(Premanager registrationEmailLabel)
			description=string(Premanager registrationEmailDescription)
			type=text
		}
		
		{if !$user || $user->getID()}
			{formElement
				name="isEnabled"
				label=string(Premanager enableUserLabel)
				description=string(Premanager enableUserDescription)
				type=checkbox
			}
		{/if}
	</fieldset>
	
	<fieldset class="buttons">
		<input type="submit" name="form-submit" class="main" value="{string Premanager submitButton}" />
	</fieldset>
{/form}
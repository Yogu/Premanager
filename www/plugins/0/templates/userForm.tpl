{form showErrors=true}
	<fieldset class="inputs">
		{formElement
			name="name"
			label=string(Premanager userName)
			description=string(Premanager userNameDescription)
			type=text
		}
		
		{if $user}
			{$description1 = changePasswordDescription}
			{$description2 = changePasswordConfirmationDescription}
			{$description3 = emailConfirmationDescription}
		{else}
			{$description1 = passwordDescription}
			{$description2 = passwordConfirmationDescription}
			{$description3 = registerEmailConfirmationDescription}
		{/if}
			
		{formElement
			name="password"
			label=string(Premanager passwordLabel)
			description=string(Premanager, $description1)
			type=password
			attributes=array(autocomplete='off')
		}
		
		{formElement
			name="passwordConfirmation"
			label=string(Premanager passwordConfirmationLabel)
			description=string(Premanager, $description2)
			type=password
			attributes=array(autocomplete='off')
		}
		
		{formElement
			name="email"
			label=string(Premanager registrationEmailLabel)
			description=string(Premanager registrationEmailDescription)
			type=text
		}
		
		{formElement
			name="emailConfirmation"
			label=string(Premanager registerEmailConfirmationLabel)
			description=string(Premanager $description3)
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
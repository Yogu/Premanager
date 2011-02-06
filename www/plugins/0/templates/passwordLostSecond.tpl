{form showErrors=true}
	<p>{string Premanager passwordLostSecondMessage array(userName=$user->getName())}</p>

	<fieldset class="inputs">
		{formElement
			name="password"
			label=string(Premanager registerPasswordLabel)
			description=string(Premanager registerPasswordDescription)
			type=password
		}
		
		{formElement
			name="passwordConfirmation"
			label=string(Premanager registerPasswordConfirmationLabel)
			description=string(Premanager registerPasswordConfirmationDescription)
			type=password
		}
	</fieldset>
	
	<fieldset class="buttons">
		<input type="submit" name="submit" class="main" value="{string Premanager submitButton}" />
	</fieldset>
{/form}
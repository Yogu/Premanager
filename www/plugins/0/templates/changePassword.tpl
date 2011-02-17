<p>{string Premanager changePasswordMessage array(passwordLostURL=treeURL('Premanager', 'password-lost'))}</p>

{form showErrors=true}
	<fieldset class="inputs">
		{formElement
			name="currentPassword"
			label=string(Premanager currentPasswordLabel)
			description=string(Premanager currentPasswordDescription)
			type=password
		}
		
		{formElement
			name="password"
			label=string(Premanager passwordLabel)
			description=string(Premanager passwordDescription)
			type=password
		}
		
		{formElement
			name="passwordConfirmation"
			label=string(Premanager passwordConfirmationLabel)
			description=string(Premanager passwordConfirmationDescription)
			type=password
		}
	</fieldset>
	
	<fieldset class="buttons">
		<input type="submit" name="submit" class="main" value="{string Premanager submitButton}" />
	</fieldset>
{/form}
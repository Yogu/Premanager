{form showErrors=true}
	<p>{string Premanager passwordLostMessage}</p>

	<fieldset class="inputs">
		{formElement
			name="email"
			label=string(Premanager passwordLostEmailLabel)
			description=string(Premanager passwordLostEmailDescription)
			type=text
		}
	</fieldset>
	
	<fieldset class="buttons">
		<input type="submit" name="submit" class="main" value="{string Premanager submitButton}" />
	</fieldset>
{/form}
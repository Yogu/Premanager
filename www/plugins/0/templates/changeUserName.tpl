<p>{string Premanager changeUserNameMessage}</p>

{form showErrors=true}
	<fieldset class="inputs">
		{formElement
			name="name"
			label=string(Premanager changeUserNameLabel)
			description=string(Premanager changeUserNameDescription)
			type=text
		}
	</fieldset>
	
	<fieldset class="buttons">
		<input type="submit" name="submit" class="main" value="{string Premanager submitButton}" />
	</fieldset>  
{/form}
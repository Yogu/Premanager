{form showErrors=true}
	<fieldset class="inputs">
		{formElement
			name="groups"
			type=select
			options=$groups
			size=20
			multiple=true
			leftColumn=false
			fullsize=true
		}
	</fieldset>
	
	<fieldset class="buttons">
		<input type="submit" name="submit" class="main" value="{string Premanager submitButton}" />
		<input type="submit" name="cancel" value="{string Premanager confirmationCancelButton}" />
	</fieldset>
{/form}

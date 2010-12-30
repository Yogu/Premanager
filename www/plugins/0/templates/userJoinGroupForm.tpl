{form showErrors=true}
	<fieldset class="inputs">
		{formElement
			name="groups"
			type=select
			options=$groups
			size=10
			multiple=true
			leftColumn=false
			fullsize=true
		}
	</fieldset>
	
	<fieldset class="buttons">
		<input type="submit" name="form-submit" class="main" value="{string Premanager submitButton}" />
	</fieldset>
{/form}
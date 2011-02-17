{if $canRemoveEmail}
	<p>{string Premanager changeOrRemoveEmailMessage}</p>
{else}
	<p>{string Premanager changeEmailMessage}</p>
{/if}

{form showErrors=true}
	<fieldset class="inputs">
		{if $canRemoveEmail}
			{$desc1 = registerOptionalEmailDescription}
			{$desc2 = registerOptionalEmailConfirmationDescription}
		{else}
			{$desc1 = registerEmailDescription}
			{$desc2 = registerEmailConfirmationDescription}
		{/if}
		
		{formElement
			name="email"
			label=string(Premanager registerEmailLabel)
			description=string(Premanager $desc1)
			type=text
		}
		
		{formElement
			name="emailConfirmation"
			label=string(Premanager registerEmailConfirmationLabel)
			description=string(Premanager $desc2)
			type=text
		}
	</fieldset>
	
	<fieldset class="buttons">
		<input type="submit" name="submit" class="main" value="{string Premanager submitButton}" />
	</fieldset>  
{/form}
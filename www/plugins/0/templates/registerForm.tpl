{if $emailOptional}
	<p>{string Premanager registrationEmailOptionalMessage}</p>
{else}
	<p>{string Premanager registrationMessage}</p>
{/if}

{form showErrors=true}
	<fieldset class="inputs">
		{formElement
			name="name"
			label=string(Premanager registerUserLabel)
			description=string(Premanager registerUserDescription)
			type=text
		}
			
		{formElement
			name="password"
			label=string(Premanager registerPasswordLabel)
			description=string(Premanager, registerPasswordDescription)
			type=password
		}
		
		{formElement
			name="passwordConfirmation"
			label=string(Premanager registerPasswordConfirmationLabel)
			description=string(Premanager, registerPasswordConfirmationDescription)
			type=password
			attributes=array(autocomplete='off')
		}
		
		{if $emailOptional}
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
		<input type="submit" name="submit" class="main" value="{string Premanager registerButton}" />
	</fieldset>     
		
	<div class="info-box">
		<p>{string Premanager registerLoginTip} <a href="./{treeURL Premanager login}">{string Premanager registerLoginTipLinkText}</a></p>
	</div>
{/form}
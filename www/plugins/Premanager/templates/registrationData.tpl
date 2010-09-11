{extends "../../Premanager/templates/main.tpl"}

{block "content"}
	<form action="./{$Client_internalRequestURL}" method="post">
		{if $Premanager_RegistrationData_inputErrors}
			<ul class="input-errors">
				{$Premanager_RegistrationData_inputErrors}	
			</ul>
		{/if}  
		
		<fieldset class="inputs large">
			{if $Me_right_Premanager_changeUserName}
				<dl>
					<dt><label for="Premanager_RegistrationData_name">{string Premanager label array(label=string(Premanager registrationDataNameLabel))}</label></dt>
					<dd>
						<input type="text" name="Premanager_RegistrationData_name" id="Premanager_RegistrationData_name" value="{html $Premanager_RegistrationData_name}"{if $Premanager_RegistrationData_name_incorrect} class="error"{/if} />
						<p>{string Premanager registrationDataNameDescription}</p>
					</dd>
				</dl> 
			{/if}		
			 					
			<dl>
				<dt><label for="Premanager_RegistrationData_password">{string Premanager label array(label=string(Premanager passwordLabel))}</label></dt>     
				<dd>
					<input name="Premanager_RegistrationData_password" id="Premanager_RegistrationData_password" autocomplete="off" type="password"{if $Premanager_RegistrationData_password_incorrect} class="error"{/if} />
 					<p>{string Premanager changePasswordDescription}</p>
				</dd>
			</dl>               

			<dl>
				<dt><label for="Premanager_RegistrationData_passwordConfirmation">{string Premanager label array(label=string(Premanager passwordConfirmationLabel))}</label></dt>     
				<dd>
					<input name="Premanager_RegistrationData_passwordConfirmation" id="Premanager_RegistrationData_passwordConfirmation" type="password"{if $Premanager_RegistrationData_passwordConfirmation_incorrect} class="error"{/if} />
 					<p>{string Premanager changePasswordConfirmationDescription}</p>
				</dd>
			</dl>

			<dl>
				<dt><label for="Premanager_RegistrationData_email">{string Premanager label array(label=string(Premanager registrationEmailLabel))}</label></dt>
				<dd>
					<input type="text" name="Premanager_RegistrationData_email" id="Premanager_RegistrationData_email" value="{html $Premanager_RegistrationData_email}"{if $Premanager_RegistrationData_email_incorrect} class="error"{/if} />
					<p>{string Premanager registrationEmailDescription}</p>
				</dd>
			</dl>
				
			<dl>
				<dt><label for="Premanager_RegistrationData_emailConfirmation">{string Premanager label array(label=string(Premanager registerEmailConfirmationLabel))}</label></dt>
				<dd>
					<input type="text" name="Premanager_RegistrationData_emailConfirmation" id="Premanager_RegistrationData_emailConfirmation" value="{html $Premanager_RegistrationData_emailConfirmation}"{if $Premanager_RegistrationData_emailConfirmation_incorrect} class="error"{/if} />
					<p>{string Premanager emailConfirmationDescription}</p>
				</dd>
			</dl> 
		</fieldset>
		
		<fieldset class="buttons">
			<input type="submit" name="Premanager_RegistrationData_form" class="main" value="{string Premanager submitButton}" />
		</fieldset>    
	</form>
{/block}

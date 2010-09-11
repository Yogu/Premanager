{extends "../../Premanager/templates/main.tpl"}

{block "content"}
	<form action="./{$Client_internalRequestURL}" method="post">
	  <p>{if $Me_right_Premanager_registerWithoutEmail}{string Premanager registerWithoutEmailMessage}{else}{string Premanager registerMessage}{/if}</p>
	  
		{if $Premanager_Register_inputErrors}
			<ul class="input-errors">
				{$Premanager_Register_inputErrors}	
			</ul>
		{/if}  
		
		<fieldset class="inputs large">
			<dl>
				<dt><label for="Premanager_Register_name">{string Premanager label array(label=string(Premanager registerUserLabel))}</label></dt>
				<dd>
					<input type="text" name="Premanager_Register_name" id="Premanager_Register_name" value="{html $Premanager_Register_name}"{if $Premanager_Register_name_incorrect} class="error"{/if} />
					<p>{string Premanager registerUserDescription}</p>
				</dd>
			</dl>
						
			<dl>
				<dt><label for="Premanager_Register_password">{string Premanager label array(label=string(Premanager registerPasswordLabel))}</label></dt>     
				<dd>
					<input name="Premanager_Register_password" id="Premanager_Register_password" autocomplete="off" type="password"{if $Premanager_Register_password_incorrect} class="error"{/if} />
 					<p>{string Premanager registerPasswordDescription}</p>
				</dd>
			</dl>               
						  							
			<dl>
				<dt><label for="Premanager_Register_passwordConfirmation">{string Premanager label array(label=string(Premanager registerPasswordConfirmationLabel))}</label></dt>     
				<dd>
					<input name="Premanager_Register_passwordConfirmation" id="Premanager_Register_passwordConfirmation" type="password"{if $Premanager_Register_passwordConfirmation_incorrect} class="error"{/if} />
 					<p>{string Premanager registerPasswordConfirmationDescription}</p>
				</dd>
			</dl>
			    
			<dl>
				<dt><label for="Premanager_Register_email">{string Premanager label array(label=string(Premanager registerEmailLabel))}</label></dt>
				<dd>
					<input type="text" name="Premanager_Register_email" id="Premanager_Register_email" value="{html $Premanager_Register_email}"{if $Premanager_Register_email_incorrect} class="error"{/if} />
					<p>{if $Me_right_Premanager_registerWithoutEmail}{string Premanager registerOptionalEmailDescription}{else}{string Premanager registerEmailDescription}{/if}</p>
				</dd>
			</dl>  
			    
			<dl>
				<dt><label for="Premanager_Register_emailConfirmation">{string Premanager label array(label=string(Premanager registerEmailConfirmationLabel))}</label></dt>
				<dd>
					<input type="text" name="Premanager_Register_emailConfirmation" id="Premanager_Register_emailConfirmation" value="{html $Premanager_Register_emailConfirmation}"{if $Premanager_Register_emailConfirmation_incorrect} class="error"{/if} />
					<p>{if $Me_right_Premanager_registerWithoutEmail}{string Premanager registerOptionalEmailConfirmationDescription}{else}{string Premanager registerEmailConfirmationDescription}{/if}</p>
				</dd>
			</dl>
		</fieldset>
		
		<fieldset class="buttons">
			<input type="submit" name="Premanager_Register_form" class="main" value="{string Premanager registerButton}" />
		</fieldset>     
		
		<div class="info-box">
			<p>{string Premanager registerLoginTip} <a href="./{treeURL Premanager LoginForm}">{string Premanager registerLoginTipLinkText}</a></p>
		</div>
	</form>
{/block}

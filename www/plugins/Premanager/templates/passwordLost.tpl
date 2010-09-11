{extends "../../Premanager/templates/main.tpl"}

{block "content"}
	<p>{string Premanager passwordLostMessage}</p>
	
	{if $Premanager_PasswordLost_inputErrors}
		<ul class="input-errors">
			{$Premanager_PasswordLost_inputErrors}	
		</ul>
	{/if}  

	<form action="./{$Client_internalRequestURL}" method="post">
		<fieldset class="inputs">
			<dl>
				<dt><label for="Premanager_PasswordLost_userName">{string Premanager label array(label=string(Premanager passwordLostUserLabel))}</label></dt>
				<dd>
					<input type="text" name="Premanager_PasswordLost_userName" id="Premanager_PasswordLost_userName" value="{html $Premanager_PasswordLost_userName}" class="small{if $Premanager_PasswordLost_userName_incorrect} error{/if}" />
					<p>{string Premanager passwordLostUserDescription}</p>
				</dd>
			</dl>
			
			<dl>
				<dt><label for="Premanager_PasswordLost_email">{string Premanager label array(label=string(Premanager passwordLostEmailLabel))}</label></dt>
				<dd>
					<input type="text" name="Premanager_PasswordLost_email" id="Premanager_PasswordLost_email" value="{html $Premanager_PasswordLost_email}" class="small{if $Premanager_PasswordLost_email_incorrect} error{/if}" />
					<p>{string Premanager passwordLostEmailDescription}</p>
				</dd>
			</dl>
		</fieldset>
		
		<fieldset class="buttons">
			<input type="submit" name="Premanager_PasswordLost_form" class="main" value="{string Premanager submitButton}" />
		</fieldset>
	</form>
{/block}

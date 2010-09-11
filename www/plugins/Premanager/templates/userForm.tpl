{extends "../../Premanager/templates/main.tpl"}     

{block "content"}
	<form action="{html $Client_requestURL}" method="post">
		{if $Premanager_User_inputErrors}
			<ul class="input-errors">
				{$Premanager_User_inputErrors}	
			</ul>
		{/if}   
		
		<input type="submit" name="Premanager_User_form" style="display: none;" />
	
		<fieldset class="inputs large">
			<dl>
				<dt><label for="Premanager_User_name">{string Premanager label array(label=string(Premanager userName))}</label></dt>
				<dd>
					<input type="text" name="Premanager_User_name" id="Premanager_User_name" value="{html $Premanager_User_formName}"{if $Premanager_User_name_incorrect} class="error"{/if} />
					<p>{string Premanager userNameDescription}</p>
				</dd>
			</dl> 
			    
			{if $Premanager_User_id || $Premanager_User_mode == 'add'}   							
				<dl>
					<dt><label for="Premanager_User_isBot">{string Premanager label array(label=string(Premanager isBotShortLabel))}</label></dt>
					<dd>       
						<div>   
							<label for="Premanager_User_isBot">
							<input type="checkbox" id="Premanager_User_isBot" name="Premanager_User_isBot"{if $Premanager_User_isBot} checked="checked"{/if} onchange="javascript:updateIsBot();" />
								{string Premanager isBotLabel}
							</label>
						</div>
						<p>{string Premanager isBotDescription}</p>  
					</dd>  
				</dl>              
							  							
				<dl id="botField">
					<dt><label for="Premanager_User_botIdentifier">{string Premanager label array(label=string(Premanager botIdentifier))}</label></dt>     
					<dd>
						<input name="Premanager_User_botIdentifier" id="Premanager_User_botIdentifier" type="text" value="{html $Premanager_User_botIdentifier}"{if $Premanager_User_botIdentifier_incorrect} class="error"{/if} />
	 					<p>{string Premanager botIdentifierDescription}</p>
					</dd>
				</dl>                
							  							
				<dl id="passwordField1">
					<dt><label for="Premanager_User_password">{string Premanager label array(label=string(Premanager passwordLabel))}</label></dt>     
					<dd>
						<input name="Premanager_User_password" id="Premanager_User_password" autocomplete="off" type="password"{if $Premanager_User_password_incorrect} class="error"{/if} />
	 					<p>{string Premanager changePasswordDescription}</p>
					</dd>
				</dl>               
							  							
				<dl id="passwordField2">
					<dt><label for="Premanager_User_passwordConfirmation">{string Premanager label array(label=string(Premanager passwordConfirmationLabel))}</label></dt>     
					<dd>
						<input name="Premanager_User_passwordConfirmation" id="Premanager_User_passwordConfirmation" type="password"{if $Premanager_User_passwordConfirmation_incorrect} class="error"{/if} />
	 					<p>{string Premanager changePasswordConfirmationDescription}</p>
					</dd>
				</dl>    

				<dl id="emailField1">
					<dt><label for="Premanager_User_email">{string Premanager label array(label=string(Premanager registrationEmailLabel))}</label></dt>
					<dd>
						<input type="text" name="Premanager_User_email" id="Premanager_User_email" value="{html $Premanager_User_email}"{if $Premanager_User_email_incorrect} class="error"{/if} />
						<p>{string Premanager registrationEmailDescription}</p>
					</dd>
					{if $Premanager_User_unconfirmedEmail}
						<dd>
							<div class="info-box">
								{string Premanager userHasUnconfirmedEmailInfo array(email=$Premanager_User_unconfirmedEmail)}
								<input type="submit" name="Premanager_User_resetUnconfirmedEmail" value="{string Premanager resetUnconfirmedEmailButton}" />
								<input type="submit" name="Premanager_User_confirmUnconfirmedEmail" value="{string Premanager confirmUnconfirmedEmailButton}" />
							</div>												
						</dd>
					{/if}
				</dl>        

				<dl id="emailField2">
					<dt><label for="Premanager_User_emailConfirmation">{string Premanager label array(label=string(Premanager registerEmailConfirmationLabel))}</label></dt>
					<dd>
						<input type="text" name="Premanager_User_emailConfirmation" id="Premanager_User_emailConfirmation" value="{html $Premanager_User_emailConfirmation}"{if $Premanager_User_emailConfirmation_incorrect} class="error"{/if} />
						<p>{string Premanager emailConfirmationDescription}</p>
					</dd>
				</dl> 
				 			
				{if $Premanager_User_mode == 'add' || $Premanager_User_id}  
					<dl>
						<dt><label for="Premanager_User_status">{string Premanager label array(label=string(Premanager userStatusLabel))}</label></dt>
						<dd>    
							<select id="Premanager_User_status" name="Premanager_User_status">
								<option value="enabled"{if $Premanager_User_status == 'enabled'} selected="selected"{/if}>{string Premanager userStatusEnabled}</option>
								{if $Premanager_User_unconfirmedEmail}
									<option value="waitForEmail"{if $Premanager_User_status == 'waitForEmail'} selected="selected"{/if}>{string Premanager userStatusWaitForEmail}</option>
								{/if}
								<option value="disabled"{if $Premanager_User_status == 'disabled'} selected="selected"{/if}>{string Premanager userStatusDisabled}</option>
							</select>
						
							<p>{string Premanager userStatusDescription}</p>
						</dd>  
					</dl>
				{/if}

				<script type="text/javascript">
					function updateIsBot() \{    
						var checkBox = document.getElementById('Premanager_User_isBot');
						var passwordField1 = document.getElementById('passwordField1');  
						var passwordField2 = document.getElementById('passwordField2');  
						var emailField1 = document.getElementById('emailField1');       
						var emailField2 = document.getElementById('emailField2');
						var botField = document.getElementById('botField');
						
						if (checkBox == null)
							return;     
						
						var d = checkBox.checked ? 'none' : 'block';
						var nd = checkBox.checked ? 'block' : 'none';
						
						if (passwordField1 != null)
							passwordField1.style.display = d;
						
						if (passwordField2 != null)
							passwordField2.style.display = d;  
						
						if (emailField1 != null)
							emailField1.style.display = d;
						
						if (emailField2 != null)
							emailField2.style.display = d;
						
						if (botField != null)
							botField.style.display = nd;
					}
					updateIsBot();
				</script>
			{/if}
		</fieldset>
		
		<fieldset class="buttons">
			<input type="submit" name="Premanager_User_form" class="main" value="{string Premanager submitButton}" />
		</fieldset>
	</form>
{/block}

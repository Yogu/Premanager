{extends "../../Premanager/templates/main.tpl"}

{block "content"}
	<form action="./" method="post">
		<fieldset class="inputs">
			<dl>
				<dt><label for="Me_nameLarge">{string Premanager label array(label=string(Premanager loginUserLabel))}</label></dt>
				<dd><input type="text" name="Me_name" id="Me_nameLarge" class="small" /></dd>
			</dl>
			
			<dl>
				<dt><label for="Me_passwordLarge">{string Premanager label array(label=string(Premanager loginPasswordLabel))}</label></dt>
				<dd>
					<input type="password" name="Me_password" id="Me_passwordLarge" class="small" />
					<p>{string Premanager loginFailedPasswordLostMessage} <a href="./{treeURL Premanager PasswordLost}">{string Premanager loginFailedPasswordLostLinkText}</a></p>
				</dd>
			</dl>  
			
			<dl>
				<dd>
					<label for="Me_hiddenLarge">
						<input type="checkbox" name="Me_hidden" id="Me_hiddenLarge" />
						{string Premanager loginHidden}
					</label>
				</dd>
			</dl>
		</fieldset>
		
		<fieldset class="buttons">
			<input type="submit" name="Me_login" class="main" value="{string Premanager loginButton}" />
		</fieldset>
		
		{if $Me_right_Premanager_register || $Me_right_Premanager_registerWithoutEmail}
			<div class="info-box">
				<p>{string Premanager loginRegisterTip} <a href="./{treeURL Premanager Register}">{string Premanager loginRegisterTipLinkText}</a></p>
			</div>	
		{/if}
	</form>
{/block}

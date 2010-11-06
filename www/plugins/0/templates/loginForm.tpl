{form}
	{if $referer}
		<input type="hidden" name="referer" value="{html $referer}" />
	{/if}
	<fieldset class="inputs">
		<dl>
			<dt><label for="Premanager_LoginPage_user">{string Premanager label array(label=string(Premanager loginUserLabel))}</label></dt>
			<dd><input type="text" name="user" id="Premanager_LoginPage_user" class="small" /></dd>
		</dl>
		
		<dl>
			<dt><label for="Premanager_LoginPage_password">{string Premanager label array(label=string(Premanager loginPasswordLabel))}</label></dt>
			<dd>
				<input type="password" name="password" id="Premanager_LoginPage_password" class="small" />
				{if !$hidePasswordLostHint}
					<p>{string Premanager loginFailedPasswordLostMessage} <a href="./{$passwordLostURL}">{string Premanager loginFailedPasswordLostLinkText}</a></p>
				{/if}	
			</dd>
		</dl>  
		
		<dl>
			<dd>
				<label for="Premanager_LoginPage_hidden">
					<input type="checkbox" name="hidden" id="Premanager_LoginPage_hidden" />
					{string Premanager loginHidden}
				</label>
			</dd>
		</dl>
	</fieldset>
	
	<fieldset class="buttons">
		<input type="submit" name="login" class="main" value="{string Premanager loginButton}" />
	</fieldset>
	
	{if $canRegister}
		<div class="info-box">
			<p>{string Premanager loginRegisterTip} <a href="./{$registerURL}">{string Premanager loginRegisterTipLinkText}</a></p>
		</div>	
	{/if}
{/form}

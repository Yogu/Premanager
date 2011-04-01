{$idPrefix = mt_rand()}

{form action=treeURL(Premanager login)}
	{if $referer}
		<input type="hidden" name="referer" value="{html $referer}" />
	{/if}
	{if $isDemo || !$currentSession}
		<fieldset class="inputs">
			<dl>
				<dt><label for="premanager-widgets-login-{$idPrefix}-user">{string Premanager label array(label=string(Premanager.Widgets loginWidgetUserLabel))}</label></dt>
				<dd><input name="user" id="premanager-widgets-login-{$idPrefix}-user" type="text" {if $isDemo}autocomplete="off"{/if} /></dd>
			</dl>
			
			<dl>
				<dt><label for="premanager-widgets-login-{$idPrefix}-password">{string Premanager label array(label=string(Premanager.Widgets loginWidgetPasswordLabel))}</label></dt>
				<dd><input name="password" id="premanager-widgets-login-{$idPrefix}-password" type="password" {if $isDemo}autocomplete="off"{/if} /></dd>
			</dl>
		</fieldset>
		
		<fieldset class="buttons">
			<input type="submit" name="login" value="{string Premanager.Widgets loginWidgetLoginButton}" {if $isDemo}disabled="disabled"{/if} />
		</fieldset>
	{else}
		<p>{string Premanager.Widgets loginWidgetLoggedInAs array(userName=$currentSession->getUser()->getName())}</p>
		
		<fieldset class="buttons">
			<input type="submit" name="logout" value="{string Premanager.Widgets loginWidgetLogoutButton}" />
		</fieldset>
	{/if} 
{/form}
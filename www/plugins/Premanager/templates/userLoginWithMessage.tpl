{extends "../../Premanager/templates/main.tpl"}

{block "content"}
	{if $message}
		<p>{$message}</p>
	{/if}
	{if $linkURL}						
		<p>
			&raquo;&nbsp;<a href="{$linkURL}">{$linkText}</a><br />
		</p>
	{/if}    
	<p>
		&raquo;&nbsp;<a href="./">{string Premanager goToHomepage}</a><br /> 
	</p>
{/block}

{block "after"}        
	<dl class="block">
		<dt>{string Premanager loginTitle}</dt>
		<dd>
			<form action="{$Config_urlPrefix}{html $Premanager_Login_targetURL}" method="post">
				<fieldset class="inputs">
					<dl>
						<dt><label for="Me_nameLarge">{string Premanager label array(label=string(Premanager loginUserLabel))}</label></dt>
						<dd><input type="text" name="Me_name" id="Me_nameLarge" class="small" /></dd>
					</dl>
					
					<dl>
						<dt><label for="Me_passwordLarge">{string Premanager label array(label=string(Premanager loginPasswordLabel))}</label></dt>
						<dd><input type="password" name="Me_password" id="Me_passwordLarge" class="small" /></dd>
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
			</form>
		</dd>
	</dl>
{/block}

{extends "../../Premanager/templates/widget.tpl"}

{block "title"}
	{if $Me_loggedIn}
		{string Premanager loggedInAs array(userName=$Me_name)}
	{else}
		<a href="./{treeURL Premanager LoginForm}" title="{string Premanager loginDetailLinkTitle}">
			{string Premanager loginSidebarTitle}
		</a>
	{/if}
{/block}

{block "content"} 
	{if $Me_loggedIn}
		<ul class="list">
			<li><a href="./my">{string Premanager widgetMyLabel}</a></li>
			<li><a href="./{treeURL Premanager Users}/{url $Me_name}">{string Premanager widgetMyProfileLabel}</a></li>
		</ul>	
		
		<form action="{html $Client_requestURL}" method="post">
			<fieldset class="buttons">
				<input type="submit" name="Me_logout" value="{string Premanager widgetLogoutButton}" />
			</fieldset>
		</form>
	{else}
		<form action="{html $Client_requestURL}" method="post">
			<fieldset class="inputs">
				<dl>
					<dt><label for="Me_name">{string Premanager label array(label=string(Premanager loginSidebarUserLabel))}</label></dt>
					<dd><input type="text" name="Me_name" id="Me_name" /></dd>
				</dl>		
				
				<dl>
					<dt><label for="Me_password">{string Premanager label array(label=string(Premanager loginSidebarPasswordLabel))}</label></dt>
					<dd><input type="password" name="Me_password" id="Me_password" /></dd>
				</dl>
			</fieldset>
			
			<fieldset class="buttons">
				<input type="submit" name="Me_login" value="{string Premanager widgetLoginButton}" />			
			</fieldset>
		</form>	
	{/if}
{/block}

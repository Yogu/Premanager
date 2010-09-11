{extends "../../Premanager/templates/mail.tpl"}

{block "plain"}
{string Premanager passwordLostEmailPlainMessage array(userName=$Premanager_PasswordLost_name, password=$Premanager_PasswordLost_secondaryPassword)}
{/block}

{block "content"}
	<p>{string Premanager passwordLostEmailMessage array(userName=$Premanager_PasswordLost_name, password=$Premanager_PasswordLost_secondaryPassword)}</p>
{/block}

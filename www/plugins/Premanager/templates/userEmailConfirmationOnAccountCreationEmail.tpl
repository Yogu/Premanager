{extends "../../Premanager/templates/mail.tpl"}

{block "plain"}
{string Premanager userEmailConfirmationOnAccountCreationEmailPlainMessage array(userName=$Premanager_Register_name, organizationTitle=$Org_title, linkURL=implode(array('http://' urlTemplate(null, null, '') treeURL(Premanager Register) '?confirm-email&key=' $Premanager_Register_unconfirmedEmailKey)))}
{/block}

{block "content"}
	<p>{string Premanager userEmailConfirmationOnAccountCreationEmailMessage array(userName=$Premanager_Register_name, organizationTitle=$Org_title, linkURL=implode(array('http://' urlTemplate(null, null, '') treeURL(Premanager Register) '?confirm-email&key=' $Premanager_Register_unconfirmedEmailKey)))}</p>
{/block}

{extends "../../Premanager/templates/main.tpl"}

{block "index"}noindex{/block}

{block "content"}     	
	<form action="{html $Client_requestURL}" method="post">
		{if $message}
			<p>{$message}</p>
		{else}
			<p>{string Premanager confirmationMessage}</p>
		{/if}
		
		<fieldset class="buttons">
			<input type="submit" name="Confirmation_confirm" value="{string Premanager confirmationConfirmButton}" class="main" />
			<input type="submit" name="Confirmation_cancel" value="{string Premanager confirmationCancelButton}" />		
		</fieldset>
	</form>
{/block}

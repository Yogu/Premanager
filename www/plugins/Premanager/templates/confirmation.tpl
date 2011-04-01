{form}
	{if $message}
		<p>{$message}</p>
	{else}
		<p>{string Premanager confirmationMessage}</p>
	{/if}
	
	<fieldset class="buttons">
		<input type="submit" name="confirm" value="{string Premanager confirmationConfirmButton}" class="main" />
		<input type="submit" name="cancel" value="{string Premanager confirmationCancelButton}" />		
	</fieldset>
{/form}

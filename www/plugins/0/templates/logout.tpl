<p>{string Premanager loginAlreadyLoggedIn array(userName=$environment->user->name)}</p>

{form}
	{if $referer}
		<input type="hidden" name="referer" value="{html $referer}" />
	{/if}
	<fieldset class="buttons">
		<input type="submit" name="logout" class="main" value="{string Premanager logoutButton}" />
	</fieldset>
{/form}

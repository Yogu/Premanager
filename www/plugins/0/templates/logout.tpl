<p>{string Premanager loginAlreadyLoggedIn array(userName=$environment->user->name)}</p>

{form}
	<fieldset class="buttons">
		{if $referer}
			<input type="hidden" name="referer" value="{html $referer}" />
		{/if}
		<input type="submit" name="logout" class="main" value="{string Premanager logoutButton}" />
	</fieldset>
{/form}

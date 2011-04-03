<p>{string Premanager.Widgets mySidebarMessage}</p>
{if $sidebar->isExisting()}
	<p>{string Premanager.Widgets resetMySidebarMessage}</p>
	{form}	
		<fieldset class="buttons">
			<input type="submit" name="reset" value="{string Premanager.Widgets resetMySidebarButton}" />
		</fieldset>
	{/form}
{/if}
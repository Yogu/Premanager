<p>{string Premanager.Widgets sidebarMessage array(userName=$sidebar->getUser()->getName())}</p>
{if $sidebar->isExisting()}
	<p>{string Premanager.Widgets resetSidebarMessage}</p>
	{form}	
		<fieldset class="buttons">
			<input type="submit" name="reset" value="{string Premanager.Widgets resetSidebarButton}" />
		</fieldset>
	{/form}
{/if}
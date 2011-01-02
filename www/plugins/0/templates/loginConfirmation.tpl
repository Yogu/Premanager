{if $state == 'finished'}
	{if $mode == 'iframe'}
		<p>{string Premanager loginConfirmationFinishedIframeMessage}</p>
	{else}
		<p>{string Premanager loginConfirmationFinishedMessage}</p>
	{/if}
{elseif $state == 'error'}
	<p>{string Premanager loginConfirmationErrorMessage}</p>
{else}
	<p>{string Premanager loginConfirmationMessage}</p>
{/if}

{if $state != 'finished'}
	{form}
		{if $referer}
			<input type="hidden" name="referer" value="{html $referer}" />
		{/if}
		<fieldset class="inputs">
			<dl>
				<dt><label>{string Premanager label array(label=string(Premanager userName))}</label></dt>
				<dd><input type="text" disabled="disabled" value="{html $userName}" class="small" /></dd>
			</dl>
		
			{formElement
				name="password"
				label=string(Premanager loginPasswordLabel)
				type=password
				attributes=array(autocomplete='off')
			}
		</fieldset>
		
		<fieldset class="buttons">
			<input type="submit" name="confirm-login" class="main" value="{string Premanager confirmLogin}" />
		</fieldset>
	{/form}
{/if}

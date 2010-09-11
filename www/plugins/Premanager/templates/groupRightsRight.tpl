{if $Premanager_Group_pluginName != $Premanager_Group_oldPluginName || $Premanager_Group_isFirst}
	{if !$Premanager_Group_isFirst}
		</dl>
	{/if}
	<dl>
		<dt><label>{string Premanager label array(label=html($Premanager_Group_pluginName))}</label></dt>
{/if}
<dd>       
	<div>   
		<label for="Premanager_Group_right_{$Premanager_Group_rightID}">
			<input type="checkbox" id="Premanager_Group_right_{$Premanager_Group_rightID}" name="Premanager_Group_right_{$Premanager_Group_rightID}"{if $Premanager_Group_rightChecked} checked="checked"{/if} />
			{html $Premanager_Group_rightTitle}
		</label>
	</div>
	{if $Premanager_Group_rightDescription}
		<p>{$Premanager_Group_rightDescription}</p>
	{/if}
</dd>   

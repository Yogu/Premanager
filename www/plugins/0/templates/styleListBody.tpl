{foreach $list style}
	<tr>
		<td>{html $style->getTitle()}</a></td>
		<td>{html $style->getAuthor()}</a></td>
		<td>{html $style->getDescription()}</a></td>
		<td><input type="checkbox" {if $style->isEnabled()} checked="checked"{/if} name="isEnabled[]" value="{$style->getID()}"></td>
		<td><input type="radio"{if $style->isDefault()} checked="checked"{/if} name="default" value="{$style->getID()}"></td>
		{if $notGuest}
			<td><input type="submit" name="select-{$style->getID()}" value="{string Premanager selectStyleButton}" title="{string Premanager selectStyleButtonTitle}" /></td>
		{/if}
	</tr>
{/foreach}
<li>
	<input type="radio" id="{$Editor_class}_{$Editor_field}_ml_{$EditorItem_class}" name="{$Editor_class}_{$Editor_field}_ml" value="{$EditorItem_class}"{if $EditorItem_class == $Editor_selectedML} checked="checked"{/if} />
	<label for="{$Editor_class}_{$Editor_field}_ml_{$EditorItem_class}">{html $EditorItem_title}</label>	
	<textarea id="{$Editor_class}_{$Editor_field}_txt_{$EditorItem_class}" name="{$Editor_class}_{$Editor_field}_txt_{$EditorItem_class}" class="fullsize{if $Editor_incorrect} error{/if}" cols="80" rows="5">{html $EditorItem_text}</textarea>
</li>
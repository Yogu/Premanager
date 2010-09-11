{extends "../../Premanager/templates/main.tpl"}

{block "content"}
	{if $message}
		<p>{$message}</p>
	{/if}
	{if $linkURL}						
		<p>
			&raquo;&nbsp;<a href="{$linkURL}">{$linkText}</a><br />
		</p>
	{/if}    
	<p>
		&raquo;&nbsp;<a href="./">{string Premanager goToHomepage}</a><br /> 
	</p>
{/block}
																				
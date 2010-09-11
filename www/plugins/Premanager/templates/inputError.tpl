{extends "../../Premanager/templates/main.tpl"}

{block "index"}noindex{/block}

{block "content"}
		{if $message}
			<p>{$message}</p>
		{else}
			<p>{string Premanager inputErrorMessage}</p>       
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
																				
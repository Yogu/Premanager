{extends "../../Premanager/templates/main.tpl"}

{block "title"}{string Premanager accessDenied}{/block}
{block "index"}noindex{/block}

{block "content"}
	<p>{string Premanager accessDeniedMessage}</p>
	{if $message}
		<p>{$message}</p>       
	{/if}
	<p>   
		{if $linkURL} 
			&raquo;&nbsp;<a href="{$linkURL}">{$linkText}</a><br />
		{/if}
		&raquo;&nbsp;<a href="./">{string Premanager goToHomepage}</a><br />
	</p>
{/block}

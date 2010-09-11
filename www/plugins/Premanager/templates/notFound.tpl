{extends "../../Premanager/templates/main.tpl"}

{block "index"}noindex{/block}

{block "content"}
	<p>{string Premanager pageNotFoundMessage}</p>
	{if $message}
		<p>{$message}</p>       
	{/if}
	{if $Client_referer == ""}
		<p>{string Premanager pageNotFoundNoRefererMessage}</p>  			
	{elseif $Client_refererIsInternal}			
		<p>{string Premanager pageNotFoundInternalRefererMessage}</p> 
	{else}
		<p>{string Premanager pageNotFoundExternalRefererMessage}</p>
	{/if}
	{if $linkURL}						
		<p>
			&raquo;&nbsp;<a href="{$linkURL}">{$linkText}</a><br />
		</p>
	{/if}         						
	{if $Nde_parentURL}
		<p>
			&raquo;&nbsp;<a href="./{$Nde_parentURL}">{string Premanager goToUpperPage}</a><br />
		</p>
	{/if}
	<p>
		&raquo;&nbsp;<a href="./">{string Premanager goToHomepage}</a><br /> 
	</p>
{/block}

<p>{string Premanager pageNotFoundMessage}</p>
{if $message}
	<p>{$message}</p>       
{/if}
{if !$refererExists}
	<p>{string Premanager pageNotFoundNoRefererMessage}</p>  			
{elseif $refererIsInternal}			
	<p>{string Premanager pageNotFoundInternalRefererMessage}</p> 
{else}
	<p>{string Premanager pageNotFoundExternalRefererMessage}</p>
{/if}
{if $linkURL}						
	<p>
		&raquo;&nbsp;<a href="{$linkURL}">{$linkText}</a><br />
	</p>
{/if}         						
{if $deepmostExistingNode}
	<p>
		&raquo;&nbsp;<a href="./{$deepmostExistingNode->url}">{string Premanager goToUpperPage}</a><br />
	</p>
{/if}
<p>
	&raquo;&nbsp;<a href="./">{string Premanager goToHomepage}</a><br /> 
</p>

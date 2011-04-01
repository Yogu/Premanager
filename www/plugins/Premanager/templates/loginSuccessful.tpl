<p>{string Premanager loginSuccessful array(userName=$environment->getUser()->getName())}</p>
{if $referer}
	<p>&raquo;&nbsp;<a href="{html $referer}">{string Premanager backToReferer}</a></p>
{else}
	<p>&raquo;&nbsp;<a href="./">{string Premanager goToHomepage}</a></p>
{/if}

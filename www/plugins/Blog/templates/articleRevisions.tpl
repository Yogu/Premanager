{extends "../../Premanager/templates/main.tpl"}

{block "toolbar"}
	<ul class="toolbar">                       
		<li><a href="{$Client_internalRequestURL}?edit" class="tool-edit-article" title="{string Blog editArticleDescription}">{string Blog editArticle}</a></li>
		<li><a href="{$Client_internalRequestURL}?revisions" class="tool-revisions active" title="{string Blog articleRevisionsDescription}">{string Blog articleRevisions}</a></li>
		{if $Me_right_Blog_publishRevisions && $Blog_Article_publishedRevisionID}
			<li><a href="./{$Client_internalRequestURL}?hide" class="tool-hide-article" title="{string Blog hideArticleDescription}">{string Blog hideArticle}</a></li>
		{/if}  
	</ul>	
{/block}

{block "content"}  
	{if $Blog_Article_revisions}
		<p>{string Blog articleRevisionsMessage}</p>
	{else}
		<p>{string Blog articleRevisionsEmptyMessage}</p>
	{/if}
{/block}

{block "after"}   
	{if $Blog_Article_revisions}
		<div class="block">
			<table>
				<thead>
					<tr>
						<th>{string Blog revisionColumn}</th>
						<th>{string Blog revisionTimeColumn}</th> 
						<th>{string Blog revisionCreatorColumn}</th>
						<th>{string Blog revisionSummaryColumn}</th>
					</tr>
				</thead>
				<tbody>
					{$Blog_Article_revisions}	
				</tbody>
			</table>
		</div>
	{/if}
{/block}
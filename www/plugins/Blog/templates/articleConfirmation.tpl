{extends "../../Premanager/templates/confirmation.tpl"}        

{block "toolbar"}
	{if $Me_right_Blog_editArticles}
		<ul class="toolbar">      
			<li><a href="./{$Client_internalRequestURL}?edit&rev={$Blog_Article_revision}" class="tool-edit-article" title="{string Blog editArticleDescription}">{string Blog editArticle}</a></li>
			<li><a href="./{$Client_internalRequestURL}?revisions" class="tool-revisions" title="{string Blog articleRevisionsDescription}">{string Blog articleRevisions}</a></li>    
			{if $Me_right_Blog_publishRevisions && $Blog_Article_publishedRevisionID}
				<li><a href="./{$Client_internalRequestURL}?hide" class="tool-hide-article{if $Blog_Article_mode == 'hide'} active{/if}" title="{string Blog hideArticleDescription}">{string Blog hideArticle}</a></li>
			{/if} 
			{if $Me_right_Blog_publishRevisions}
				{if $Blog_Article_revisionID == $Blog_Article_publishedRevisionID} 
					<li><span class="tool-publish-revision disabled" title="{string Blog publishRevisionActiveDescription}">{string Blog publishRevision}</span></li>
				{else} 
					<li><a href="{$Client_internalRequestURL}?publish&rev={$Blog_Article_revision}" class="tool-publish-revision{if $Blog_Article_mode == 'publish'} active{/if}" title="{string Blog publishRevisionDescription}">{string Blog publishRevision}</a></li>
				{/if}	
			{/if}
		</ul>	
	{/if}
{/block}
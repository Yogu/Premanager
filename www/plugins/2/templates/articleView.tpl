{extends "../../Premanager/templates/main.tpl"}        

{block "toolbar"}
	{if $Me_right_Blog_editArticles || $Me_right_Blog_publishRevisions || $Me_right_Blog_deleteArticles}
		<ul class="toolbar">   
			{if $Me_right_Blog_editArticles}   
				<li><a href="./{$Client_internalRequestURL}?edit&amp;rev={$Blog_Article_revision}" class="tool-edit-article" title="{string Blog editArticleDescription}">{string Blog editArticle}</a></li>
			{/if}
			{if $Me_right_Blog_editArticles ||$Me_right_Blog_publishRevisions}
				<li><a href="./{$Client_internalRequestURL}?revisions" class="tool-revisions" title="{string Blog articleRevisionsDescription}">{string Blog articleRevisions}</a></li>
			{/if}
			{if $Me_right_Blog_publishRevisions && $Blog_Article_publishedRevisionID}
				<li><a href="./{$Client_internalRequestURL}?hide" class="tool-hide-article" title="{string Blog hideArticleDescription}">{string Blog hideArticle}</a></li>
			{/if} 
			{if $Me_right_Blog_publishRevisions}
				{if $Blog_Article_revisionID == $Blog_Article_publishedRevisionID} 
					<li><span class="tool-publish-revision disabled" title="{string Blog publishRevisionActiveDescription}">{string Blog publishRevision}</span></li>
				{else} 
					<li><a href="{$Client_internalRequestURL}?publish&amp;rev={$Blog_Article_revision}" class="tool-publish-revision" title="{string Blog publishRevisionDescription}">{string Blog publishRevision}</a></li>
				{/if}	
			{/if}
			{if $Me_right_Blog_deleteArticles}
				<li><a href="./{$Client_internalRequestURL}?delete" class="tool-delete-article" title="{string Blog deleteArticleDescription}">{string Blog deleteArticle}</a></li>
			{/if} 
		</ul>	
	{/if}
{/block}

{block "before"}
	{if $Me_right_Blog_editArticles}
		<dl class="block">
			<dt>{string Blog revisionBlockTitle}</dt>
			<dd>                                     
				{if !$Blog_Article_publishedRevisionID}
					{if !$Blog_Article_lastRevisionID}   
						<p>{string Blog revisionBlockNoRevisionsMessage}</p>
					{elseif $Blog_Article_revisionID == $Blog_Article_lastRevisionID}
						<p>{string Blog revisionBlockNoPublishedRevisionMessage}</p>
					{else}
						<p>{string Blog revisionBlockNoPublishedRevisionRevisionSpecifiedMessage}</p>
					{/if}
				{else}
					{if $Blog_Article_revision < $Blog_Article_publishedRevision}
						<p>{string Blog revisionBlockOldRevisionMessage}</p>
					{elseif $Blog_Article_revision > $Blog_Article_publishedRevision}
						<p>{string Blog revisionBlockNewRevisionMessage}</p>
					{else}
						<p>{string Blog revisionBlockPublishedRevisionMessage}</p>
					{/if}
				{/if}
				
				{if $Blog_Article_revision}
					<div class="info-list">
						<dl>
							<dt>{string Premanager label array(label=string(Blog specifiedRevision))}</dt>
							<dd>{$Blog_Article_revision} {string Premanager brackets array(content=longDateTime($Blog_Article_revisionTime))}</dd>
						</dl>
						
						{if $Blog_Article_publishedRevision}
							<dl>
								<dt>{string Premanager label array(label=string(Blog publishedRevision))}</dt>
								<dd>{if $Blog_Article_publishedRevision != $Blog_Article_revision}<a href="./{$Client_internalRequestURL}?rev={$Blog_Article_publishedRevision}" title="{string Blog viewRevisionDescription}">{/if}{$Blog_Article_publishedRevision} {string Premanager brackets array(content=longDateTime($Blog_Article_publishedRevisionTime))}{if $Blog_Article_publishedRevision != $Blog_Article_revision}</a>{/if}</dd>
							</dl>
						{/if}
						
						{if $Blog_Article_lastRevision}
							<dl>
								<dt>{string Premanager label array(label=string(Blog lastRevision))}</dt>
								<dd>{if $Blog_Article_lastRevision != $Blog_Article_revision}<a href="./{$Client_internalRequestURL}?rev={$Blog_Article_lastRevision}" title="{string Blog viewRevisionDescription}">{/if}{$Blog_Article_lastRevision} {string Premanager brackets array(content=longDateTime($Blog_Article_lastRevisionTime))}{if $Blog_Article_lastRevision != $Blog_Article_revision}</a>{/if}</dd>
							</dl>
						{/if}
					</div>
				{/if}
			</dd>		
		</dl>	
	{/if}
{/block}

{block "content"}
	{if $Blog_Article_text}
		<div class="pml">{$Blog_Article_textHTML}</div>
	{/if}
{/block}

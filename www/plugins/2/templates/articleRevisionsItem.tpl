<tr{if $Blog_Revision_revisionID == $Blog_Article_publishedRevisionID} class="selected"{/if}>
	<td><a href="{$Client_internalRequestURL}?rev={$Blog_Revision_revision}" title="{string Blog viewRevisionDescription}">{$Blog_Revision_revision}</a></td>
	<td><a href="{$Client_internalRequestURL}?rev={$Blog_Revision_revision}" title="{string Blog viewRevisionDescription}">{longDateTime $Blog_Revision_createTime}</a></td>
	<td>
		<a href="./{treeURL Premanager Users}/{url $Blog_Revision_creatorName}">
			<span class="avatar"><img alt="{string Premanager avatarOf array(userName=$Blog_Revision_creatorName)}" src="./{treeURL Premanager Users}/{url $Blog_Revision_creatorName}/avatar" /></span>
			<span class="user-name user" style="color: #{$Blog_Revision_creatorColor};">{html $Blog_Revision_creatorName}</span>
			<span class="user-title">{html $Blog_Revision_creatorTitle}</span>
		</a>
	</td>
	<td>
		{if $Me_right_Blog_publishRevisions}
			<ul class="toolbar">
				{if $Blog_Revision_revisionID == $Blog_Article_publishedRevisionID} 
					<li><span class="tool-publish-revision active" title="{string Blog publishRevisionActiveDescription}">{string Blog publishRevision}</span></li>
				{else} 
					<li><a href="{$Client_internalRequestURL}?publish&rev={$Blog_Revision_revision}" class="tool-publish-revision" title="{string Blog publishRevisionDescription}">{string Blog publishRevision}</a></li>	
				{/if}	
			</ul>
		{/if}
	
		<span>{html $Blog_Revision_summary}</span>
	</td>
</tr>

{xml}

<response type="page">
	<title>{html $page->title}</title>
	<location>
		{foreach $hierarchy|reverse item}
			<node>
				<url>{html $item->url}</url>
				<title>{html $item->title}</title>
			</node>
		{/foreach}				
	</location>
	<project>
		<name>{html $pageNode->getProject()->getName()}</name>
		<title>{html $pageNode->getProject()->getTitle()}</title>
		<subtitle>{html $pageNode->getProject()->getSubTitle()}</subtitle>
	</project>
	<content>
		{foreach $page->blocks row}
			<row>
				{foreach $row col}
					<col>
						{foreach $col block}
							<block>
								<content>
									{$block->html}
								</content>
							</block>
						{/foreach}
					</col>
				{/foreach}
			</row>
		{/foreach}	
	</content>
</response>
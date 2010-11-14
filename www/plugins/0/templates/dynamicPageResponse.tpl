{xml}

<response type="page">
	<title>{html $page->title}</title>
	<location>
		{include file='backendNavigationItem.tpl' node=$navigationTree activeNode=$node}				
	</location>
	<project>
		<name>{html $node->getProject()->getName()}</name>
		<title>{html $node->getProject()->getTitle()}</title>
		<subtitle>{html $node->getProject()->getSubTitle()}</subtitle>
	</project>
	<content>
		{foreach $page->blocks row}
			<row>
				{foreach $row col}
					<col>
						{foreach $col block}
							<block>
								<content>
									{html $block->getHTML()}
								</content>
							</block>
						{/foreach}
					</col>
				{/foreach}
			</row>
		{/foreach}	
	</content>
	<timeinfo total="{html timeInfo(true)}">{html timeInfo()}</timeinfo>
</response>

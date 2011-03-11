<{* *}?xml version="1.0" encoding="utf-8" ?>
<!DOCTYPE html>

<html lang="{$environment->getlanguage()->getName()}">
	<head>
		<meta charset="utf-8" />
		<title>{html $project->getTitle()}{if !$isIndexPage}{string Premanager titleDivider}{html $title}{elseif $project->getSubTitle()}{string Premanager titleDivider}{html $project->getSubTitle()}{/if}</title>
		<base href="{html $environment->getURLPrefix()}" />
				
		<meta name="copyright" content="{html $organization->getCopyright()}" />
		<meta name="author" content="{html $project->getAuthor()}" />
		<meta name="publisher" content="{html $project->getAuthor()}" />
		<meta name="description" content="{html $project->getDescription()}" />
		{if $project->getkeywords()}
			<meta name="keywords" content="{html $project->getKeywords()}" />
		{elseif $organization->getkeywords()}
			<meta name="keywords" content="{html $organization->getKeywords()}" />
		{/if}
		
		<meta http-equiv="Content-Script-Type" content="text/javascript" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
		
		<link rel="canonical" title="{string Premanager canonical}" href="{html $canonicalURLPrefix}{html $node->getFullURL()}" />
		<link rel="top" title="{string Premanager home}" href="./" />
		<link rel="start" title="{string Premanager home}" href="./" />
		{if $node->getParent()} 
			<link rel="up" title="{string Premanager up}" href="./{html $node->getParent()->getURL()}" />
		{/if}
		{if $isList && $node->getPageCount() > 1}
			<link rel="first" title="{string Premanager pageX array(page=1)}" href="./{$node->getURLForPage(1)}" />
			<link rel="last" title="{string Premanager pageX array(page=$node->getPageCount())}" href="./{$node->getURLForPage($node->getPageCount())}" />
			{if $node->getPageIndex() > 1}
				<link rel="prev" title="{string Premanager pageX array(page=$node->getPageIndex()-1)}" href="./{$node->getURLForPage($node->getPageIndex()-1)}" />
			{/if}
			{if $node->getPageIndex() - $node->getPageCount()}
				<link rel="next" title="{string Premanager pageX array(page=$node->getPageIndex()+1)}" href="{$node->getURLForPage($node->getPageIndex()+1)}" />
			{/if}
		{/if}
		
		{if $environment->getEdition() == 1} {* mobile *}
			<link rel="alternate" media="screen" type="text/html" href="{urlPrefix null ''}{$node->getFullURL()}" />
			<link rel="alternate" media="print" type="text/html" href="{urlPrefix null 'print'}{$node->getFullURL()}" />
		{elseif $environment->getEdition() == 2} {* print *}
			<link rel="alternate" media="screen" type="text/html" href="{urlPrefix null ''}{$node->getFullURL()}" />
			<link rel="alternate" media="handheld" type="text/html" href="{urlPrefix null 'mobile'}{$node->getFullURL()}" />
		{else}
			<link rel="alternate" media="handheld" type="text/html" href="{urlPrefix null 'mobile'}{$node->getFullURL()}" />
			<link rel="alternate" media="print" type="text/html" href="{urlPrefix null 'print'}{$node->getFullURL()}" />
		{/if}
		     		
		<link rel="shortcut icon" title="Shortcut Icon" href="{html $staticURLPrefix}Premanager/images/icon-16x16.png" />
		
		{foreach $environment->getStyle()->getStylesheets('page') as stylesheet}
			{if $environment->getEdition() == 1} {* mobile *}
				{if $stylesheet->getMedia() != "screen"} 
					<link rel="stylesheet" type="text/css" href="{html $stylesheet->getURL()}"{if $stylesheet->getMedia() == 'handheld'} media="handheld, screen"{elseif $stylesheet->getMedia() != "all"} media="{html $stylesheet->getMedia()}"{/if} />
				{/if}
			{elseif $environment->getEdition() == 2} {* print *}
				{if $stylesheet->getMedia() != "screen"} 
					<link rel="stylesheet" type="text/css" href="{html $stylesheet->getURL()}"{if $stylesheet->getMedia() != 'print' && $stylesheet->getMedia() != "all"} media="{html $stylesheet->getMedia()}"{/if} />
				{/if}
			{else}
				<link rel="stylesheet" type="text/css" href="{html $stylesheet->getURL()}" media="{html $stylesheet->getMedia()}" />
			{/if}
		{/foreach}
		{foreach $stylesheets stylesheet}
			<link rel="stylesheet" type="text/css" href="{html $staticURLPrefix}{html $stylesheet}" media="all" />
		{/foreach}
		                       
		<script type="text/javascript">var Config = \{emptyURLPrefix:'{escape $emptyURLPrefix}', staticURLPrefix:'{escape $staticURLPrefix}'};</script>                                                                                                      
		<script type="text/javascript" src="{$staticURLPrefix}Premanager/scripts/prototype.js"></script>
		<script type="text/javascript" src="{$staticURLPrefix}Premanager/scripts/modernizr-1.6.min.js"></script>
		<script type="text/javascript" src="{$staticURLPrefix}Premanager/scripts/tools.js"></script>
		<script type="text/javascript" src="{$staticURLPrefix}Premanager/scripts/window.js"></script>
		<script type="text/javascript" src="{$staticURLPrefix}Premanager/scripts/smart-pageload.js"></script>
		<script type="text/javascript" src="{$staticURLPrefix}Premanager/thirdparty/jscolor/jscolor.js"></script>
		{$head}
	</head>
	
	<body class="page{if $sidebar} has-sidebar{/if}">
		{$top}
		
		<header id="header">
			<hgroup>
				<h1 id="organization-heading"><a href="./">{html $organization->getTitle()}</a></h1>
				{if $project->getid() != 0}
					<h2 id="project-heading"><a href="./{html $project->getname()}">{html $project->getTitle()}</a></h2>
				{/if}
			</hgroup>
		</header>
		
		{$afterHeader}
		
		<nav id="navbar">
			<ul>                         
				{foreach $hierarchy|reverse item}
					<li><a href="./{html $item->getURL()}">{html $item->getTitle()}</a></li>
				{/foreach}			
			</ul>		
		</nav>
		
		{$afterNavigation}
			
		{if $isList && $node->getPageCount() > 1}
			<div id="pagination">
				{if $node->getPageIndex() > 1}
					<a href="./{$node->getURLForPage($node->getPageIndex()-1)}" class="page-back">{string Premanager pageBack}</a>
				{/if}
				
				<span class="pages">
					{if $node->getPageIndex() > 1}<a href="./{$node->getURLForPage(1)}">1</a>{/if}
					{if $node->getPageIndex() > 2}<a href="./{$node->getURLForPage(2)}">2</a>{/if}
					{if $node->getPageIndex() > 3}<a href="./{$node->getURLForPage(3)}">3</a>{/if}
					
					{if $node->getPageIndex() > 5}<span class="gap">{string Premanager literalGap}</span>{/if}

            {if $node->getPageIndex() > 5 && $node->getPageIndex() == $node->getPageCount()}<a href="./{$node->getURLForPage($node->getPageIndex()-2)}">{$node->getPageIndex()-2}</a>{/if} 
					{if $node->getPageIndex() > 4}<a href="./{$node->getURLForPage($node->getPageIndex()-1)}">{$node->getPageIndex()-2}</a>{/if}
					
					<span class="current">{$node->getPageIndex()}</span>
					
					{if $node->getPageIndex() < $node->getPageCount()-3}<a href="./{$node->getURLForPage($node->getPageIndex()+1)}">{$List_page+1}{/if}
					
					{if $node->getPageIndex() < $node->getPageCount()-4}{string Premanager literalGap}</span>{/if}
					
					{if $node->getPageIndex() < $node->getPageCount()-2}<a href="./{$node->getURLForPage($node->getPageCount()-2)}">{$node->getPageCount()-2}</a>{/if}
					{if $node->getPageIndex() < $node->getPageCount()-1}<a href="./{$node->getURLForPage($node->getPageCount()-1)}">{$node->getPageCount()-1}</a>{/if}
					{if $node->getPageIndex() < $node->getPageCount()}<a href="./{$node->getURLForPage($node->getPageCount())}">{$node->getPageCount()}</a>{/if}
				</span>

				{if $node->getPageIndex() < $node->getPageCount()}
					<a href="./{$node->getURLForPage($node->getPageCount())}" class="page-forward">{string Premanager pageForward}</a>
				{/if}
			</div>			
		{/if}
		
		{$afterPagination}
		
		{if count($toolbar)}
			<ul class="toolbar" id="toolbar">
				{foreach $toolbar item}
					{$item->getHTML()}
				{/foreach}
			</ul>
		{/if}
		
		{$afterToolbar}

		<nav id="navigation-tree">
			<ul class="tree">
				{include file='navigationItem.tpl' node=$navigationTree activeNode=$node}		
			</ul>
		</nav>
		
		{$afterNavigationTree} 

		<div id="content">
			{$contentTop}
		
			{if $log}
				<section class="block" id="log">
					<header>
						<h1>Log</h1>
					</header>
					<div>
						<ul class="list">
							{foreach $log item}
							  {$message = $item->getMessage()}
								<li{if (strpos($message, "\n"))} class="multi-line"{/if}>
									<span>{html $item->getfullFunctionName()} ({html(basename($item->getfileName()))}:{html $item->getline()})</span>
									<span>{$message}</span>
								</li>
							{/foreach}
						</ul>
					</div>
				</section>
			{/if}
				
			{$afterLog}
			
			{foreach $blocks row}
				<div class="block-row">
					{foreach $row col}
						<div class="block-col">
							{foreach $col block}
								{$block->getHTML()}
							{/foreach}
						</div>
					{/foreach}
				</div>
			{/foreach}
			
			{$contentBottom}
		</div>
		
		{$beforeFooter}
		
		<footer id="footer">
			<p>{html $organization->getCopyright()}</p>
			<p><a href="{html $version['URL']}">Premanager <span title="{html $version['Date']}">{html $version['Version']}</span> &copy; {html $version['Copyright']}.</a></p>
			{*<nav id="footlinks">
				<ul>
					<li><a href="./info/impressum">Impressum</a></li>
					<li><a href="./info/kontakt">Kontakt</a></li>
				</ul>			
			</nav>*}
			<p id="footer-time-info">{timeInfo()}</p>
		</footer>
		
		{$bottom}
	</body>
</html>

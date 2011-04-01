<<?php ?>?xml version="1.0" encoding="utf-8" ?>
<!DOCTYPE html>

<html lang="{$environment->getLanguage()->getName()}">
	<head>
		<meta charset="utf-8" />
		<title>{html $project->getTitle()}{if !$isIndexPage}{string Premanager titleDivider}{html $node->getTitle()}{elseif $project->getSubTitle()}{string Premanager titleDivider}{html $project->getSubTitle()}{/if}</title>
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
		     		
		<link rel="shortcut icon" title="Shortcut Icon" href="{html $staticURLPrefix}Premanager/images/icon-16x16.png" />
		
		{foreach $environment->getStyle()->getStylesheets('page') as stylesheet}
			<link rel="stylesheet" type="text/css" href="{html $stylesheet->getURL()}"{if $stylesheet->getmedia() != 'all'} media="{html $stylesheet->getMedia()}"{/if} />
		{/foreach}
	</head>
	
	<body class="simple">
		{$content}
	</body>
</html>

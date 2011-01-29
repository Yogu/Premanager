<{* *}?xml version="1.0" encoding="utf-8" ?>
<!DOCTYPE html>

<html lang="{$environment->getLanguage()->getName()}">
	<head>
		<meta charset="utf-8" />
		<title>{html $organization->getTitle()}{string Premanager titleDivider}{html $title}</title>
		<base href="{html $environment->getURLPrefix()}" />
				
		<meta name="copyright" content="{html $organization->getCopyright()}" />
		
		<meta http-equiv="Content-Script-Type" content="text/javascript" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
		     		
		<link rel="shortcut icon" title="Shortcut Icon" href="{html $staticURLPrefix}Premanager/images/icon-16x16.png" />
		
		{$stylesheetNumber = 0}
		{foreach $environment->getStyle()->getStylesheets() as stylesheet}
			<link rel="stylesheet" type="text/css" href="cid:stylesheet-{$stylesheetNumber++}@premanager" media="{html $stylesheet->getMedia()}" />
		{/foreach}
	</head>
	
	<body>
		<header id="header">
			<hgroup>
				<h1 id="organization-heading"><a href="./">{html $organization->getTitle()}</a></h1>
			</hgroup>
		</header> 

		<div id="content">
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
		</div>
		
		<footer id="footer">
			<p>{html $organization->getCopyright()}</p>
			<p><a href="{html $version['URL']}">Premanager <span title="{html $version['Date']}">{html $version['Version']}</span> &copy; {html $version['Copyright']}.</a></p>
			{*<nav id="footlinks">
				<ul>
					<li><a href="./info/impressum">Impressum</a></li>
					<li><a href="./info/kontakt">Kontakt</a></li>
				</ul>			
			</nav>*}
		</footer>
	</body>
</html>

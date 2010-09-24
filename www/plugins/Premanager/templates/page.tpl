<<?php ?>?xml version="1.0" encoding="utf-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>{html $project->title}{if !$isIndexPage}{string Premanager titleDivider}{html $node->standAloneTitle}{elseif $project->subTitle}{string Premanager titleDivider}{$project->subTitle}{/if}</title>
		<base href="{html $environment->urlPrefix}" />
				
		<meta name="copyright" content="{html $organization->copyright}" />
		<meta name="author" content="{html $project->author}" />
		<meta name="publisher" content="{html $project->author}" />
		<meta name="description" content="{html $project->description}" />
		{if $project->keywords}
			<meta name="keywords" content="{html $project->keywords}" />
		{elseif $organization->keywords}
			<meta name="keywords" content="{html $organization->keywords}" />
		{/if}
		
		<meta http-equiv="Content-Script-Type" content="text/javascript" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
		
		<link rel="canonical" title="{string Premanager canonical}" href="{$canonicalURLPrefix}{$node->fullURL}" />
		<link rel="top" title="{string Premanager home}" href="./" />
		<link rel="start" title="{string Premanager home}" href="./" />
		{if $node->parent} 
			<link rel="up" title="{string Premanager up}" href="./{$node->parent->url}" />
		{/if}
		     		
		<link rel="shortcut icon" title="Shortcut Icon" href="{$staticURLPrefix}Premanager/images/icon-16x16.png" />
		
		{foreach $environment->style->getStylesheets() as stylesheet}
			<link rel="stylesheet" type="text/css" href="{html $stylesheet->url}"{if $stylesheet->media != 'all'} media="{html $stylesheet->media}"{/if} />
		{/foreach}
		
		{*
		TODO: move this code to somewhere it is used
		{if $List_pageCount > 1}
			<link rel="first" title="{string Premanager pageX array(page=1)}" href="./{$List_firstPageURL}" />
			<link rel="last" title="{string Premanager pageX array(page=$List_pageCount)}" href="./{$List_baseURL}{$List_pageCount}" />
			{if $List_page > 1}
				<link rel="prev" title="{string Premanager pageX array(page=$List_page-1)}" href="{if $List_page-1 > 1}{$List_baseURL}{$List_page-1}{else}{$List_firstPageURL}{/if}" />
			{/if}
			{if $List_page < $List_pageCount}
				<link rel="next" title="{string Premanager pageX array(page=$List_page+1)}" href="{$List_baseURL}/{$List_page+1}" />
			{/if}
		{/if}
		*}
		                       
		<script type="text/javascript">var Config = \{staticURLPrefix:'{escape $staticURLPrefix}'};</script>                                                                                                      
		<script type="text/javascript" src="{$staticURLPrefix}Premanager/scripts/prototype.js"></script>
		<script type="text/javascript" src="{$staticURLPrefix}Premanager/scripts/tools.js"></script>
		<script type="text/javascript" src="{$staticURLPrefix}Premanager/scripts/window.js"></script>
		
		{$head}
	</head>
	
	<body lang="{$environment->language->name}">
		<div id="header">
			<div id="organization-title">
				<span class="text"><a href="./">{html $organization->title}</a></span>
			</div>
			
			{if $project->id != 0}
				<div id="project-title">
					<span class="text"><a href="./{html $project->name}">{html $project->title}</a></span>
				</div>
			{/if}
		</div>

		<div class="navigation">
			<ul class="location">
				{foreach $hierarchy|reverse item}
					<li><a href="./{html $item->url}">{html $item->title}</a></li>
				{/foreach}
			</ul>
			
			{*
			TODO: move this to where it works
			{if $List_pageCount > 1}
				<div class="pagination">
					{if $List_page > 1}
						<a href="./{if $List_page-1 > 1}{$List_baseURL}{$List_page-1}{else}{$List_firstPageURL}{/if}" class="page-back">{string Premanager pageBack}</a>
					{/if}
					
					<span class="pages">
						{if $List_page > 1}<a href="./{$List_firstPageURL}">1</a>{/if}
						{if $List_page > 2}<a href="./{$List_baseURL}2">2</a>{/if}
						{if $List_page > 3}<a href="./{$List_baseURL}3">3</a>{/if}
						
						{if $List_page > 5}<span class="gap">{string Premanager literalGap}</span>{/if}

            {if $List_page > 5 && $List_page == $List_pageCount}<a href="./{$List_baseURL}{$List_page-2}">{$List_page-2}</a>{/if} 
						{if $List_page > 4}<a href="./{$List_baseURL}{$List_page-1}">{$List_page-1}</a>{/if}
						
						<span class="current">{$List_page}</span>
						
						{if $List_page < $List_pageCount-3}<a href="./{$List_baseURL}{$List_page+1}">{$List_page+1}{/if}
						
						{if $List_page < $List_pageCount-4}{string Premanager literalGap}</span>{/if}
						
						{if $List_page < $List_pageCount-2}<a href="./{$List_baseURL}{$List_pageCount-2}">{$List_pageCount-2}</a>{/if}
						{if $List_page < $List_pageCount-1}<a href="./{$List_baseURL}{$List_pageCount-1}">{$List_pageCount-1}</a>{/if}
						{if $List_page < $List_pageCount}<a href="./{$List_baseURL}{$List_pageCount}">{$List_pageCount}</a>{/if}
					</span>

					{if $List_page < $List_pageCount}
						<a href="./{$List_baseURL}{$List_page+1}" class="page-forward">{string Premanager pageForward}</a>
					{/if}
				</div>			
			{/if}
			*}
		</div>
		
		{$toolbar}

		<div id="content" class="{if $sidebar}with-sidebar {/if}with-navigation-tree">
			{$global}
		
			{if $log}
				<dl class="block" id="log">
					<dt>Log</dt>
					<dd>
						<ul class="list">
							{foreach $log item}
								<li{if strpos($item->message, "\n") !== false} class="multi-line"{/if}>
									<span>{$item->fullFunctionName} ({html(basename($item->fileName))}:{$item->line})</span>
									<span>{$item->message}</span>
								</li>
							{/foreach}
						</ul>
					</dd>
				</dl>
			{/if}
			
			{foreach $blocks row}
				<div class="block-row">
					{foreach $row col}
						<div class="block-col">
							{foreach $col block}
								{$block->html}
							{/foreach}
						</div>
					{/foreach}
				</div>
			{/foreach}
		</div>

		<ul id="navigation-tree">
			<li>dummy</li>		
		</ul>
		
		{if $sidebar}
			<div id="sidebar">
				{$sidebar}
			</div>
		{/if} 
	</body>
</html>

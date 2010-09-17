<<?php ?>?xml version="1.0" encoding="utf-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>{html $Prj_title}{if $Nde_url}{string Premanager titleDivider}{$Nde_standAloneTitle}{elseif $Prj_subTitle}{string Premanager titleDivider}{$Prj_subTitle}{/if}</title>
		<base href="{$Config_urlPrefix}" />
				
		<meta name="copyright" content="{html $Org_copyright}" />
		<meta name="author" content="{html $Prj_author}" />
		<meta name="publisher" content="{html $Prj_author}" />
		<meta name="description" content="{html $Prj_description}" />
		{if $Prj_keywords}
			<meta name="keywords" content="{html $Prj_keywords}" />
		{elseif $Org_keywords}
			<meta name="keywords" content="{html $Org_keywords}" />
		{/if}
		
		<meta http-equiv="Content-Script-Type" content="text/javascript" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
		
		<link rel="canonical" title="{string Premanager canonical}" href="{$Config_commonURLPrefix}{if $List_page > 1}{$List_baseURL}{$List_page}{elseif $List_page}{$List_firstPageURL}{else}{$Nde_url}{/if}" />
		<link rel="top" title="{string Premanager home}" href="./" />
		<link rel="start" title="{string Premanager home}" href="./" />
		<link rel="up" title="{string Premanager up}" href="./{$Nde_parentURL}" />
		     		
		<link rel="shortcut icon" title="Shortcut Icon" href="{$Config_staticURLPrefix}Premanager/images/icon-16x16.png" />
		
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
		                       
		<script type="text/javascript">var Config = \{staticURLPrefix:'{escape $Config_staticURLPrefix}'};</script>                                                                                                      
		<script type="text/javascript" src="{$Config_staticURLPrefix}Premanager/scripts/prototype.js"></script>
		<script type="text/javascript" src="{$Config_staticURLPrefix}Premanager/scripts/tools.js"></script>
		<script type="text/javascript" src="{$Config_staticURLPrefix}Premanager/scripts/window.js"></script>
		{$Premanager_themeHead}
		{block "head"}{/block}
	</head>
	
	<body lang="{$Lang_code}">
		<div id="header">
			<div id="organization-title">
				<span class="text"><a href="http://{urlTemplate(null, null, '')}">{html $Org_title}</a></span>
			</div>
			
			{if $Prj_id != 0}
				<div id="project-title">
					<span class="text"><a href="./">{html $Prj_title}</a></span>
				</div>
			{/if}
		</div>

		<div class="navigation">
			<ul class="location">
				<li><a href="http://{urlTemplate(null, null, '')}">{html $Org_title}</a></li>
				{if $Prj_id != 0}
					<li><a href="./">{html $Prj_title}</a></li>
				{/if}
				{$Premanager_navigationList}
			</ul>
			
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
		</div>
		
		{block "toolbar"}{/block}

		<div id="content"{if $Premanager_sidebar && $Premanager_navigationTree} class="with-sidebar with-navigation-tree"{elseif $Premanager_sidebar} class="with-sidebar"{elseif $Premanager_navigationTree} class="with-navigation-tree"{/if}>
			{$Premanager_globalArea}
		
			{if $Premanager_debug && $Premanager_log}
				<dl class="block" id="log">
					<dt>Log</dt>
					<dd>
						<ul class="list">{$Premanager_log}</ul>
					</dd>
				</dl>
			{/if}
		                 
			{$Premanager_beforeContent}     
			{block "before"}{/block}
		
			<dl class="block" id="main-block">
				<dt>{html $Nde_standAloneTitle}</dt>
				<dd>
					{block "content"}{/block}				
				</dd>
			</dl>
			
			{block "after"}{/block}		
			{$Premanager_afterContent}		
		</div>
		
		{if $Premanager_navigationTree}
			<ul id="navigation-tree">
				{$Premanager_navigationTree}		
			</ul>
		{/if}
		
		{if $Premanager_sidebar}
			<div id="sidebar">
				{$Premanager_sidebar}
			</div>
		{/if} 
	</body>
</html>

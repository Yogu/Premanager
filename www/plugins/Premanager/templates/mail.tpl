--{$Mail_bodyBoundary}
Content-Type: text/plain; charset="utf-8"; format=flowed
Content-Transfer-Encoding: 8bit

{block "plain"}{/block}

--{$Mail_bodyBoundary}
Content-Type: multipart/related; boundary="{$Mail_htmlBoundary}"


--{$Mail_htmlBoundary}
Content-Type: text/html; charset="utf-8"
Content-Transfer-Encoding: 8bit

<<?php ?>?xml version="1.0" encoding="utf-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>{html $Org_title}{string Premanager titleDivider}{html $Mail_subject}</title>
		<base href="{urlTemplate(null, null, '')}" />	

		<meta http-equiv="Content-Script-Type" content="text/javascript" />
		<meta http-equiv="Content-Style-Type" content="text/css" />

		<link rel="top" title="{string Premanager home}" href="./" />
		<link rel="start" title="{string Premanager home}" href="./" />

		{$Mail_themeHead}
		{block "head"}{/block}
	</head>

	<body lang="{$Lang_code}">
		<div id="header">
			<div id="organization-title">
				<span class="text"><a href="http://{urlTemplate(null, null, '')}">{html $Org_title}</a></span>
			</div>
			
			{if $Prj_id}
				<div id="project-title">
					<span class="text"><a href="http://{urlTemplate(null, null, $Prj_name)}">{html $Prj_title}</a></span>
				</div>
			{/if}
		</div>

		<div id="content">
			{block "before"}{/block}

			<dl class="block" id="main-block">
				<dt>{html $Mail_title}</dt>
				<dd>
					{block "content"}{/block}				
				</dd>
			</dl>
			
			{block "after"}{/block}			
		</div>
	</body>
</html>

{extends "../../Premanager/templates/main.tpl"}

{block "head"}
	<script type="text/javascript" src="{$Config_staticURLPrefix}Premanager/scripts/wysiwyg.js"></script>
{/block}     

{block "before"}  
	{if $Blog_Article_preview}
		<dl class="block">
			<dt>{string Blog preview}</dt>
			<dd>
				<div class="pml">{$Blog_Article_textHTML}</div>
			</dd>
		</dl>
	{/if}
{/block}

{block "content"}
	<form action="{html $Client_requestURL}" method="post">
		{if $Blog_Article_inputErrors}
			<ul class="input-errors">
				{$Blog_Article_inputErrors}	
			</ul>
		{/if}   
	
		<fieldset class="inputs">
			<dl>
				<dt><label for="Blog_Article_title">{string Premanager label array(label=string(Blog articleTitle))}</label></dt>
				<dd>
					<input type="text" name="Blog_Article_title" id="Blog_Article_title" value="{html $Blog_Article_formTitle}" class="fullsize{if $Blog_Article_title_incorrect} error{/if}" />
					<p>{string Blog articleTitleDescription}</p>
				</dd>
			</dl>
			       
			<dl>
				<dt><label for="Blog_Article_text">{string Premanager label array(label=string(Blog articleText))}</label></dt>
				<dd>
					<ul class="editor">
						{$Blog_Article_editorContent}
					</ul>
					<p>{string Blog articleTextDescription}</p>
				</dd>
			</dl>     
			
			<dl>
				<dt><label for="Blog_Article_summary">{string Premanager label array(label=string(Blog summaryLabel))}</label></dt>
				<dd>
					<input type="text" name="Blog_Article_summary" id="Blog_Article_summary" value="{html $Blog_Article_summary}" class="fullsize{if $Blog_Article_summary_incorrect} error{/if}" />
					<p>{if $Blog_Article_mode == 'add'}{string Blog summaryCreatedDescription}{else}{string Blog summaryDescription}{/if}</p>
				</dd>
			</dl>
		</fieldset>
		
		<fieldset class="buttons">
			<input type="submit" name="Blog_Article_submit" class="main" value="{string Premanager submitButton}" />
			<input type="submit" name="Blog_Article_preview" value="{string Premanager previewButton}" />
		</fieldset>
	</form>
{/block}
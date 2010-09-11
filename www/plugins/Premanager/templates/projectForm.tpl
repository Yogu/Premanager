{extends "../../Premanager/templates/main.tpl"}     

{block "content"}
	<form action="{html $Client_requestURL}" method="post">
		{if $Premanager_ProjectNode_inputErrors}
			<ul class="input-errors">
				{$Premanager_ProjectNode_inputErrors}	
			</ul>
		{/if}   
	
		<fieldset class="inputs">
			<dl>
				<dt><label for="Premanager_ProjectNode_title">{string Premanager label array(label=string(Premanager projectTitle))}</label></dt>
				<dd>
					<input type="text" name="Premanager_ProjectNode_title" id="Premanager_ProjectNode_title" value="{html $Premanager_ProjectNode_title}"{if $Premanager_ProjectNode_title_incorrect} class="error"{/if} />
					<p>{string Premanager projectTitleDescription}</p>
				</dd>
			</dl>   
			
			{if $Premanager_ProjectNode_mode == 'add' || $Premanager_ProjectNode_id}
				<dl>
					<dt><label for="Premanager_ProjectNode_name">{string Premanager label array(label=string(Premanager projectName))}</label></dt>
					<dd>
						<input type="text" name="Premanager_ProjectNode_name" id="Premanager_ProjectNode_name" value="{html $Premanager_ProjectNode_name}"{if $Premanager_ProjectNode_name_incorrect} class="error"{/if} />
						<p>{string Premanager projectNameDescription}</p>
					</dd>
				</dl>
			{/if} 
			
			<dl>
				<dt><label for="Premanager_ProjectNode_subTitle">{string Premanager label array(label=string(Premanager projectSubTitle))}</label></dt>
				<dd>
					<input type="text" name="Premanager_ProjectNode_subTitle" id="Premanager_ProjectNode_subTitle" value="{html $Premanager_ProjectNode_subTitle}"{if $Premanager_ProjectNode_subTitle_incorrect} class="error"{/if} />
					<p>{string Premanager projectSubTitleDescription}</p>
				</dd>
			</dl>       
			
			<dl>
				<dt><label for="Premanager_ProjectNode_author">{string Premanager label array(label=string(Premanager projectAuthor))}</label></dt>
				<dd>
					<input type="text" name="Premanager_ProjectNode_author" id="Premanager_ProjectNode_author" value="{html $Premanager_ProjectNode_author}"{if $Premanager_ProjectNode_author_incorrect} class="error"{/if} />
					<p>{string Premanager projectAuthorDescription}</p>
				</dd>
			</dl>        
			
			<dl>
				<dt><label for="Premanager_ProjectNode_copyright">{string Premanager label array(label=string(Premanager projectCopyright))}</label></dt>
				<dd>
					<input type="text" name="Premanager_ProjectNode_copyright" id="Premanager_ProjectNode_copyright" value="{html $Premanager_ProjectNode_copyright}"{if $Premanager_ProjectNode_copyright_incorrect} class="error"{/if} />
					<p>{string Premanager projectCopyrightDescription}</p>
				</dd>
			</dl>  
			
			<dl>
				<dt><label for="Premanager_ProjectNode_description">{string Premanager label array(label=string(Premanager projectDescription))}</label></dt>
				<dd>
					<textarea name="Premanager_ProjectNode_description" id="Premanager_ProjectNode_description"{if $Premanager_ProjectNode_description_incorrect} class="error"{/if}>{html $Premanager_ProjectNode_description}</textarea>
					<p>{string Premanager projectDescriptionDescription}</p>
				</dd>
			</dl>    
			
			<dl>
				<dt><label for="Premanager_ProjectNode_keywords">{string Premanager label array(label=string(Premanager projectKeywords))}</label></dt>
				<dd>
					<textarea name="Premanager_ProjectNode_keywords" id="Premanager_ProjectNode_keywords"{if $Premanager_ProjectNode_keywords_incorrect} class="error"{/if}>{html $Premanager_ProjectNode_keywords}</textarea>
					<p>{string Premanager projectKeywordsDescription}</p>
				</dd>
			</dl> 
		</fieldset>
		
		<fieldset class="buttons">
			<input type="submit" name="Premanager_ProjectNode_form" class="main" value="{string Premanager submitButton}" />
		</fieldset>
	</form>
{/block}

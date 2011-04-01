{form showErrors=true}
	<fieldset class="inputs">
		{formElement
			name="title"
			label=string(Premanager projectTitle)
			description=string(Premanager projectTitleDescription)
			type=text
		}
		
		{if !$project || $project->getID()}
			{formElement
				name="name"
				label=string(Premanager projectName)
				description=string(Premanager projectNameDescription)
				type=text
			}
		{/if}
		
		{formElement
			name="subTitle"
			label=string(Premanager projectSubTitle)
			description=string(Premanager projectSubTitleDescription)
			type=text
		}
		
		{formElement
			name="author"
			label=string(Premanager projectAuthor)
			description=string(Premanager projectAuthorDescription)
			type=text
		}
		
		{formElement
			name="copyright"
			label=string(Premanager projectCopyright)
			description=string(Premanager projectCopyrightDescription)
			type=text
		}
		
		{formElement
			name="description"
			label=string(Premanager projectDescription)
			description=string(Premanager projectDescriptionDescription)
			type=textarea
		}
		
		{formElement
			name="keywords"
			label=string(Premanager projectKeywords)
			description=string(Premanager projectKeywordsDescription)
			type=text
		}
	</fieldset>
	
	<fieldset class="buttons">
		<input type="submit" name="submit" class="main" value="{string Premanager submitButton}" />
	</fieldset>
{/form}

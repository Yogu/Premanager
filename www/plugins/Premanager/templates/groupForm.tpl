{extends "../../Premanager/templates/main.tpl"}     

{block "content"}
	<form action="{html $Client_requestURL}" method="post">
		{if $Premanager_Group_inputErrors}
			<ul class="input-errors">
				{$Premanager_Group_inputErrors}	
			</ul>
		{/if}   
	
		<fieldset class="inputs">
			<dl>
				<dt><label for="Premanager_Group_name">{string Premanager label array(label=string(Premanager groupName))}</label></dt>
				<dd>
					<input type="text" name="Premanager_Group_name" id="Premanager_Group_name" value="{html $Premanager_Group_formName}"{if $Premanager_Group_name_incorrect} class="error"{/if} />
					<p>{string Premanager groupNameDescription}</p>
				</dd>
			</dl> 
			
			<dl>
				<dt><label for="Premanager_Group_title">{string Premanager label array(label=string(Premanager groupTitle))}</label></dt>
				<dd>
					<input type="text" name="Premanager_Group_title" id="Premanager_Group_title" value="{html $Premanager_Group_title}"{if $Premanager_Group_title_incorrect} class="error"{/if} />
					<p>{string Premanager groupTitleDescription}</p>
				</dd>
			</dl>      
			
			<dl>
				<dt><label for="Premanager_Group_color">{string Premanager label array(label=string(Premanager groupColor))}</label></dt>
				<dd>
					<input type="text" name="Premanager_Group_color" id="Premanager_Group_color" value="{html $Premanager_Group_color}" class="small {if $Premanager_Group_color_incorrect} error{/if}" />
					<p>{string Premanager groupColorDescription}</p>
				</dd>
			</dl>        
			
			{if $Me_right_Premanager_setGroupPriority}
				<dl>
					<dt><label for="Premanager_Group_priority">{string Premanager label array(label=string(Premanager groupPriority))}</label></dt>
					<dd>
						<input type="text" name="Premanager_Group_priority" id="Premanager_Group_priority" value="{html $Premanager_Group_priority}" class="small {if $Premanager_Group_priority_incorrect} error{/if}" />
						<p>{string Premanager groupPriorityDescription}</p>
					</dd>
				</dl>  
			{/if}
			
			<dl>
				<dt><label for="Premanager_Group_text">{string Premanager label array(label=string(Premanager groupText))}</label></dt>
				<dd>
					<textarea name="Premanager_Group_text" id="Premanager_Group_text"{if $Premanager_Group_text_incorrect} class="error"{/if}>{html $Premanager_Group_text}</textarea>
					<p>{string Premanager groupTextDescription}</p>
				</dd>
			</dl> 
			     			
			{if $Me_right_Premanager_manageUsers}				
				<dl>
					<dt><label for="Premanager_Group_autoJoin">{string Premanager label array(label=string(Premanager groupAutoJoinShortLabel))}</label></dt>
					<dd>       
						<div>   
							<label for="Premanager_Group_autoJoin">
							<input type="checkbox" id="Premanager_Group_autoJoin" name="Premanager_Group_autoJoin"{if $Premanager_Group_autoJoin} checked="checked"{/if} />
								{string Premanager groupAutoJoinLabel}
							</label>
						</div>  
					</dd>  
				</dl>
			{/if}
		</fieldset>
		
		<fieldset class="buttons">
			<input type="submit" name="Premanager_Group_form" class="main" value="{string Premanager submitButton}" />
		</fieldset>
	</form>
{/block}

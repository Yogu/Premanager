{extends "../../Premanager/templates/main.tpl"}

{block "content"}
	<form action="{$Client_requestURL}" method="post" enctype="multipart/form-data">
		{if $Premanager_ChangeAvatar_inputErrors}
			<ul class="input-errors">
				{$Premanager_ChangeAvatar_inputErrors}	
			</ul>
		{/if}
		
		{if $Premanager_ChangeAvatar_hasAvatar}
			{if $Premanager_ChangeAvatar_userID == $Me_id}
				<p>{string Premanager changeAvatarMessageOwnExisting}</p>
			{else}                                                     
				<p>{string Premanager changeAvatarMessageForeignExisting array(userName=$Premanager_ChangeAvatar_userName)}</p>
			{/if}
		{else}
			{if $Premanager_ChangeAvatar_userID == $Me_id}
				<p>{string Premanager changeAvatarMessageOwnEmpty}</p>
			{else}                                                     
				<p>{string Premanager changeAvatarMessageForeignEmpty array(userName=$Premanager_ChangeAvatar_userName)}</p>
			{/if}
		{/if}  
		
		<fieldset class="inputs">
			{if $Premanager_ChangeAvatar_hasAvatar}
				<dl>
					<dt><label>{string Premanager label array(label=string(Premanager currentAvatar))}</label></dt>
					<dd>
						<img alt="{string Premanager avatarOf array(userName=$Premanager_ChangeAvatar_userName)}" src="./{treeURL Premanager Users}/{url $Premanager_ChangeAvatar_userName}?avatar" />
						<p>{string Premanager currentAvatarDescription}</p>
					</dd>
				</dl> 
			{/if}
			         
			<dl>
				<dt><label for="Premanager_ChangeAvatar_file">{if $Premanager_ChangeAvatar_hasAvatar}{string Premanager label array(label=string(Premanager selectAvatarExisting))}{else}{string Premanager label array(label=string(Premanager selectAvatarEmpty))}{/if}</label></dt>
				<dd>
					<input type="file" name="Premanager_ChangeAvatar_file" id="Premanager_ChangeAvatar_file"{if $Premanager_ChangeAvatar_file_incorrect} class="error"{/if} />
					<p>{if $Premanager_ChangeAvatar_hasAvatar}{string Premanager selectAvatarExistingDescription}{else}{string Premanager selectAvatarEmptyDescription}{/if}</p>
				</dd>
			</dl>
			
			<dl>
				<dt><label>{string Premanager label array(label=string(Premanager changeAvatarButtonsLabel))}</label></dt>
				<dd>
					<input type="submit" name="Premanager_ChangeAvatar_submit" id="Premanager_ChangeAvatar_submit" value="{string Premanager changeAvatarButton}" class="main" />      
					{if $Premanager_ChangeAvatar_hasAvatar}
						<input type="submit" name="Premanager_ChangeAvatar_delete" id="Premanager_ChangeAvatar_delete" value="{string Premanager deleteAvatarButton}" class="main" />
					{/if}
				</dd>
			</dl>		
		</fieldset>
	</form>
{/block}

{if $ownAvatar}
	{if $user->hasAvatar()}
		<p>{string Premanager changeAvatarMessageOwnExisting array(userName=$user->getName())}</p>
	{else}
		<p>{string Premanager changeAvatarMessageOwnEmpty array(userName=$user->getName())}</p>
	{/if}
{else}
	{if $user->hasAvatar()}
		<p>{string Premanager changeAvatarMessageForeignExisting array(userName=$user->getName())}</p>
	{else}
		<p>{string Premanager changeAvatarMessageForeignEmpty array(userName=$user->getName())}</p>
	{/if}
{/if}

{if $error}
	<ul class="input-errors">
		<li>{$error}</li>
	</ul>
{/if}

{form multipart=true}
	<fieldset class="inputs">
		{if $user->hasAvatar()}
			<dl>
				<dt>{string Premanager label array(label=string(Premanager currentAvatar))}</dt>
				<dd><img alt="{string Premanager avatarOf array(userName=$user->getName())}" src="./{html treeURL(Premanager users)}/{html $user->getName()}/avatar" /></span>
			</dl>
			{$t1 = selectAvatarExisting}
			{$t2 = selectAvatarExistingDescription}
		{else}
			{$t1 = selectAvatarEmpty}
			{$t2 = selectAvatarEmptyDescription}
		{/if}
		
		{formElement
			name="avatar"
			label=string(Premanager $t1)
			description=string(Premanager $t2)
			type=file
		}
	</fieldset>
	
	<fieldset class="buttons">
		<input type="submit" name="submit" class="main" value="{string Premanager changeAvatarButton}" />
		{if $user->hasAvatar()}
			<input type="submit" name="delete" class="main" value="{string Premanager deleteAvatarButton}" />
		{/if}
	</fieldset>  
{/form}
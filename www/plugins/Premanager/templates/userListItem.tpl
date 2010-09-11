<tr>
	<td>
		<a href="./{treeURL Premanager Users}/{url $Premanager_User_name}">
			<span class="avatar"><img alt="{string Premanager avatarOf array(userName=$Premanager_User_name)}" src="./{treeURL Premanager Users}/{url $Premanager_User_name}?avatar" /></span>
			<span class="user-name user" style="color: #{$Premanager_User_color};">{html $Premanager_User_name}</span>
			<span class="user-title">{html $Premanager_User_title}</span>
		</a>
	</td>
	<td>{longDateTime $Premanager_User_registrationTime}</td>
	<td>{if $Premanager_User_lastLoginTime}{longDateTime $Premanager_User_lastLoginTime}{else}{string Premanager literalNone}{/if}</td>
</tr>

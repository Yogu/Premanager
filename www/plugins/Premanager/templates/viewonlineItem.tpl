<tr>
	<td>
		<a href="./{treeURL Premanager Users}/{url $Session_userName}">
			<span class="avatar">
				<img alt="{string Premanager avatarOf array(userName=$Session_userName)}" src="./{treeURL Premanager Users}/{url $Session_userName}" />
			</span>
			<span class="user-name user" style="color: #{$Session_userColor};">{html $Session_userName}</span>
			<span class="user-title">{html $Session_userTitle}</span>
		</a>
	</td>
	<td><span class="short-time" title="{longDateTime $Session_lastRequestTime}">{longRelativeTime $Session_lastRequestTime}</span></td>
	<td>
		<ul class="location">
			<li><a href="http://{urlTemplate(null, null, '')}">{html $Org_title}</a></li>
			{if $Session_projectName != ''}
				<li><a href="http://{urlTemplate(null, null, $Session_projectName)}">{html $Session_projectTitle}</a></li>
			{/if}
		</ul>
	</td>
</tr>

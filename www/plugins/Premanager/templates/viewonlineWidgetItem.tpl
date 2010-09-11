<li>
	<a href="./{treeURL Premanager Users}/{if $Session_userID}{html $Session_userName}{else}{string Premanager guest}{/if}"><span style="color: #{$Session_userColor}" class="user user-name">{if $Session_userID}{html $Session_userName}{else}{string Premanager xGuests array(count=$Session_count)}{/if}</span></a>
</li>

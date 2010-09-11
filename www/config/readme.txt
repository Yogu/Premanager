This folder contains settings for a Premanager installation.

Contents:
	config.ini:
		General settings.
		For more details see comments in the file

	debug:
		If exists, the debug-mode is enabled. If does not exist, release-mode is
		enabled.
		Note: after creating or deleting this file, cache must be cleared to
		completely apply the new value.

	disablelogin:
		If exists, visitors can not login. If does not exist, visitors can login.
		If this file is created while a visitor is logged in, it will be a guest
		until the file is deleted. If the session is still valid then, the user is
		logged in once again.

	.htaccess:
		Makes sure that this folder can not be displayed in the client's browser.
		This is important, because config.ini contains a SECURITY KEY!
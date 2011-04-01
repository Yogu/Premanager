<section class="block widget">
	<header>{if $widget->getLinkURL()}<a href="./{html $widget->getLinkURL()}">{/if}{html $widget->getTitle()}{if $widget->getLinkURL()}</a>{/if}</header>
	<div class="content">{$widget->getContent()}</div>
</section>
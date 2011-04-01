{if count($widgets)}
	<aside id="sidebar" class="editing">
		{foreach $widgets widget}
			<section class="block widget">
				<header>{if $widget->getLinkURL()}<a href="./{html $widget->getLinkURL()}">{/if}{html $widget->getTitle()}{if $widget->getLinkURL()}</a>{/if}</header>
				<div class="content">{$widget->getContent()}</div>
				<div class="controls">
					{form}
						<input type="hidden" name="widget-id" value="{$widget->getID()}" />
						<input type="submit" name="remove" value="{string Premanager.Widgets removeWidgetButton}" class="remove-button" />
						<input type="submit" name="move-up" value="{string Premanager.Widgets moveWidgetUpButton}" class="move-up-button" />
						<input type="submit" name="move-down" value="{string Premanager.Widgets moveWidgetDownButton}" class="move-down-button" />
					{/form}
				</div>
			</section>
		{/foreach}
	</aside>
{/if}

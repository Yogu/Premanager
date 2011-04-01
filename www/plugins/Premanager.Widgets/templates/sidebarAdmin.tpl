<div class="widget-container">
	{foreach $widgetClasses widgetClass}
		<section class="block widget">
			<header>{html $widgetClass->getTitle()}</header>
			<div class="content">{$widgetClass->getSampleContent()}</div>
			<div class="controls">
				{form}
					<input type="hidden" name="widget-class-id" value="{$widgetClass->getID()}" />
					<input type="submit" name="add" value="{string Premanager.Widgets addWidgetButton}" />
				{/form}
			</div>
		</section>
	{/foreach}
</div>
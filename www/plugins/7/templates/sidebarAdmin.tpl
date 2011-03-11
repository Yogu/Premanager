<div class="widget-container">
	{foreach $widgetClasses widgetClass}
		{$widgetClass->getSampleBlock()->getHTML()}	
	{/foreach}
</div>
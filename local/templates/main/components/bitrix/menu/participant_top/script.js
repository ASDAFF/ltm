$(document).ready(function(){
	var container = $("div.exhibition-list");
	var containerWidth = container.width();
	
	var activeElem = container.find("li.active");
	
	if(activeElem.length == 0)
	{
		allElem = container.find("li");
		var currentWidth = $(allElem[0]).width();

		allElem.each(function(indx, element){
			currentWidth = currentWidth + $(element).width();
		});

		if(currentWidth > containerWidth)
		{
			var newWidth = Math.floor((containerWidth - allElem.length) / allElem.length);

			allElem.each(function(indx, element){
				$(element).width(newWidth);
			});
		}
	}
	else
	{
		var activeElemWidth = activeElem.width();
		
		var sibElem = activeElem.siblings("li");
		
		if(sibElem.length > 0)
		{
			var currentWidth = activeElemWidth;
			sibElem.each(function(indx, element){
				currentWidth = currentWidth + $(element).width();
			});
			
			if(currentWidth > containerWidth)
			{
				var notActiveElemWidth = (containerWidth - activeElemWidth - 0.5) / sibElem.length;
				
				sibElem.each(function(indx, element){
					$(element).width(notActiveElemWidth);
				});
			}
		}
	}

});
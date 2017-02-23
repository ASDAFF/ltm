$( document ).ready(function() {
	$(".times-list a").on( "click", function(event) {
		event.preventDefault();
		if($(this).parent().find("select").length > 0){
			var timeChoose = $(this).parent().find("select").val();
			var recHref = $(this).attr("href")+"&to="+timeChoose;
			window.open(recHref,'particip_write', 'scrollbars=yes,resizable=yes,width=500, height=400, left='+(screen.availWidth/2-250)+', top='+(screen.availHeight/2-200)+'');
		}
		else{
			var insertHtml = $("#time-list"+$(this).attr("data-timeslot")).html();			
			$(this).parent().append(insertHtml);
		}
		
	});

	$(".table-fixed").fixedHeaderTable({ footer: false, fixedColumn: true });

});
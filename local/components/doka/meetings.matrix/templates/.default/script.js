$( document ).ready(function() {
	$(".times-list a.meeting, .times-list a.request").on( "click", function(event) {
		event.preventDefault();
		var $selectCompany = $(this).parent().find(".companies");
		if($(this).hasClass('active')){
			var timeChoose = $selectCompany.val();
			var recHref = $(this).attr("href")+"&to="+timeChoose;
			window.open(recHref,'particip_write', 'scrollbars=yes,resizable=yes,width=500, height=400, left='+(screen.availWidth/2-250)+', top='+(screen.availHeight/2-200)+'');
		} else {
			if($selectCompany.length > 0) {
				$selectCompany.show();
			} else {
				var insertHtml = $("#time-list"+$(this).attr("data-timeslot")).html();
				$(this).parent().find("span.cancel").before(insertHtml);
			}
			$(this).parent().find("span.cancel").css('display', 'block');
			$(this).parent().find("a").hide();
			$(this).show();
			$(this).addClass('active');
		}
	});

	$(".times-list span.cancel").on( "click", function(event) {
		$(this).hide();
		$(this).parent().find(".companies").hide();
		$(this).parent().find("a").show();
		$(this).parent().find("a.active").removeClass("active");
	});

	$(".times-list a.reserve").on( "click", function(event) {
		event.preventDefault();
		newWind($(this).attr("href"), 500, 200);
	});

	$(".table-fixed").fixedHeaderTable({ footer: false, fixedColumn: false });

});
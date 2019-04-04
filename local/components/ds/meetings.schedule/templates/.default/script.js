function newRequest(reciver, timeC, app, doing, tupeU, exib){
	var timeList = document.getElementById('companys' + timeC);
	var timeChoose = 1;
	optionIndex = timeList.selectedIndex;
	timeChoose = timeList.options[optionIndex].value;
	var recHref = doing+'?id='+reciver+'&to='+timeChoose+'&time='+timeC+'&app='+app+'&meet=accept&type='+tupeU+'&exib_code='+exib;
	window.open(recHref,'particip_write', 'scrollbars=yes,resizable=yes,width=500, height=400, left='+(screen.availWidth/2-250)+', top='+(screen.availHeight/2-200)+'');
	return false;
}
function newWind(recHref, widthW, heightW){
	window.open(recHref,'particip_wind', 'scrollbars=yes,resizable=yes,width='+widthW+', height='+heightW+', left='+(screen.availWidth/2-250)+', top='+(screen.availHeight/2-200)+'');
	return false;
}
function newWindConfirmed(recHref, widthW, heightW){
	if(confirm("Selected timeslot will be reserved for your purpose and won't be available to make appointment request until you release it")) {
		window.open(recHref,'particip_wind', 'scrollbars=yes,resizable=yes,width='+widthW+', height='+heightW+', left='+(screen.availWidth/2-250)+', top='+(screen.availHeight/2-200)+'');
	}
	return false;
}
jQuery(document).ready(function() {
	jQuery("a.user-info-wind").fancybox({
		"closeBtn" : false,
		"width": 480,
		"padding": 0,
		"margin": 0,
		"scrolling": "auto",
		"type": 'ajax'
	});

	jQuery("a.time-reserve-wind").fancybox({
		"closeBtn" : false,
		"width": 480,
		"padding": 0,
		"margin": 0,
		"scrolling": "auto",
		"type": 'ajax'
	});
});

$(document).on('click','.shedule-info__close', function(){
	$.fancybox.close();
});
$(document).on('click','.shedule-info__send', function(){
	var confirmHref = $(this).closest('form').find("input[name=href]").eq(0).val();
	$.fancybox({
		"closeBtn" : false,
		"width": 480,
		"padding": 0,
		"margin": 0,
		"scrolling": "auto",
		"type": 'ajax',
		"href": confirmHref
	});
});
$(document).on('click','.shedule-info__reload', function(){
	$.fancybox.close();
	location.reload();
});
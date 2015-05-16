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
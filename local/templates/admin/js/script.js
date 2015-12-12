
function newWind(url, width, height){
		var recHref = url;
		window.open(recHref,'particip_write', 'scrollbars=yes,resizable=yes,width='+width+', height='+height+', left='+(screen.availWidth/2-width/2)+', top='+(screen.availHeight/2-height/2)+'');
		return false;
	}

function newWindConfirm(url, width, height, guest){
	var result = confirm(guest);
	if(result){
		var recHref = url;
		window.open(recHref,'particip_write', 'scrollbars=yes,resizable=yes,width='+width+', height='+height+', left='+(screen.availWidth/2-width/2)+', top='+(screen.availHeight/2-height/2)+'');
	}
	return false;
}

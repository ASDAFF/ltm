function newWish(reciver, app, doing){
	//alert("TEST");
	var wishList = document.getElementById('wishlistComp');
	var compChoose = 1;
	optionIndex = wishList.selectedIndex;
	compChoose = wishList.options[optionIndex].value;
	var recHref = doing+'?id='+reciver+'&to='+compChoose+'&app='+app;
	window.open(recHref,'particip_write', 'scrollbars=yes,resizable=yes,width=500, height=400, left='+(screen.availWidth/2-250)+', top='+(screen.availHeight/2-200)+'');
	return false;
}
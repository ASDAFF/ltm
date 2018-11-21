function newWish(receiver, app, doing) {
	var wishList = document.getElementById('wishlistComp');
	var optionIndex = wishList.selectedIndex;
	var compChoose = wishList.options[optionIndex].value || 1;
	var recHref = doing + '?id=' + receiver + '&to=' + compChoose + '&app=' + app;
	window.open(recHref, 'particip_write', 'scrollbars=yes,resizable=yes,width=500, height=400, left=' + (screen.availWidth / 2 - 250) + ', top=' + (screen.availHeight / 2 - 200) + '');
	return false;
}
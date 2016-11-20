function regForExhib(exhibID, userID, exIdNumber)
{
	regData = new Object();
	regData.exhibID = exhibID;
	regData.userID = userID;
	regData.SID = BX.bitrix_sessid();
	var parentBlock = $("#exh-"+exIdNumber);

	$.ajax({
		type: "POST",
		url: '/local/components/rarus/auth.form/ajax_reg.php',
		data: regData,
		success: function(data){
			if(data == "OK") {
				parentBlock.html('<p class="exh-choose">You have already registered for this event. Status: not confirmed yet</p>');
				alert('Your request has been sent.');
			}
		}
	});
}
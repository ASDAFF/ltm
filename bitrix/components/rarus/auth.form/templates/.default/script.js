function regForExhib(exhibID, userID)
{
	regData = new Object();
	regData.exhibID = exhibID;
	regData.userID = userID;
	regData.SID = BX.bitrix_sessid();


	$.ajax({
		type: "POST",
		url: '/bitrix/components/rarus/auth.form/ajax_reg.php',
		data: regData,
		success: function(data){
			if(data == "OK")
				alert('Your request has been sent.');
		}
	});
}

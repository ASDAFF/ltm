function regForExhib(exhibID, userID, SID)
{
	regData = new Object();
	regData.exhibID = exhibID;
	regData.userID = userID;
	regData.SID = SID;


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

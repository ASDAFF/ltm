$(function() {

	$("#check_all").on("change", function(){
		if($(this).prop("checked")){
			$(".inbox-list__check-item").prop("checked", true);
		}
		else{
			$(".inbox-list__check-item").prop("checked", false);
		}
	});

	$(".inbox-list__check-item").on("change", function(){
		if($(".inbox-list__check-item:checked").length > 0){
			$(".inbox-list__check-info").show();
		}
		else{
			$(".inbox-list__check-info").hide();
		}
	});

	$(".inbox-list__check-info span").on("click", function(){
		var data = new Array(),
			exib = $("#check_all").data("exib"),
			action = $(this).data("action");
		$('.inbox-list__check-item:checked').each(function(index,value){
			data.push($(value).data("id"));
		});
		$.ajax({
			type: "POST",
			url: "/ajax/messages.php",
			dataType: 'json',
			data:({mess:data, action:action}),
			// Выводим то что вернул PHP
			success: function(res){
				if(res.error == ''){
					var toAdd = 0,
						toDel = 0;
					$('.inbox-list__check-item:checked').each(function(index,value){
						if(action == 'delete'){
							if($(this).closest("tr").hasClass("new-message")){
								toDel += 1;
							}
							$(this).closest("tr").remove();
						}
						else if(action == 'read'){
							if($(this).closest("tr").hasClass("new-message")){
								toDel += 1;
							}
							$(this).closest("tr").removeClass("new-message");
						}
						else if(action == 'unread'){
							if(!$(this).closest("tr").hasClass("new-message")){
								toAdd += 1;
							}
							$(this).closest("tr").addClass("new-message");
						}
					});
					$countMess = $('#mess-'+exib).text();
					if($countMess == '')
						$countMess = 0;
					else
						$countMess = parseInt($countMess);
					$countMess = $countMess + toAdd - toDel;
					$('#mess-'+exib).text($countMess);
					if($countMess < 1)
						$('#mess-'+exib).hide();
					else
						$('#mess-'+exib).show();
				}
			}
		});
	});

});




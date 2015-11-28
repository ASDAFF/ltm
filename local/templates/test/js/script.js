$(document).ready(function(){   
    $(window).scroll(function () {
        if ($(this).scrollTop() > 0) {
            $('#scroller').fadeIn();
        } else {
            $('#scroller').fadeOut();
        }
    });
    $('#scroller').click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 400);
        return false;
    });
});

$(function() {

	$('.mail').focus(function(){
		var val = $(this).val();
		if(val == 'johnpatterson@gmail.com'){
			$(this).val('');
		}
	});
	$('.mail').blur(function(){
		var val = $(this).val();
		if(val == ''){
			$(this).val('johnpatterson@gmail.com');
		}
	});

	$('.pass').focus(function(){
		var val = $(this).val();
		if(val == '12345678'){
			$(this).val('');
		}
	});
	$('.pass').blur(function(){
		var val = $(this).val();
		if(val == ''){
			$(this).val('12345678');
		}
	});



});


//�������� ������� �� �������
function sendRequest(elem, app, from, to, type)
{
	var Data = {
			FromId:parseInt(from),
			ToId: parseInt(to),
			AppId: parseInt(app),
			UType: type
	};

	var TimeSlotTd = $(elem).parent("td").siblings("td.free-slots");

	Data.TimeSlotId = TimeSlotTd.find("select[name=times]").val();

	if(Data.FromId && Data.ToId && Data.UType && Data.AppId && Data.TimeSlotId)
	{
		newWind("/cabinet/service/appointment.php?id=" + Data.FromId + "&to=" + Data.ToId + "&time=" + Data.TimeSlotId + "&app=" + Data.AppId + "&type=" + Data.UType, 500, 600);
	}
	/*
	���������� ���������
	id - �� ����
	to - ����
	time - id ���������
	type - ��� p ��� g (��������� ��� �����)
	app - id �������� (������ ��� ���� ��� 1)
	*/
}
//�������� ������� �� ������� HB
function sendRequestHB(elem, app, from, to, type)
{
	var Data = {
			FromId:parseInt(from),
			ToId: parseInt(to),
			AppId: parseInt(app),
			UType: type
	};

	var TimeSlotTd = $(elem).parent("td").siblings("td.free-slots");

	Data.TimeSlotId = TimeSlotTd.find("select[name=times]").val();

	if(Data.FromId && Data.ToId && Data.UType && Data.AppId && Data.TimeSlotId)
	{
		newWind("/cabinet/service/appointment_hb.php?id=" + Data.FromId + "&to=" + Data.ToId + "&time=" + Data.TimeSlotId + "&app=" + Data.AppId + "&type=" + Data.UType, 500, 600);
	}
	/*
	���������� ���������
	id - �� ����
	to - ����
	time - id ���������
	type - ��� p ��� g (��������� ��� �����)
	app - id �������� (������ ��� ���� ��� 1)
	*/
}

function newWind(url, width, height){
	var recHref = url;
	window.open(recHref,'particip_write', 'scrollbars=yes,resizable=yes,width='+width+', height='+height+', left='+(screen.availWidth/2-width/2)+', top='+(screen.availHeight/2-height/2)+'');
	return false;
}

$(function(){
	$('.repasbut').click(function(){
		var login = $('.repas').val();
		if(login == ''){
			$('.error_log').remove();
			if(!$('#pas_form').next().hasClass('error_cl')){
				$('#pas_form').after('<span class = "error_cl">' + obMes.noLogin + '</span>');
			}
			$('.repas').removeClass('yes_bor').addClass('error_bor');
			$('.pas_yes').hide();
		}else{
			$('.repas').removeClass('error_bor');
			$('.error_cl').remove();
			$('.pas_yes').load('/remind/remind_ajax.php', {login:login}, function(response, status, xhr){
				if(response == 'ER_LOGIN'){
					if(!$('#pas_form').next().hasClass('error_log')){
						$('#pas_form').after('<span class = "error_log">' + obMes.erLogin + '</span>');
					}
					$('.repas').removeClass('yes_bor').addClass('error_bor');
					$('.pas_yes').hide();
				}else{
					$('.repas').addClass('yes_bor');	
					$('.error_cl').remove();
					$('.error_log').remove();
					$('.pas_yes').text(obMes.passSend).css({'display':'block'});
				}
			});
		}
	});
});

//��������� �������� ��� ������������
$(function(){
	
	//���������� ���������� �������� �� ��������
	function add_news(data, selector, remove)
	{
		var container = $(selector);
		
		if(container.length > 0)
		{
			//������� ������� ������
			container.find(remove).remove();
			
			//���������� ������ � ����� ��������
			container.append(data);
		}
	}
	
	//�������� ������� ��� ��������� ��������
	$('div#news').on("click",".show-more-btn",function(){
		//�������� ������ � ������
		var url = $(this).data("url");
		var query = $(this).data("query");
		
		$.ajax({
			url: url,
			data: query + "&ajax=Y",
			dataType: "html",
			success: function(result){
				var html = $(result);
				if(html)
				{
					add_news(html, "div#news", "div.show-more-wrap");
				}
			}
		});
	});

	function checkMeets(){
		var app = new Array();
		$(".meetApp").each(function() {
			app.push($(this).attr("data-id"));
		});
		$.ajax({
			type: "POST",
			url: "/ajax/meetings.php",
			dataType: 'json',
			data:({app:app}),
			success: function(res){
				if(res.error == ''){
					$(".meetApp").each(function() {
						var appId = $(this).attr("data-id"),
							newCount = res[appId].incoming;
						$countMeet = $('#meet-'+appId).text();
						if($countMeet != newCount){
							$('#meet-'+appId).text(newCount);
							if(newCount < 1)
								$('#meet-'+appId).hide();
							else
								$('#meet-'+appId).show();
						}
					});
				}
			}
		});
	}

	$(document).ready(function(){
		setInterval(checkMeets, 60000);
	});
});
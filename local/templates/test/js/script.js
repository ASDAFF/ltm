function closeModal(event) {
	var modal = document.querySelector(".main-modal"),
		overlay = document.querySelector(".mdl-overlay");
	event.preventDefault;
	modal.classList.remove("modal-show");
	overlay.classList.remove("modal-show");
};

function showModal() {
	var modal = document.querySelector(".main-modal"),
		overlay = document.querySelector(".mdl-overlay");
	overlay.classList.add("modal-show");
	modal.classList.add("modal-show");
};


$(document).ready(function(){
	var cross = document.querySelector(".mdl-cross-close"),
		linkClose = document.querySelector(".mdl-close");
	cross.addEventListener("click", closeModal);
	linkClose.addEventListener("click", closeModal);
	
	var timer = 1000;
	/*setTimeout(showModal, timer);*/


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


//отправка запроса на встречу
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
	Изменяемые параметры
	id - от кого
	to - кому
	time - id таймслота
	type - тип p или g (участники или гость)
	app - id выставки (сейчас для всех это 1)
	*/
}
//отправка запроса на встречу HB
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
	Изменяемые параметры
	id - от кого
	to - кому
	time - id таймслота
	type - тип p или g (участники или гость)
	app - id выставки (сейчас для всех это 1)
	*/
}

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

//Подргузка новостей без перезагрузки
$(function(){
	
	//добавление полученных новостей на страницу
	function add_news(data, selector, remove)
	{
		var container = $(selector);
		
		if(container.length > 0)
		{
			//удаляем прошлую кнопку
			container.find(remove).remove();
			
			//записываем данные в конец элемента
			container.append(data);
		}
	}
	
	//отправка запроса для получения новостей
	$('div#news').on("click",".show-more-btn",function(){
		//получаем данные с кнопки
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
			if($(this).attr("data-hb-id") != ''){
				app.push($(this).attr("data-hb-id"));
			}
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
							appHbId = $(this).attr("data-hb-id"),
							newCount = res[appId].incoming;
						if(appHbId != ''){
							newCount += res[appHbId].incoming;
						}
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
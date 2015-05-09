$(function(){
	$('.pasbut').click(function(){
		var pas = $('.depas').val();
		if(pas == ''){
			$('.enpas').val('');
			$('.error_pas').remove();
			$('.enpas').val('').removeClass('yes_bor');
			if(!$('.depas').hasClass('error_bor')){
				$('#pas_form').after('<span class = "error_cl">Вы должны заполнить поле!</span>');
				$('.depas').addClass('error_bor');
			}
		}else{
			if($('#pas_form').next().hasClass('error_cl')){
				$('#pas_form').next().remove();
				$('.depas').removeClass('error_bor');
			}
			$('.enpas').load('/pas/pas_ajax.php', {pas:pas}, function(response, status, xhr){
				if(response != ''){	
					var str = response.substr(-10,10);
					if(str == '**********'){
						$('.enpas').val(response.substring(0, response.length - 10)).addClass('yes_bor');
						if(!$('#pas_form').next().hasClass('error_pas')){
							$('#pas_form').after('<span class = "error_pas">Такого пароля нет в базе!</span>');
						}	
					}else{
						$('.enpas').val(response).addClass('yes_bor');
						$('.error_pas').remove();
					}
				}else{
					if($('.enpas').hasClass('yes_bor')){
						$('.enpas').val('').removeClass('yes_bor');
					}
					if(!$('#pas_form').next().hasClass('error_pas')){
						$('#pas_form').after('<span class = "error_pas">Такого пароля нет в базе либо он был зашифрован по-неизвестному алгоритму!</span>');
					}					
				}
			});
		}
	});
});
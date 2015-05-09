$(function(){
	$('.repasbut').click(function(){
		var login = $('.repas').val();
		if(login == ''){
			$('.error_log').remove();
			if(!$('#pas_form').next().hasClass('error_cl')){
				$('#pas_form').after('<span class = "error_cl">Вы должны ввести свой логин!</span>');
			}
			$('.repas').removeClass('yes_bor').addClass('error_bor');
			$('.pas_yes').hide();
		}else{
			$('.repas').removeClass('error_bor');
			$('.error_cl').remove();
			$('.pas_yes').load('/remind/remind_ajax.php', {login:login}, function(response, status, xhr){
				if(response == 'ER_LOGIN'){
					if(!$('#pas_form').next().hasClass('error_log')){
						$('#pas_form').after('<span class = "error_log">Логин задан неправильно!</span>');
					}
					$('.repas').removeClass('yes_bor').addClass('error_bor');
					$('.pas_yes').hide();
				}else{
					$('.repas').addClass('yes_bor');	
					$('.error_cl').remove();
					$('.error_log').remove();
					$('.pas_yes').text('Ваш пароль отправлен Вам на e-mail.').css({'display':'block'});
				}
			});
		}
	});
});
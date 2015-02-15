$(function(){
	$('#e_info tr').hover(function(){
		if(!$(this).hasClass('tr_e_f')){
			$(this).addClass('trHover');
		}
	}, function(){
		if(!$(this).hasClass('tr_e_f')){
			$(this).removeClass('trHover');
		}
	});
});
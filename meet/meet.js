$(function(){
	$('.meet tr').hover(function(){
		if(!$(this).hasClass('first_meet_cl') && !$(this).hasClass('er_tr')){
			$(this).addClass('trHover');
		}
	}, function(){
		if(!$(this).hasClass('first_meet_cl') && !$(this).hasClass('er_tr')){
			$(this).removeClass('trHover');
		}
	});
});
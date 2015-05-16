$(function() {
	//переписать на классы, уменьшив кол-во кода
	$('.chek').click(function(){
		var ind = $(this).parent().index();
		if($(this).hasClass('active')){
			$('.chek').removeClass('active');
			if($(this).hasClass('st2')){
				$(this).removeClass('active').css({'top':'0'});
				$('.user_gr').val('EXH');
			}else{
				$(this).removeClass('active').css({'top':'4px'});
				$('.user_gr').val('BUY');
			}
		}else{
			$('.chek').removeClass('active');
			if($(this).hasClass('st2')){
				$(this).addClass('active').css({'top':'-4px'});
				$('.user_gr').val('BUY');
			}else{
				$(this).addClass('active').css({'top':'0'});
				$('.user_gr').val('EXH');
			}
		}
		if(ind==0){
			$('.reg_exh').show();
			$('.reg_buy').hide();
		}else{
			$('.reg_exh').hide();
			$('.reg_buy').show();
		}
	});

	$('.reg_exh #t_step2 tr td span').each(function(){
		var cl = $(this).attr('class');
		if(cl == 'stat_ex_no'/* || cl == 'stat_ex_an'*/){
			$(this).parent().prev().prev().children().addClass('n_check');
		}
	});
	$('.reg_buy #t_step2 tr td span').each(function(){
		var cl = $(this).attr('class');
		if(cl == 'stat_ex_no'/* || cl == 'stat_ex_an'*/){
			$(this).parent().prev().prev().children().addClass('n_check');
			$(this).parent().prev().prev().prev().children().addClass('n_check');
		}
	});
	
	$('#center').on('click', '.reg_exh .chek2', function(){
		if(!$(this).hasClass('n_check')){
			if($(this).hasClass('active2')){
				$(this).removeClass('active2');
			}else{
				$(this).addClass('active2');
			}
		}
	});
	$('#center').on('click', '.reg_buy .chek2', function(){
		if(!$(this).hasClass('g_not')){
			var act2 = $('.reg_buy .bb').size();
			if(act2 == 1){
				if(!$(this).hasClass('n_check') && $(this).parent().parent().hasClass('bb')){
					if($(this).hasClass('active2')){
						$(this).removeClass('active2');
					}else{
						$(this).addClass('active2');
					}
				}
			}else{
				if(!$(this).parent().parent().hasClass('bb')){
					$(this).parent().parent().addClass('bb');
				}
				if(!$(this).hasClass('n_check')){
					if($(this).hasClass('active2')){
						$(this).removeClass('active2');
					}else{
						$(this).addClass('active2');
					}
				}
			}
			var act = $('.reg_buy .active2').size();
			//console.log(act);
			if(act == 0){
				$('.reg_buy #t_step2 tr').each(function(){
					$(this).removeClass('bb');
				});
			}
			if(act2 != 1){
				var point = $(this).parent().parent().index();
				$('.reg_buy #t_step2 td.nam_bu span').each(function(){
					$(this).css({'color':'#787878'});
					$('.reg_buy #t_step2 tr:eq('+point+') td.nam_bu span').css({'color':'#000'});
				});
			}
			var acti2 = $('.reg_buy .active2').size();
			if(acti2 == 0){
				$('.reg_buy #t_step2 td.nam_bu span').each(function(){
					$(this).css({'color':'#000'});
				});
			}
		}
	});
	
	$('#center').on('click', '.reg_exh .chek3', function(){
		if($(this).hasClass('active3')){
			$(this).removeClass('active3');
			$('.ex_but').removeClass('but_av');
		}else{
			$(this).addClass('active3');
			$('.ex_but').addClass('but_av');
		}
	});
	
	$('#center').on('click', '.reg_buy .chek3', function(){
		if($(this).hasClass('active3')){
			$(this).removeClass('active3');
			$('.buy_but').removeClass('but_av');
		}else{
			$(this).addClass('active3');
			$('.buy_but').addClass('but_av');
		}
	});
	
	
	$('#center').on('click', '.reg_exh .sel_name', function(){
		if($(this).next().hasClass('rep')){
			$(this).next().slideUp().removeClass('rep');
		}else{
			$(this).next().slideDown().addClass('rep');
		}
	});
	
	$('#center').on('click', '.reg_buy .sel_name', function(){
		if($(this).next().hasClass('rep')){
			$(this).next().slideUp().removeClass('rep');
		}else{
			$(this).next().slideDown().addClass('rep');
		}
	});
	
	$('#center').on('click', '.reg_exh .sel_name_1', function(){
		$('#view_1').slideDown();
		$('.reg_exh .sel_name_1').hide();
		$('#view_1').css({'border':'none'});
		$('.reg_exh .ex_sel_1').css({'background':'#fff', 'border':'1px solid #F2F2F3'});
		$('.reg_exh .sel_name_1').next().css({'display':'block'});
	});
	$('#center').on('click', '.reg_exh .chek_1', function(){
		if($(this).hasClass('active_1')){
			$(this).removeClass('active_1');
		}else{
			$(this).addClass('active_1');
		}
	});
	
	$('#center').on('click', '.reg_buy .sel_name_1', function(){
		$('#bview_1').slideDown();
		$('.reg_buy .sel_name_1').hide();
		$('#bview_1').css({'border':'none'});
		$('.reg_buy .ex_sel_1').css({'background':'#fff', 'border':'1px solid #F2F2F3'});
		$('.reg_buy .sel_name_1').next().css({'display':'block'});
	});
	$('#center').on('click', '.reg_buy .chek_1', function(){
		if($(this).hasClass('active_1')){
			$(this).removeClass('active_1');
		}else{
			$(this).addClass('active_1');
		}
	});
	
	$('#center').on('click', '.reg_exh .up', function(){
		$(this).hide();
		$(this).parent().removeAttr('style');
		$(this).next().hide();
		$(this).prev().css({'display':'block'});
	});
	$('#center').on('click', '.reg_buy .up', function(){
		$(this).hide();
		$(this).parent().removeAttr('style');
		$(this).next().hide();
		$(this).prev().css({'display':'block'});
	});
	
	$('#center').on('click', '.reg_exh .sel_name_2', function(){
		$('#view_2').slideDown();
		$('.reg_exh .sel_name_2').hide();
		$('#view_2').css({'border':'none'});
		$('.reg_exh .ex_sel_2').css({'background':'#fff', 'border':'1px solid #F2F2F3'});
		$('.reg_exh .sel_name_2').next().css({'display':'block'});
	});
	$('#center').on('click', '.reg_exh .chek_2', function(){
		if($(this).hasClass('active_2')){
			$(this).removeClass('active_2');
		}else{
			$(this).addClass('active_2');
		}
	});
	
	$('#center').on('click', '.reg_buy .sel_name_2', function(){
		$('#bview_2').slideDown();
		$('.reg_buy .sel_name_2').hide();
		$('#bview_2').css({'border':'none'});
		$('.reg_buy .ex_sel_2').css({'background':'#fff', 'border':'1px solid #F2F2F3'});
		$('.reg_buy .sel_name_2').next().css({'display':'block'});
	});
	$('#center').on('click', '.reg_buy .chek_2', function(){
		if($(this).hasClass('active_2')){
			$(this).removeClass('active_2');
		}else{
			$(this).addClass('active_2');
		}
	});
	
	$('#center').on('click', '.reg_exh .sel_name_3', function(){
		$('#view_3').slideDown();
		$('.reg_exh .sel_name_3').hide();
		$('#view_3').css({'border':'none'});
		$('.reg_exh .ex_sel_3').css({'background':'#fff', 'border':'1px solid #F2F2F3'});
		$('.reg_exh .sel_name_3').next().css({'display':'block'});
	});
	$('#center').on('click', '.reg_exh .chek_3', function(){
		if($(this).hasClass('active_3')){
			$(this).removeClass('active_3');
		}else{
			$(this).addClass('active_3');
		}
	});
	
	$('#center').on('click', '.reg_buy .sel_name_3', function(){
		$('#bview_3').slideDown();
		$('.reg_buy .sel_name_3').hide();
		$('#bview_3').css({'border':'none'});
		$('.reg_buy .ex_sel_3').css({'background':'#fff', 'border':'1px solid #F2F2F3'});
		$('.reg_buy .sel_name_3').next().css({'display':'block'});
	});
	$('#center').on('click', '.reg_buy .chek_3', function(){
		if($(this).hasClass('active_3')){
			$(this).removeClass('active_3');
		}else{
			$(this).addClass('active_3');
		}
	});
	
	$('#center').on('click', '.reg_exh .sel_name_4', function(){
		$('#view_4').slideDown();
		$('.reg_exh .sel_name_4').hide();
		$('#view_4').css({'border':'none'});
		$('.reg_exh .ex_sel_4').css({'background':'#fff', 'border':'1px solid #F2F2F3'});
		$('.reg_exh .sel_name_4').next().css({'display':'block'});
	});
	$('#center').on('click', '.reg_exh .chek_4', function(){
		if($(this).hasClass('active_4')){
			$(this).removeClass('active_4');
		}else{
			$(this).addClass('active_4');
		}
	});
	
	$('#center').on('click', '.reg_buy .sel_name_4', function(){
		$('#bview_4').slideDown();
		$('.reg_buy .sel_name_4').hide();
		$('#bview_4').css({'border':'none'});
		$('.reg_buy .ex_sel_4').css({'background':'#fff', 'border':'1px solid #F2F2F3'});
		$('.reg_buy .sel_name_4').next().css({'display':'block'});
	});
	$('#center').on('click', '.reg_buy .chek_4', function(){
		if($(this).hasClass('active_4')){
			$(this).removeClass('active_4');
		}else{
			$(this).addClass('active_4');
		}
	});
	
	$('#center').on('click', '.reg_exh .sel_name_5', function(){
		$('#view_5').slideDown();
		$('.reg_exh .sel_name_5').hide();
		$('#view_5').css({'border':'none'});
		$('.reg_exh .ex_sel_5').css({'background':'#fff', 'border':'1px solid #F2F2F3'});
		$('.reg_exh .sel_name_5').next().css({'display':'block'});
	});
	$('#center').on('click', '.reg_exh .chek_5', function(){
		if($(this).hasClass('active_5')){
			$(this).removeClass('active_5');
		}else{
			$(this).addClass('active_5');
		}
	});
	
	$('#center').on('click', '.reg_buy .sel_name_5', function(){
		$('#bview_5').slideDown();
		$('.reg_buy .sel_name_5').hide();
		$('#bview_5').css({'border':'none'});
		$('.reg_buy .ex_sel_5').css({'background':'#fff', 'border':'1px solid #F2F2F3'});
		$('.reg_buy .sel_name_5').next().css({'display':'block'});
	});
	$('#center').on('click', '.reg_buy .chek_5', function(){
		if($(this).hasClass('active_5')){
			$(this).removeClass('active_5');
		}else{
			$(this).addClass('active_5');
		}
	});
	
	$('#center').on('click', '.reg_exh .sel_name_6', function(){
		$('#view_6').slideDown();
		$('.reg_exh .sel_name_6').hide();
		$('#view_6').css({'border':'none'});
		$('.reg_exh .ex_sel_6').css({'background':'#fff', 'border':'1px solid #F2F2F3'});
		$('.reg_exh .sel_name_6').next().css({'display':'block'});
	});
	$('#center').on('click', '.reg_exh .chek_6', function(){
		if($(this).hasClass('active_6')){
			$(this).removeClass('active_6');
		}else{
			$(this).addClass('active_6');
		}
	});
	
	$('#center').on('click', '.reg_buy .sel_name_6', function(){
		$('#bview_6').slideDown();
		$('.reg_buy .sel_name_6').hide();
		$('#bview_6').css({'border':'none'});
		$('.reg_buy .ex_sel_6').css({'background':'#fff', 'border':'1px solid #F2F2F3'});
		$('.reg_buy .sel_name_6').next().css({'display':'block'});
	});
	$('#center').on('click', '.reg_buy .chek_6', function(){
		if($(this).hasClass('active_6')){
			$(this).removeClass('active_6');
		}else{
			$(this).addClass('active_6');
		}
	});

	$('#center').on('click', '.reg_exh .sel_name_7', function(){
		$('#view_7').slideDown();
		$('.reg_exh .sel_name_7').hide();
		$('#view_7').css({'border':'none'});
		$('.reg_exh .ex_sel_7').css({'background':'#fff', 'border':'1px solid #F2F2F3'});
		$('.reg_exh .sel_name_7').next().css({'display':'block'});
	});
	$('#center').on('click', '.reg_exh .chek_7', function(){
		if($(this).hasClass('active_7')){
			$(this).removeClass('active_7');
		}else{
			$(this).addClass('active_7');
		}
	});
	
	$('#center').on('click', '.reg_buy .sel_name_7', function(){
		$('#bview_7').slideDown();
		$('.reg_buy .sel_name_7').hide();
		$('#bview_7').css({'border':'none'});
		$('.reg_buy .ex_sel_7').css({'background':'#fff', 'border':'1px solid #F2F2F3'});
		$('.reg_buy .sel_name_7').next().css({'display':'block'});
	});
	$('#center').on('click', '.reg_buy .chek_7', function(){
		if($(this).hasClass('active_7')){
			$(this).removeClass('active_7');
		}else{
			$(this).addClass('active_7');
		}
	});
	
	$('#center').on('click', '.reg_exh #view span', function(){
		var text = $(this).text();
		$(this).parent().prev().text(text);
		$(this).parent().slideUp().removeClass('rep');
	});
	$('#center').on('click', '.reg_buy #view span', function(){
		var text = $(this).text();
		$(this).parent().prev().text(text);
		$(this).parent().slideUp().removeClass('rep');
	});
	
	$('.reg_exh .inputtext').each(function(){
		$(this).addClass('ex_inp');
	});
	$('.reg_buy .inputtext').each(function(){
		$(this).addClass('ex_inp');
	});
	
	var i1 = 0;
	var first1 = '';
	var other1 = '';
	$('#form_dropdown_SIMPLE_QUESTION_284 option').each(function(){
		i1++;
		var text = $(this).text();
		if(i1 == 1){
			first1 = "<span class='sel_name'>"+text+'</span>';
			other1 += '<span>'+text+'</span>';
		}else{
			other1 += '<span>'+text+'</span>';
		}
	});
	var other01 = "<div class='ex_sel'>"+first1+"<div id='view' style='display: none;'>"+other1+"</div></div>";
	$('#form_dropdown_SIMPLE_QUESTION_284').hide();
	$('#form_dropdown_SIMPLE_QUESTION_284').after(other01);
	$('.reg_exh .inputtextarea').addClass('ex_area');
	
	var bi1 = 0;
	var bfirst1 = '';
	var bother1 = '';
	$('#form_dropdown_SIMPLE_QUESTION_677 option').each(function(){
		bi1++;
		var text = $(this).text();
		if(bi1 == 1){
			bfirst1 = "<span class='sel_name'>"+text+'</span>';
			bother1 += '<span>'+text+'</span>';
		}else{
			bother1 += '<span>'+text+'</span>';
		}
	});
	var bother01 = "<div class='ex_sel'>"+bfirst1+"<div id='view' style='display: none;'>"+bother1+"</div></div>";
	$('#form_dropdown_SIMPLE_QUESTION_677').hide();
	$('#form_dropdown_SIMPLE_QUESTION_677').after(bother01);
	$('.reg_buy .inputtextarea').addClass('ex_area');
	
	var i12 = 0;
	var first12 = '';
	var other12 = '';
	$('#form_dropdown_SIMPLE_QUESTION_889 option').each(function(){
		i12++;
		var text = $(this).text();
		if(i12 == 1){
			first12 = "<span class='sel_name'>Title"+'</span>';
			other12 += '<span>'+text+'</span>';
		}else{
			other12 += '<span>'+text+'</span>';
		}
	});
	var other012 = "<div class='ex_sel2'>"+first12+"<div id='view' style='display: none;'>"+other12+"</div></div>";
	$('#form_dropdown_SIMPLE_QUESTION_889').hide();
	$('#form_dropdown_SIMPLE_QUESTION_889').after(other012);
	
	var bi12 = 0;
	var bfirst12 = '';
	var bother12 = '';
	$('#form_dropdown_SIMPLE_QUESTION_678 option').each(function(){
		bi12++;
		var text = $(this).text();
		if(bi12 == 1){
			bfirst12 = "<span class='sel_name'>"+text+'</span>';
			bother12 += '<span>'+text+'</span>';
		}else{
			bother12 += '<span>'+text+'</span>';
		}
	});
	var bother012 = "<div class='ex_sel2'>"+bfirst12+"<div id='view' style='display: none;'>"+bother12+"</div></div>";
	$('#form_dropdown_SIMPLE_QUESTION_678').hide();
	$('#form_dropdown_SIMPLE_QUESTION_678').after(bother012);
	
	var i2 = 0;
	var first2 = '';
	var other2 = '';
	var tr1 = -1;
	$('.reg_exh .ar1 label span').each(function(){
		i2++;
		tr1++;
		var text = $(this).text();
		var textP = $(this).parent().attr('for');
		if(i2 == 1){
			first2 = "<span class='sel_name_1'>"+text+'</span><span class = "up"></span>';
			if(tr1%2==0){
				other2 += "<tr><td class = 'chek_1' for = '"+textP+"'>"+text+'</td>';
			}else{
				other2 += "<td class = 'chek_1' for = '"+textP+"'>"+text+'</td></tr>';
			}
		}else{
			if(tr1%2==0){
				other2 += "<tr><td class = 'chek_1' for = '"+textP+"'>"+text+'</td>';
			}else{
				other2 += "<td class = 'chek_1' for = '"+textP+"'>"+text+'</td></tr>';
			}
		}
	});
	var other02 = "<div class='ex_sel_1'>"+first2+"<table id='view_1' style='display: none;'>"+other2+"</table></div>";
	$('.reg_exh .ar1').hide();
	$('.reg_exh .ar01').after(other02);
	
	var bi2 = 0;
	var bfirst2 = '';
	var bother2 = '';
	var btr1 = -1;
	$('.reg_buy .ar1 label span').each(function(){
		bi2++;
		btr1++;
		var text = $(this).text();
		var textP = $(this).parent().attr('for');
		if(bi2 == 1){
			bfirst2 = "<span class='sel_name_1'>"+text+'</span><span class = "up"></span>';
			if(btr1%2==0){
				bother2 += "<tr><td class = 'chek_1' for = '"+textP+"'>"+text+'</td>';
			}else{
				bother2 += "<td class = 'chek_1' for = '"+textP+"'>"+text+'</td></tr>';
			}
		}else{
			if(btr1%2==0){
				bother2 += "<tr><td class = 'chek_1' for = '"+textP+"'>"+text+'</td>';
			}else{
				bother2 += "<td class = 'chek_1' for = '"+textP+"'>"+text+'</td></tr>';
			}
		}
	});
	var bother02 = "<div class='ex_sel_1'>"+bfirst2+"<table id='bview_1' style='display: none;'>"+bother2+"</table></div>";
	$('.reg_buy .ar1').hide();
	$('.reg_buy .ar01').after(bother02);
	
	var i3 = 0;
	var first3 = '';
	var other3 = '';
	var tr2 = -1;
	$('.reg_exh .ar2 label span').each(function(){
		i3++;
		tr2++;
		var text = $(this).text();
		var textP = $(this).parent().attr('for');
		if(i3 == 1){
			first3 = "<span class='sel_name_2'>"+text+'</span><span class = "up"></span>';
			if(tr2%2==0){
				other3 += "<tr><td class = 'chek_2' for = '"+textP+"'>"+text+'</td>';
			}else{
				other3 += "<td class = 'chek_2' for = '"+textP+"'>"+text+'</td></tr>';
			}
		}else{
			if(tr2%2==0){
				other3 += "<tr><td class = 'chek_2' for = '"+textP+"'>"+text+'</td>';
			}else{
				other3 += "<td class = 'chek_2' for = '"+textP+"'>"+text+'</td></tr>';
			}
		}
	});
	var other03 = "<div class='ex_sel_2'>"+first3+"<table id='view_2' style='display: none;'>"+other3+"</table></div>";
	$('.reg_exh .ar2').hide();
	$('.reg_exh .ar2').after(other03);
	
	var bi3 = 0;
	var bfirst3 = '';
	var bother3 = '';
	var btr2 = -1;
	$('.reg_buy .ar2 label span').each(function(){
		bi3++;
		btr2++;
		var text = $(this).text();
		var textP = $(this).parent().attr('for');
		if(bi3 == 1){
			bfirst3 = "<span class='sel_name_2'>"+text+'</span><span class = "up"></span>';
			if(btr2%2==0){
				bother3 += "<tr><td class = 'chek_2' for = '"+textP+"'>"+text+'</td>';
			}else{
				bother3 += "<td class = 'chek_2' for = '"+textP+"'>"+text+'</td></tr>';
			}
		}else{
			if(btr2%2==0){
				bother3 += "<tr><td class = 'chek_2' for = '"+textP+"'>"+text+'</td>';
			}else{
				bother3 += "<td class = 'chek_2' for = '"+textP+"'>"+text+'</td></tr>';
			}
		}
	});
	var bother03 = "<div class='ex_sel_2'>"+bfirst3+"<table id='bview_2' style='display: none;'>"+bother3+"</table></div>";
	$('.reg_buy .ar2').hide();
	$('.reg_buy .ar2').after(bother03);
	
	var i4 = 0;
	var first4 = '';
	var other4 = '';
	var tr3 = -1;
	$('.reg_exh .ar3 label span').each(function(){
		i4++;
		tr3++;
		var text = $(this).text();
		var textP = $(this).parent().attr('for');
		if(i4 == 1){
			first4 = "<span class='sel_name_3'>"+text+'</span><span class = "up"></span>';
			if(tr3%2==0){
				other4 += "<tr><td class = 'chek_3' for = '"+textP+"'>"+text+'</td>';
			}else{
				other4 += "<td class = 'chek_3' for = '"+textP+"'>"+text+'</td></tr>';
			}
		}else{
			if(tr3%2==0){
				other4 += "<tr><td class = 'chek_3' for = '"+textP+"'>"+text+'</td>';
			}else{
				other4 += "<td class = 'chek_3' for = '"+textP+"'>"+text+'</td></tr>';
			}
		}
	});
	var other04 = "<div class='ex_sel_3'>"+first4+"<table id='view_3' style='display: none;'>"+other4+"</table></div>";
	$('.reg_exh .ar3').hide();
	$('.reg_exh .ar3').after(other04);
	
	var bi4 = 0;
	var bfirst4 = '';
	var bother4 = '';
	var btr3 = -1;
	$('.reg_buy .ar3 label span').each(function(){
		bi4++;
		btr3++;
		var text = $(this).text();
		var textP = $(this).parent().attr('for');
		if(bi4 == 1){
			bfirst4 = "<span class='sel_name_3'>"+text+'</span><span class = "up"></span>';
			if(btr3%2==0){
				bother4 += "<tr><td class = 'chek_3' for = '"+textP+"'>"+text+'</td>';
			}else{
				bother4 += "<td class = 'chek_3' for = '"+textP+"'>"+text+'</td></tr>';
			}
		}else{
			if(btr3%2==0){
				bother4 += "<tr><td class = 'chek_3' for = '"+textP+"'>"+text+'</td>';
			}else{
				bother4 += "<td class = 'chek_3' for = '"+textP+"'>"+text+'</td></tr>';
			}
		}
	});
	var bother04 = "<div class='ex_sel_3'>"+bfirst4+"<table id='bview_3' style='display: none;'>"+bother4+"</table></div>";
	$('.reg_buy .ar3').hide();
	$('.reg_buy .ar3').after(bother04);
	
	var i5 = 0;
	var first5 = '';
	var other5 = '';
	var tr4 = -1;
	$('.reg_exh .ar4 label span').each(function(){
		i5++;
		tr4++;
		var text = $(this).text();
		var textP = $(this).parent().attr('for');
		if(i5 == 1){
			first5 = "<span class='sel_name_4'>"+text+'</span><span class = "up"></span>';
			if(tr4%2==0){
				other5 += "<tr><td class = 'chek_4' for = '"+textP+"'>"+text+'</td>';
			}else{
				other5 += "<td class = 'chek_4' for = '"+textP+"'>"+text+'</td></tr>';
			}
		}else{
			if(tr4%2==0){
				other5 += "<tr><td class = 'chek_4' for = '"+textP+"'>"+text+'</td>';
			}else{
				other5 += "<td class = 'chek_4' for = '"+textP+"'>"+text+'</td></tr>';
			}
		}
	});
	var other05 = "<div class='ex_sel_4'>"+first5+"<table id='view_4' style='display: none;'>"+other5+"</table></div>";
	$('.reg_exh .ar4').hide();
	$('.reg_exh .ar4').after(other05);
	
	var bi5 = 0;
	var bfirst5 = '';
	var bother5 = '';
	var btr4 = -1;
	$('.reg_buy .ar4 label span').each(function(){
		bi5++;
		btr4++;
		var text = $(this).text();
		var textP = $(this).parent().attr('for');
		if(bi5 == 1){
			bfirst5 = "<span class='sel_name_4'>"+text+'</span><span class = "up"></span>';
			if(btr4%2==0){
				bother5 += "<tr><td class = 'chek_4' for = '"+textP+"'>"+text+'</td>';
			}else{
				bother54 += "<td class = 'chek_4' for = '"+textP+"'>"+text+'</td></tr>';
			}
		}else{
			if(btr4%2==0){
				bother5 += "<tr><td class = 'chek_4' for = '"+textP+"'>"+text+'</td>';
			}else{
				bother5 += "<td class = 'chek_4' for = '"+textP+"'>"+text+'</td></tr>';
			}
		}
	});
	var bother05 = "<div class='ex_sel_4'>"+bfirst5+"<table id='bview_4' style='display: none;'>"+bother5+"</table></div>";
	$('.reg_buy .ar4').hide();
	$('.reg_buy .ar4').after(bother05);
	
	var i6 = 0;
	var first6 = '';
	var other6 = '';
	var tr5 = -1;
	$('.reg_exh .ar5 label span').each(function(){
		i6++;
		tr5++;
		var text = $(this).text();
		var textP = $(this).parent().attr('for');
		if(i6 == 1){
			first6 = "<span class='sel_name_5'>"+text+'</span><span class = "up"></span>';
			if(tr5%2==0){
				other6 += "<tr><td class = 'chek_5' for = '"+textP+"'>"+text+'</td>';
			}else{
				other6 += "<td class = 'chek_5' for = '"+textP+"'>"+text+'</td></tr>';
			}
		}else{
			if(tr5%2==0){
				other6 += "<tr><td class = 'chek_5' for = '"+textP+"'>"+text+'</td>';
			}else{
				other6 += "<td class = 'chek_5' for = '"+textP+"'>"+text+'</td></tr>';
			}
		}
	});
	var other06 = "<div class='ex_sel_5'>"+first6+"<table id='view_5' style='display: none;'>"+other6+"</table></div>";
	$('.reg_exh .ar5').hide();
	$('.reg_exh .ar5').after(other06);
	
	var bi6 = 0;
	var bfirst6 = '';
	var bother6 = '';
	var btr5 = -1;
	$('.reg_buy .ar5 label span').each(function(){
		bi6++;
		btr5++;
		var text = $(this).text();
		var textP = $(this).parent().attr('for');
		if(bi6 == 1){
			bfirst6 = "<span class='sel_name_5'>"+text+'</span><span class = "up"></span>';
			if(btr5%2==0){
				bother6 += "<tr><td class = 'chek_5' for = '"+textP+"'>"+text+'</td>';
			}else{
				bother6 += "<td class = 'chek_5' for = '"+textP+"'>"+text+'</td></tr>';
			}
		}else{
			if(btr5%2==0){
				bother6 += "<tr><td class = 'chek_5' for = '"+textP+"'>"+text+'</td>';
			}else{
				bother6 += "<td class = 'chek_5' for = '"+textP+"'>"+text+'</td></tr>';
			}
		}
	});
	var bother06 = "<div class='ex_sel_5'>"+bfirst6+"<table id='bview_5' style='display: none;'>"+bother6+"</table></div>";
	$('.reg_buy .ar5').hide();
	$('.reg_buy .ar5').after(bother06);
	
	var i7 = 0;
	var first7 = '';
	var other7 = '';
	var tr6 = -1;
	$('.reg_exh .ar6 label span').each(function(){
		i7++;
		tr6++;
		var text = $(this).text();
		var textP = $(this).parent().attr('for');
		if(i7 == 1){
			first7 = "<span class='sel_name_6'>"+text+'</span><span class = "up"></span>';
			if(tr6%2==0){
				other7 += "<tr><td class = 'chek_6' for = '"+textP+"'>"+text+'</td>';
			}else{
				other7 += "<td class = 'chek_6' for = '"+textP+"'>"+text+'</td></tr>';
			}
		}else{
			if(tr6%2==0){
				other7 += "<tr><td class = 'chek_6' for = '"+textP+"'>"+text+'</td>';
			}else{
				other7 += "<td class = 'chek_6' for = '"+textP+"'>"+text+'</td></tr>';
			}
		}
	});
	var other07 = "<div class='ex_sel_6'>"+first7+"<table id='view_6' style='display: none;'>"+other7+"</table></div>";
	$('.reg_exh .ar6').hide();
	$('.reg_exh .ar6').after(other07);
	
	var bi7 = 0;
	var bfirst7 = '';
	var bother7 = '';
	var btr6 = -1;
	$('.reg_buy .ar6 label span').each(function(){
		bi7++;
		btr6++;
		var text = $(this).text();
		var textP = $(this).parent().attr('for');
		if(bi7 == 1){
			bfirst7 = "<span class='sel_name_6'>"+text+'</span><span class = "up"></span>';
			if(btr6%2==0){
				bother7 += "<tr><td class = 'chek_6' for = '"+textP+"'>"+text+'</td>';
			}else{
				bother7 += "<td class = 'chek_6' for = '"+textP+"'>"+text+'</td></tr>';
			}
		}else{
			if(btr6%2==0){
				bother7 += "<tr><td class = 'chek_6' for = '"+textP+"'>"+text+'</td>';
			}else{
				bother7 += "<td class = 'chek_6' for = '"+textP+"'>"+text+'</td></tr>';
			}
		}
	});
	var bother07 = "<div class='ex_sel_6'>"+bfirst7+"<table id='bview_6' style='display: none;'>"+bother7+"</table></div>";
	$('.reg_buy .ar6').hide();
	$('.reg_buy .ar6').after(bother07);
	
	var i8 = 0;
	var first8 = '';
	var other8 = '';
	var tr7 = -1;
	$('.reg_exh .ar7 label span').each(function(){
		i8++;
		tr7++;
		var text = $(this).text();
		var textP = $(this).parent().attr('for');
		if(i8 == 1){
			first8 = "<span class='sel_name_7'>"+text+'</span><span class = "up"></span>';
			if(tr7%2==0){
				other8 += "<tr><td class = 'chek_7' for = '"+textP+"'>"+text+'</td>';
			}else{
				other8 += "<td class = 'chek_7' for = '"+textP+"'>"+text+'</td></tr>';
			}
		}else{
			if(tr7%2==0){
				other8 += "<tr><td class = 'chek_7' for = '"+textP+"'>"+text+'</td>';
			}else{
				other8 += "<td class = 'chek_7' for = '"+textP+"'>"+text+'</td></tr>';
			}
		}
	});
	var other08 = "<div class='ex_sel_7'>"+first8+"<table id='view_7' style='display: none;'>"+other8+"</table></div>";
	$('.reg_exh .ar7').hide();
	$('.reg_exh .ar7').after(other08);
	
	var bi8 = 0;
	var bfirst8 = '';
	var bother8 = '';
	var btr7 = -1;
	$('.reg_buy .ar7 label span').each(function(){
		bi8++;
		btr7++;
		var text = $(this).text();
		var textP = $(this).parent().attr('for');
		if(bi8 == 1){
			bfirst8 = "<span class='sel_name_7'>"+text+'</span><span class = "up"></span>';
			if(btr7%2==0){
				bother8 += "<tr><td class = 'chek_7' for = '"+textP+"'>"+text+'</td>';
			}else{
				bother8 += "<td class = 'chek_7' for = '"+textP+"'>"+text+'</td></tr>';
			}
		}else{
			if(btr7%2==0){
				bother8 += "<tr><td class = 'chek_7' for = '"+textP+"'>"+text+'</td>';
			}else{
				bother8 += "<td class = 'chek_7' for = '"+textP+"'>"+text+'</td></tr>';
			}
		}
	});
	var bother08 = "<div class='ex_sel_7'>"+bfirst8+"<table id='bview_7' style='display: none;'>"+bother8+"</table></div>";
	$('.reg_buy .ar7').hide();
	$('.reg_buy .ar7').after(bother08);
	
	$('#center').on('click', '.reg_exh .chek2', function(){
		if(!$(this).hasClass('n_check')){
			if($(this).hasClass('active2')){
				var text = $(this).parent().next().children().text();
				var text2 = $('#adr').text();
				if(text2 == ''){
					$('#adr').text(text);
				}else{
					$('#adr').text(text2+', '+text);
				}
			}else{
				
				var text = $(this).parent().next().children().text();
				var text2 = $('#adr').text();
				if(text2 == ''){
					$('#adr').text();
				}else{
					text2 = text2.replace(', '+text, "");
					text2 = text2.replace(text+', ', "");
					text2 = text2.replace(text, "");
					$('#adr').text(text2);
				}
			}
		}
	});
	$('#center').on('click', '.reg_buy .chek2', function(){
		var act = $('.reg_buy .bb').size();
		var act2 = $('.reg_buy .active2').size();
		if(act == 0){
			if($(this).parent().hasClass('mon_b')){
				var text = $(this).parent().next().next().children().text();
			}else{
				var text = $(this).parent().next().children().text();
			}
			var text2 = $('#adr2').text();
			if(text2 == ''){
				$('#adr2').text();
			}else{
				text2 = text2.replace(', '+text, "");
				text2 = text2.replace(text+', ', "");
				text2 = text2.replace(text, "");
				$('#adr2').text(text2);
			}
		}else{
			if(!$(this).hasClass('n_check') && act2 < 2){
				if($(this).hasClass('active2')){
					if($(this).parent().hasClass('mon_b')){
						var text = $(this).parent().next().next().children().text();
						var text2 = $('#adr2').text();
						if(text2 == ''){
							$('#adr2').text(text);
						}else{
							$('#adr2').text(text2+', '+text);
						}
					}else{
						var text = $(this).parent().next().children().text();
						var text2 = $('#adr2').text();
						if(text2 == ''){
							$('#adr2').text(text);
						}else{
							$('#adr2').text(text2+', '+text);
						}
					}
				}else{
					if($(this).parent().hasClass('mon_b')){
						var text = $(this).parent().next().next().children().text();
					}else{
						var text = $(this).parent().next().children().text();
					}
					var text2 = $('#adr2').text();
					if(text2 == ''){
						$('#adr2').text();
					}else{
						if(act2 != 1){
							text2 = text2.replace(', '+text, "");
							text2 = text2.replace(text+', ', "");
							text2 = text2.replace(text, "");
							$('#adr2').text(text2);
						}
					}
				}
			}
		}
		
	});
	
	
	$('#center').on('click', '.photos', function(){
		for(var i=0; i< 12; i++){
			if(!$('.phot_s:eq('+i+')').hasClass('act_file')){
				$('.phot_s:eq('+i+')').click();
				break;
			}
		}
	});
	
	$('#center').on('click', '.logo_f', function(){
		$('.logo_file').click();
	});
	
	$('#center').on('click', '.p_fo', function(){
		$('.pers_photo').click();
	});
	
	
	$('.reg_exh #step3 input[type="text"]').each(function(){
		var te = $(this).val();
		$(this).val('');
		$(this).attr('placeholder', te);
		$(this).blur(function(){
			if(te != 'Alternative e-mail' && te != 'http://'){
				var val = $(this).val();
				if(val == '' && !$(this).next().hasClass('ref_er2')){
					//$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Не заполнено поле "'+te+'".</p>');
					$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">This field must be completed.</p>');
				}
				if(val != '' && $(this).next().hasClass('ref_er2')){
					$(this).css({'border':'none'});
					$(this).next().remove();
				}
				if(val != '' && $(this).next().hasClass('not_valid')){
					$(this).next().remove();
				}
				var rus = /а|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я/gi;
				if(val != '' && rus.test(val) && !$(this).next().hasClass('ref_er2')){
					//$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Используйте английский язык".</p>');
					$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Please use English only.</p>');
				}
				if(val != '' && !rus.test(val) && $(this).next().hasClass('ref_er2')){
					$(this).css({'border':'none'});
					$(this).next().remove();
				}
				if(val != '' && $(this).next().hasClass('not_valid')){
					$(this).next().remove();
				}
				var mail = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
				if(te == 'E-mail' || te == 'Please confirm your e-mail'){
					if(val != '' && !mail.test(val) && !$(this).next().hasClass('ref_er2')){
						//$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Введите корректно Ваш E-mail.</p>');
						$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">E-mail address is incorrect.</p>');
					}
					if(val != '' && mail.test(val) && $(this).next().hasClass('ref_er2')){
						$(this).css({'border':'none'});
						$(this).next().remove();
					}
					if(val != '' && $(this).next().hasClass('not_valid')){
						$(this).next().remove();
					}
				}
				if(te == 'Please confirm your e-mail'){
					var email = $('input[placeholder="E-mail"]').val();
					if(val != ''){
						if(val != email && !rus.test(val) && mail.test(val)){
							$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">E-mail не совпадают.</p>');
							$(this).prev().css({'border':'1px solid #ff1111'});
						}
						if(val == email && !rus.test(val) && mail.test(val) && $(this).next().hasClass('ref_er2')){
							$(this).css({'border':'none'});
							$(this).next().remove();
						}
						if(val == email && !rus.test(val) && mail.test(val)){
							$(this).prev().css({'border':'none'});
						}
						if(val != '' && $(this).next().hasClass('not_valid')){
							$(this).next().remove();
						}
					}
				}
				if(te == 'E-mail'){
					var email = $('input[placeholder="Please confirm your e-mail"]').val();
					if(val != '' && email != ''){
						if(val != email && !rus.test(val) && mail.test(val)){
							$(this).css({'border':'1px solid #ff1111'}).next().after('<p class = "ref_er2">E-mail не совпадают.</p>');
						}
						if(val == email && !rus.test(val) && mail.test(val) && $(this).next().next().hasClass('ref_er2')){
							$(this).css({'border':'none'});
							$(this).next().css({'border':'none'});
							$(this).next().next().remove();
						}
					}
				}
				var number = /^\d+$/;
				if(te == 'Telephone'){
					if(!number.test(val) && !$(this).next().hasClass('ref_er2')){
						//$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Номер должен состоять только из цифр.</p>');
						$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Numbers only, please.</p>');
					}
					if(number.test(val) && $(this).next().hasClass('ref_er2')){
						$(this).css({'border':'none'});
						$(this).next().remove();
					}
					if(val != '' && $(this).next().hasClass('not_valid')){
						$(this).next().remove();
					}
				}
				var site = /^(http?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
				/*if(te == 'http://'){
					if(!site.test(val) && !$(this).next().hasClass('ref_er2')){
						$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Введите корректно адрес сайта.</p>');
					}
					if(site.test(val) && $(this).next().hasClass('ref_er2')){
						$(this).css({'border':'none'});
						$(this).next().remove();
					}
					if(val != '' && $(this).next().hasClass('not_valid')){
						$(this).next().remove();
					}
				}*/
				if(te == 'Your login'){
					var th = $(this);
					var rus = /а|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я/gi;
					var simv = val.indexOf(' ')+1;
					if(simv > 0){
						$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Spacebar - invalid character in your login</p>');
					}else{
						if($(this).next().hasClass('ref_er2')){
							$(this).next().remove();
						}
						$(this).css({'border':'none'});
					}
					if(val != '' && !rus.test(val) && !$(this).next().hasClass('ref_er2')){
						$('#ex_login').load('/ajax/ex_login.php', {login: val}, function(response, status, xhr){
							if(response > 0){
								//th.css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Данный логин уже занят.</p>');
								th.css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">This name is busy already.</p>');
							}else{
								if(th.next().hasClass('ref_er2') && rus.test(val)){
									th.next().remove();
									th.css({'border':'none'});
									th.after('<span class = "ajax_yes"></span>');
									//console.log(12);
								}
								if(val != '' && th.next().hasClass('not_valid')){
									th.next().remove();
								}
								if(!th.next().hasClass('not_valid')){
									th.after('<span class = "ajax_yes"></span>');
								}
							}
						});
					}
				}
				if(val != '' && !$(this).next().hasClass('ref_er2')){
					$(this).after('<span class = "ajax_yes2"></span>');
				}else{
					if($(this).next().hasClass('ajax_yes2')){
						$(this).next().remove();
					}
					if($(this).next().hasClass('ref_er2') && $(this).next().next().hasClass('ajax_yes2')){
						$(this).next().next().remove();
					}
				}
			}
			if(te == 'Alternative e-mail'){
				var val = $(this).val();
				if(val != ''){
					var rus = /а|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я/gi;
					if(val != '' && rus.test(val) && !$(this).next().hasClass('ref_er2')){
						//$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Используйте английский язык.</p>');
						$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Please use English only.</p>');
					}
					if(val != '' && !rus.test(val) && $(this).next().hasClass('ref_er2')){
						$(this).css({'border':'none'});
						$(this).next().remove();
					}
					if(val != '' && $(this).next().hasClass('not_valid')){
						$(this).next().remove();
					}
					var mail = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
					if(val != '' && !mail.test(val) && !$(this).next().hasClass('ref_er2')){
						//$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Введите корректно Ваш E-mail.</p>');
						$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">E-mail address is incorrect.</p>');
					}
					if(val != '' && mail.test(val) && $(this).next().hasClass('ref_er2')){
						$(this).css({'border':'none'});
						$(this).next().remove();
					}
					if(val != '' && $(this).next().hasClass('not_valid')){
						$(this).next().remove();
					}
				}else{
					$(this).val('');
					if($(this).next().hasClass('ref_er2')){
						$(this).css({'border':'none'});
						$(this).next().remove();
					}
					if(val != '' && $(this).next().hasClass('not_valid')){
						$(this).next().remove();
					}
				}
				if(val != '' && !$(this).next().hasClass('ref_er2')){
					$(this).after('<span class = "ajax_yes2"></span>');
				}else{
					if($(this).next().hasClass('ajax_yes2')){
						$(this).next().remove();
					}
					if($(this).next().hasClass('ref_er2') && $(this).next().next().hasClass('ajax_yes2')){
						$(this).next().next().remove();
					}
				}
			}
			if(te == 'http://'){
				var site = /^(http?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
				var val = $(this).val();
				//console.log(val);
				if(val != ''){
					if(!site.test(val) && !$(this).next().hasClass('ref_er2')){
						$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Введите корректно адрес сайта.</p>');
					}
					if(site.test(val) && $(this).next().hasClass('ref_er2')){
						$(this).css({'border':'none'});
						$(this).next().remove();
					}
					if(val != '' && $(this).next().hasClass('not_valid')){
						$(this).next().remove();
					}
					if(val != '' && !$(this).next().hasClass('not_valid') && site.test(val)){
						//$(this).next().remove();
					}
				}else{
					$(this).val('');
					if($(this).next().hasClass('ref_er2')){
						$(this).css({'border':'none'});
						$(this).next().remove();
					}
					if(val != '' && $(this).next().hasClass('not_valid')){
						$(this).next().remove();
					}
				}
			}
		});
	});
	
	$('.reg_buy #step3 input[type="password"]').each(function(){
		var te = $(this).val();
		$(this).val('');
		$(this).attr('placeholder', te);
	});
	
	$('.reg_buy #step3 input[type="password"]').each(function(){
		$(this).blur(function(){
			var val = $(this).val();
			var te = $(this).attr('placeholder');
			if(te == 'Введите пароль'){
				var simv = val.indexOf(' ')+1;
				var rus = /а|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я/gi;
				
				var pas = $('input[placeholder="Повторите пароль"]').val();
				if(val != '' && pas != ''){
					if(val != pas && $('.ref_er2').size() == 0){
						$(this).css({'border':'1px solid #ff1111'}).next().after('<span class = "not_validp"></span>').after('<p class = "ref_er2">Пароли не совпадают.</p>');
						$(this).next().css({'border':'1px solid #ff1111'});
						$(this).css({'border':'1px solid #ff1111'});
					}else{
						if($('.ref_er2').size() == 0){
							$('.not_validp').remove();
							$(this).css({'border':'none'});
							$('.ref_er2').remove();
						}
					}
				}
				if(val != '' && pas == ''){
					if(simv > 0){
						$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_validp"></span>').after('<p class = "ref_er2">Вы ввели пробел в пароль. Он недопустим</p>');
					}else{
						if($(this).next().hasClass('ref_er2')){
							$('.ref_er2').remove();
							$('.not_validp').remove();
						}
						$(this).css({'border':'none'});
					}
				}
				if(val == '' && pas != ''){
					if(val != pas){
						$(this).css({'border':'1px solid #ff1111'}).next().after('<span class = "not_validp"></span>').after('<p class = "ref_er2">Пароли не совпадают.</p>');
						$(this).next().css({'border':'1px solid #ff1111'});
						$(this).css({'border':'1px solid #ff1111'});
					}else{
						$('.not_validp').remove();
						$(this).css({'border':'none'});
						$('.ref_er2').remove();
					}
				}
				if(val == '' && pas == ''){
					//nothing
				}
				if(val == '' || pas == '' || val != pas || rus.test(val) || rus.test(pas) || simv > 0){
					$('.ajax_yes3').remove();
				}
				if(val != '' && pas != '' && val == pas && !rus.test(val) && !rus.test(pas) && !$(this).next().hasClass('ref_er2')){
					$(this).next().after('<span class = "ajax_yes3"></span>');
					$('.not_validp').remove();
					$(this).css({'border':'none'});
					$(this).next().css({'border':'none'});
					$('.ref_er2').remove();
				}
			}
			if(te == 'Повторите пароль'){
				var pas = $('input[placeholder="Введите пароль"]').val();
				var simv = val.indexOf(' ')+1;
				var rus = /а|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я/gi;
				if(val != '' && pas != ''){
					if(val != pas && $('.ref_er2').size() == 0){
						$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_validp"></span>').after('<p class = "ref_er2">Пароли не совпадают.</p>');
						$(this).prev().css({'border':'1px solid #ff1111'});
						$(this).css({'border':'1px solid #ff1111'});
					}else{
						if($('.ref_er2').size() == 0){
							$('.not_validp').remove();
							$(this).css({'border':'none'});
							$('.ref_er2').remove();
						}
					}
					
					/*if(val != pas){
						$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_validp"></span>').after('<p class = "ref_er2">Пароли не совпадают.</p>');
					}else{
						$(this).css({'border':'none'});
						$('.not_validp').remove();
						$('.ref_er2').remove();
					}*/
				}
				if(val != '' && pas == ''){
					if(simv > 0){
						$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_validp"></span>').after('<p class = "ref_er2">Вы ввели пробел в пароль. Он недопустим</p>');
					}else{
						if($(this).next().hasClass('ref_er2')){
							$('.ref_er2').remove();
							$('.not_validp').remove();
						}
						$(this).css({'border':'none'});
					}
				}
				if(val == '' && pas != ''){
					if(val != pas){
						$(this).css({'border':'1px solid #ff1111'}).next().after('<span class = "not_validp"></span>').after('<p class = "ref_er2">Пароли не совпадают.</p>');
						$(this).next().css({'border':'1px solid #ff1111'});
						$(this).css({'border':'1px solid #ff1111'});
					}else{
						$('.not_validp').remove();
						$(this).css({'border':'none'});
						$('.ref_er2').remove();
					}
				}
				if(val == '' && pas == ''){
					//nothing
				}
				
				if(val != '' && pas != '' && val == pas && !rus.test(val) && !$(this).hasClass('ref_er2')){
					$(this).after('<span class = "ajax_yes3"></span>');
					$('.not_validp').remove();
					$(this).css({'border':'none'});
					$(this).prev().css({'border':'none'});
					$('.ref_er2').remove();
				}
				if(val == '' || pas == '' || val != pas || rus.test(val) || rus.test(pas) || simv > 0){
					$('.ajax_yes3').remove();
				}
				
			}
		});
	});
	
	$('.reg_buy #step3 input[type="text"]').each(function(){
		var te = $(this).val();
		$(this).val('');
		$(this).attr('placeholder', te);
		$(this).blur(function(){
			var val = $(this).val();
			if(te != 'Имя коллеги' && te != 'Фамилия коллеги' && te != 'Должность коллеги' && te != 'E-mail коллеги' && te != 'Индекс' && te != 'Город' && te != 'http://'){
				//console.log(te);
				if(val == '' && !$(this).next().hasClass('ref_er2')){
					$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Не заполнено поле "'+te+'".</p>');
				}
				if(val != '' && $(this).next().hasClass('ref_er2')){
					$(this).css({'border':'none'});
					$(this).next().remove();
				}
				if(val != '' && $(this).next().hasClass('not_valid')){
					$(this).next().remove();
				}
				var rus = /а|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я/gi;
				if(val != '' && rus.test(val) && !$(this).next().hasClass('ref_er2')){
					$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Используйте английский язык".</p>');
				}
				if(val != '' && !rus.test(val) && $(this).next().hasClass('ref_er2')){
					$(this).css({'border':'none'});
					$(this).next().remove();
				}
				if(val != '' && $(this).next().hasClass('not_valid')){
					$(this).next().remove();
				}
				var mail = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
				if(te == 'E-mail' || te == 'Введите E-mail ещё раз' || te == 'E-mail коллеги'){
					if(val != '' && !mail.test(val) && !$(this).next().hasClass('ref_er2')){
						$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Введите корректно Ваш E-mail.</p>');
					}
					if(val != '' && mail.test(val) && $(this).next().hasClass('ref_er2')){
						$(this).css({'border':'none'});
						$(this).next().remove();
					}
					if(val != '' && $(this).next().hasClass('not_valid')){
						$(this).next().remove();
					}
				}
				if(te == 'Введите пароль'){
					var pas = $('input[placeholder="Повторите пароль"]').val();
					if(val != '' && pas != ''){
						if(val != pas){
							$(this).css({'border':'1px solid #ff1111'}).next().after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Пароли не совпадают.</p>');
							$(this).next().css({'border':'1px solid #ff1111'});
							$(this).css({'border':'1px solid #ff1111'});
						}
					}
					//console.log(pas);
					var simv = val.indexOf(' ')+1;
					
					if(simv > 0){
						$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Вы ввели пробел в пароль. Он недопустим</p>');
					}else{
						if($(this).next().hasClass(ref_er2)){
							$(this).next().remove();
						}
						$(this).css({'border':'none'});
					}
					
					if(val == '' || pas == '' || val != pas || rus.test(val) || rus.test(pas) || simv > 0){
						$('.ajax_yes3').remove();
					}
					if(val != '' && pas != '' && val == pas && !rus.test(val) && !rus.test(pas) && !$(this).next().hasClass('ref_er2')){
						$(this).after('<span class = "ajax_yes3"></span>');
					}
				}
				if(te == 'Повторите пароль'){
					var pas = $('input[placeholder="Введите пароль"]').val();
					if(val != '' && pas != ''){
						if(val != pas){
							$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Пароли не совпадают.</p>');
							$(this).prev().css({'border':'1px solid #ff1111'});
							$(this).css({'border':'1px solid #ff1111'});
						}else{
							$(this).prev().css({'border':'none'});
						}
					}
					
					var simv = val.indexOf(' ')+1;
					
					if(simv > 0){
						$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Вы ввели пробел в пароль. Он недопустим</p>');
					}else{
						if($(this).next().hasClass('ref_er2')){
							$(this).next().remove();
						}
						$(this).css({'border':'none'});
					}
					//console.log(simv);
					var rus = /а|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я/gi;
					if(val != '' && pas != '' && val == pas && !rus.test(val) && !rus.test(pas) && !$(this).next().hasClass('ref_er2')){
						$(this).after('<span class = "ajax_yes3"></span>');
					}
					if(val == '' || pas == '' || val != pas || rus.test(val) || rus.test(pas) || simv > 0){
						$('.ajax_yes3').remove();
					}
					
				}
				/*if(te == 'Введите E-mail ещё раз'){
					var email = $('input[placeholder="E-mail"]').val();
					if(val != ''){
						if(val != email && !rus.test(val) && mail.test(val)){
							$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">E-mail не совпадают.</p>');
							$(this).prev().css({'border':'1px solid #ff1111'});
						}
						if(val == email && !rus.test(val) && mail.test(val) && $(this).next().hasClass('ref_er2')){
							$(this).css({'border':'none'});
							$(this).next().remove();
						}
						if(val == email && !rus.test(val) && mail.test(val)){
							$(this).prev().css({'border':'none'});
						}
						if(val != '' && $(this).next().hasClass('not_valid')){
							$(this).next().remove();
						}
					}
				}
				if(te == 'E-mail'){
					var email = $('input[placeholder="Введите E-mail ещё раз"]').val();
					if(val != '' && email != ''){
						if(val != email && !rus.test(val) && mail.test(val)){
							$(this).css({'border':'1px solid #ff1111'}).next().after('<p class = "ref_er2">E-mail не совпадают.</p>');
						}
						if(val == email && !rus.test(val) && mail.test(val) && $(this).next().next().hasClass('ref_er2')){
							$(this).css({'border':'none'});
							$(this).next().css({'border':'none'});
							$(this).next().next().remove();
						}
					}
				}*/
				var number = /^\d+$/;
				if(te == 'Телефон'){
					if(!number.test(val) && !$(this).next().hasClass('ref_er2')){
						$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Номер должен состоять только из цифр.</p>');
					}
					if(number.test(val) && $(this).next().hasClass('ref_er2')){
						$(this).css({'border':'none'});
						$(this).next().remove();
					}
					if(val != '' && $(this).next().hasClass('not_valid')){
						$(this).next().remove();
					}
				}
				var site = /^(http?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
				if(te == 'http://'){
					if(!site.test(val) && !$(this).next().hasClass('ref_er2')){
						$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Введите корректно адрес сайта.</p>');
					}
					if(site.test(val) && $(this).next().hasClass('ref_er2')){
						$(this).css({'border':'none'});
						$(this).next().remove();
					}
					if(val != '' && $(this).next().hasClass('not_valid')){
						$(this).next().remove();
					}
				}
				if(te == 'Введите логин/гостевое имя'){
					var th = $(this);
					var rus = /а|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я/gi;
					var simv = val.indexOf(' ')+1;
					if(simv > 0){
						$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Вы ввели пробел в логин. Он недопустим</p>');
					}else{
						if($(this).next().hasClass('ref_er2')){
							$(this).next().remove();
						}
						$(this).css({'border':'none'});
					}
					if(val != '' && !rus.test(val) && !$(this).next().hasClass('ref_er2')){
						$('#ex_login').load('/ajax/ex_login.php', {login: val}, function(response, status, xhr){
							if(response > 0){
								th.css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Данный логин уже занят.</p>');
							}else{
								if(th.next().hasClass('ref_er2') && rus.test(val)){
									th.next().remove();
									th.css({'border':'none'});
									th.after('<span class = "ajax_yes"></span>');
									//console.log(12);
								}
								if(val != '' && th.next().hasClass('not_valid')){
									th.next().remove();
								}
								if(!th.next().hasClass('not_valid')){
									th.after('<span class = "ajax_yes"></span>');
								}
							}
						});
					}
				}
				if(val != '' && !$(this).next().hasClass('ref_er2')){
					//$(this).after('<span class = "ajax_yes2"></span>');
				}else{
					if($(this).next().hasClass('ajax_yes2')){
						$(this).next().remove();
					}
					if($(this).next().hasClass('ref_er2') && $(this).next().next().hasClass('ajax_yes2')){
						$(this).next().next().remove();
					}
				}
			}
			if(te == 'Индекс'){
				
				if(val == '' && !$(this).next().hasClass('ref_er2')){
					if(!$(this).next().next().hasClass('ref_er2')){
						$(this).css({'border':'1px solid #ff1111'}).next().after('<span class = "not_valid2"></span>').after('<p class = "ref_er2">Не заполнено поле "'+te+'".</p>');
					}
				}
				var rus = /а|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я/gi;
				if(val != '' && rus.test(val)){
					if($(this).next().next().hasClass('ref_er2')){
						$(this).next().next().remove();
						$('.not_valid2').remove();
					}
					$(this).css({'border':'1px solid #ff1111'}).next().after('<span class = "not_valid2"></span>').after('<p class = "ref_er2">Используйте английский язык".</p>');
				}
				
				
				var number = /^\d+$/;
				if(val != '' && !rus.test(val) && !number.test(val)){
					if($(this).next().next().hasClass('ref_er2')){
						$(this).next().next().remove();
						$('.not_valid2').remove();
					}
					$(this).css({'border':'1px solid #ff1111'}).next().after('<span class = "not_valid2"></span>').after('<p class = "ref_er2">Номер должен состоять только из цифр.</p>');
				}
				if(val != '' && !rus.test(val) && number.test(val)){
					if($(this).next().next().hasClass('ref_er2')){
						$(this).next().next().remove();
						$('.not_valid2').remove();
					}
					$(this).css({'border':'none'});
				}
			}
			if(te == 'Город'){
				
				if(val == '' && !$(this).next().hasClass('ref_er2')){
					if(!$(this).next().hasClass('ref_er2')){
						$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid3"></span>').after('<p class = "ref_er2">Не заполнено поле "'+te+'".</p>');
					}
				}
				var rus = /а|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я/gi;
				if(val != '' && rus.test(val)){
					if($(this).next().hasClass('ref_er2')){
						$(this).next().remove();
						$('.not_valid3').remove();
					}
					$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid3"></span>').after('<p class = "ref_er2">Используйте английский язык".</p>');
				}
				if(val != '' && !rus.test(val)){
					if($(this).next().hasClass('ref_er2')){
						$(this).next().remove();
						$('.not_valid3').remove();
					}
					$(this).css({'border':'none'});
				}
			}
		});
	});
	
	$('.reg_exh #step3 textarea').each(function(){
		var te = $(this).text();
		$(this).text('');
		$(this).attr('placeholder', te);
		$(this).blur(function(){
			var val = $(this).text();
			var va = $(this).val();
			if(val == '' && !$(this).next().hasClass('ref_er2')){
				//$(this).css({'border':'1px solid #ff1111'}).after('<p class = "ref_er2">Не заполнено поле "'+te+'".</p>');
				$(this).css({'border':'1px solid #ff1111'}).after('<p class = "ref_er2">This field must be completed.</p>');
			}
			if(va != '' && $(this).next().hasClass('ref_er2')){
				$(this).css({'border':'1px solid #F2F2F3'});
				$(this).next().remove();
				$('.ref_er10').remove();
			}
			var rus = /а|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я/gi;
			if(va != '' && rus.test(va) && !$(this).next().hasClass('ref_er2')){
				//$(this).css({'border':'1px solid #ff1111'}).after('<p class = "ref_er2">Используйте английский язык".</p>');
				$(this).css({'border':'1px solid #ff1111'}).after('<p class = "ref_er2">Please use English only.</p>');
			}
			if(va != '' && !rus.test(va) && $(this).next().hasClass('ref_er2')){
				$(this).css({'border':'1px solid #F2F2F3'});
				$(this).next().remove();
				$('.ref_er10').remove();
			}
			if(va != '' && !$(this).next().hasClass('ref_er2')){
				$(this).after('<span class = "ajax_yes2"></span>');
			}else{
				if($(this).next().hasClass('ajax_yes2')){
					$(this).next().remove();
					$('.ref_er10').remove();
				}
				if($(this).next().hasClass('ref_er2') && $(this).next().next().hasClass('ajax_yes2')){
					$(this).next().next().remove();
					$('.ref_er10').remove();
				}
			}
			//console.log(va.length);
			if(va.length > 1200){
				$(this).css({'border':'1px solid #ff1111'}).after('<p class = "ref_er10">Вы ввели более 1200 символов.</p>');
			}else{
				if($(this).next().hasClass('ref_er10')){
					$(this).css({'border':'1px solid #F2F2F3'});
					$('.ref_er10').remove();
				}
			}
		});
	});
	 
	$('.reg_buy #step3 textarea').each(function(){
		var te = $(this).text();
		$(this).text('');
		$(this).attr('placeholder', te);
		$(this).blur(function(){
			var val = $(this).text();
			var va = $(this).val();
			if(val == '' && !$(this).next().hasClass('ref_er2')){
				$(this).css({'border':'1px solid #ff1111'}).after('<p class = "ref_er2">Не заполнено поле "'+te+'".</p>');
			}
			if(va != '' && $(this).next().hasClass('ref_er2')){
				$(this).css({'border':'none'});
				$(this).next().remove();
				$('.ref_er10').remove();
			}
			var rus = /а|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я/gi;
			if(va != '' && rus.test(va) && !$(this).next().hasClass('ref_er2')){
				$(this).css({'border':'1px solid #ff1111'}).after('<p class = "ref_er2">Используйте английский язык".</p>');
			}
			if(va != '' && !rus.test(va) && $(this).next().hasClass('ref_er2')){
				$(this).css({'border':'none'});
				$(this).next().remove();
				$('.ref_er10').remove();
			}
			if(va != '' && !$(this).next().hasClass('ref_er2')){
				$(this).after('<span class = "ajax_yes2"></span>');
			}else{
				if($(this).next().hasClass('ajax_yes2')){
					$(this).next().remove();
					$('.ref_er10').remove();
				}
				if($(this).next().hasClass('ref_er2') && $(this).next().next().hasClass('ajax_yes2')){
					$(this).next().next().remove();
					$('.ref_er10').remove();
				}
			}
			if(va.length > 1200){
				$(this).css({'border':'1px solid #ff1111'}).after('<p class = "ref_er10">Вы ввели более 1200 символов.</p>');
			}else{
				if($(this).next().hasClass('ref_er10')){
					$(this).css({'border':'1px solid #F2F2F3'});
					$('.ref_er10').remove();
				}
			}
		});
	});
	
	
	$('#center').on('click', '.reg_exh .ex_but', function(){
		if($(this).hasClass('but_av')){
			$('.ref_er, .ref_er2').remove();
			var errors = false;
			var ex_count = $('.active2').size();
			if(ex_count == 0){
				$('.reg_exh #t_step2').after("<p class = 'ref_er'>Выберите хотя бы одну выставку.</p>");
				errors = true;
			}
			var er_c = 0;
			$('.reg_exh #step3 input[type="text"]').each(function(){
				var te = $(this).val();
				var te2 = $(this).attr('placeholder');
				if(te2 != 'Alternative e-mail' && te2 != 'http://'){
					var val = $(this).val();
					if(val == '' && !$(this).next().hasClass('ref_er2')){
						//$(this).css({'border':'1px solid #ff1111'}).after('<p class = "ref_er2">Не заполнено поле "'+te2+'".</p>');
						$(this).css({'border':'1px solid #ff1111'}).after('<p class = "ref_er2">This field must be completed.</p>');
						er_c++;
					}
					if(val != '' && $(this).next().hasClass('ref_er2')){
						$(this).css({'border':'none'});
						//$(this).next().remove();
					}
					var rus = /а|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я/gi;
					if(val != '' && rus.test(val) && !$(this).next().hasClass('ref_er2')){
						//$(this).css({'border':'1px solid #ff1111'}).after('<p class = "ref_er2">Используйте английский язык".</p>');
						$(this).css({'border':'1px solid #ff1111'}).after('<p class = "ref_er2">Please use English only.</p>');
						er_c++;
					}
					if(val != '' && !rus.test(val) && $(this).next().hasClass('ref_er2')){
						$(this).css({'border':'none'});
						//$(this).next().remove();
					}
					var mail = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
					if(te2 == 'E-mail' || te2 == 'Please confirm your e-mail'){
						if(val != '' && !mail.test(val) && !$(this).next().hasClass('ref_er2')){
							//$(this).css({'border':'1px solid #ff1111'}).after('<p class = "ref_er2">Введите корректно Ваш E-mail.</p>');
							$(this).css({'border':'1px solid #ff1111'}).after('<p class = "ref_er2">E-mail address is incorrect.</p>');
							er_c++;
						}
						if(val != '' && mail.test(val) && $(this).next().hasClass('ref_er2')){
							$(this).css({'border':'none'});
							//$(this).next().remove();
						}
					}
					if(te2 == 'Please confirm your e-mail'){
						var email = $('input[placeholder="E-mail"]').val();
						if(val != ''){
							if(val != email && !rus.test(val) && mail.test(val)){
								$(this).css({'border':'1px solid #ff1111'}).after('<p class = "ref_er2">E-mail не совпадают.</p>');
								$(this).prev().css({'border':'1px solid #ff1111'});
								er_c++;
							}
							if(val == email && !rus.test(val) && mail.test(val) && $(this).next().hasClass('ref_er2')){
								$(this).css({'border':'none'});
								//$(this).next().remove();
							}
							if(val == email && !rus.test(val) && mail.test(val)){
								$(this).prev().css({'border':'none'});
							}
						}
					}
					if(te2 == 'E-mail'){
						var email = $('input[placeholder="Please confirm your e-mail"]').val();
						if(val != '' && email != ''){
							if(val != email && !rus.test(val) && mail.test(val)){
								$(this).css({'border':'1px solid #ff1111'}).next().after('<p class = "ref_er2">E-mail не совпадают.</p>');
								er_c++;
							}
							if(val == email && !rus.test(val) && mail.test(val) && $(this).next().next().hasClass('ref_er2')){
								$(this).css({'border':'none'});
								$(this).next().css({'border':'none'});
								//$(this).next().next().remove();
							}
						}
					}
					var number = /^\d+$/;
					if(te2 == 'Telephone'){
						if(!number.test(val) && !$(this).next().hasClass('ref_er2')){
							//$(this).css({'border':'1px solid #ff1111'}).after('<p class = "ref_er2">Номер должен состоять только из цифр.</p>');
							$(this).css({'border':'1px solid #ff1111'}).after('<p class = "ref_er2">Numbers only, please.</p>');
							er_c++;
						}
						if(number.test(val) && $(this).next().hasClass('ref_er2')){
							$(this).css({'border':'none'});
							//$(this).next().remove();
						}
					}
					/*var site = /^(http?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
					if(te2 == 'http://'){
						if(!site.test(val) && !$(this).next().hasClass('ref_er2')){
							$(this).css({'border':'1px solid #ff1111'}).after('<p class = "ref_er2">Введите корректно адрес сайта.</p>');
							er_c++;
						}
						if(site.test(val) && $(this).next().hasClass('ref_er2')){
							$(this).css({'border':'none'});
							//$(this).next().remove();
						}
					}*/
					if(te2 == 'Your login'){
						var th = $(this);
						if(val != ''){
							$('#ex_login').load('/ajax/ex_login.php', {login: val}, function(response, status, xhr){
								if(response > 0){
									//th.css({'border':'1px solid #ff1111'}).after('<p class = "ref_er2">Данный логин уже занят.</p>');
									th.css({'border':'1px solid #ff1111'}).after('<p class = "ref_er2">This name is busy already.</p>');
									er_c++;
								}else{
									if(th.next().hasClass('ref_er2') && rus.test(val)){
										th.next().remove();
										th.css({'border':'none'});
										th.after('<span class = "ajax_yes"></span>');
									}
								}
							});
						}
					}
					if(val != '' && !$(this).next().hasClass('ref_er2')){
						$(this).after('<span class = "ajax_yes2"></span>');
					}else{
						if($(this).next().hasClass('ajax_yes2')){
							//$(this).next().remove();
						}
						if($(this).next().hasClass('ref_er2')){
							//$(this).next().next().remove();
						}
					}
				}
				if(te2 == 'Alternative e-mail'){
					var val = $(this).val();
					if(val != ''){
						var rus = /а|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я/gi;
						if(val != '' && rus.test(val) && !$(this).next().hasClass('ref_er2')){
							//$(this).css({'border':'1px solid #ff1111'}).after('<p class = "ref_er2">Используйте английский язык.</p>');
							$(this).css({'border':'1px solid #ff1111'}).after('<p class = "ref_er2">Please use English only.</p>');
							er_c++;
						}
						if(val != '' && !rus.test(val) && $(this).next().hasClass('ref_er2')){
							$(this).css({'border':'none'});
							//$(this).next().remove();
						}
						var mail = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
						if(val != '' && !mail.test(val) && !$(this).next().hasClass('ref_er2')){
							//$(this).css({'border':'1px solid #ff1111'}).after('<p class = "ref_er2">Введите корректно Ваш E-mail.</p>');
							$(this).css({'border':'1px solid #ff1111'}).after('<p class = "ref_er2">E-mail address is incorrect.</p>');
							er_c++;
						}
						if(val != '' && mail.test(val) && $(this).next().hasClass('ref_er2')){
							$(this).css({'border':'none'});
							//$(this).next().remove();
						}
					}else{
						$(this).val('');
						if($(this).next().hasClass('ref_er2')){
							$(this).css({'border':'none'});
							//$(this).next().remove();
						}
					}
				}
				/*if(te2 == 'http://'){
					var site = /^(http?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
					var val = $(this).val();
					//console.log(val);
					if(val != ''){
						if(!site.test(val) && !$(this).next().hasClass('ref_er2')){
							$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Введите корректно адрес сайта.</p>');
						}
						if(site.test(val) && $(this).next().hasClass('ref_er2')){
							$(this).css({'border':'none'});
							$(this).next().remove();
						}
						if(val != '' && $(this).next().hasClass('not_valid')){
							$(this).next().remove();
						}
						if(val != '' && !$(this).next().hasClass('not_valid') && site.test(val)){
							$(this).next().remove();
						}
					}else{
						$(this).val('');
						if($(this).next().hasClass('ref_er2')){
							$(this).css({'border':'none'});
							$(this).next().remove();
						}
						if(val != '' && $(this).next().hasClass('not_valid')){
							$(this).next().remove();
						}
					}
				}*/
				if(te2 == 'http://'){
					var site = /^(http?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
					var val = $(this).val();
					//console.log(val);
					if(val != ''){
						if(!site.test(val) && !$(this).next().hasClass('ref_er2')){
							$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Введите корректно адрес сайта.</p>');
						}
						if(site.test(val) && $(this).next().hasClass('ref_er2')){
							$(this).css({'border':'none'});
							$(this).next().remove();
						}
						if(val != '' && $(this).next().hasClass('not_valid')){
							$(this).next().remove();
						}
						if(val != '' && !$(this).next().hasClass('not_valid') && site.test(val)){
							//$(this).next().remove();
						}
					}else{
						$(this).val('');
						if($(this).next().hasClass('ref_er2')){
							$(this).css({'border':'none'});
							$(this).next().remove();
						}
						if(val != '' && $(this).next().hasClass('not_valid')){
							$(this).next().remove();
						}
					}
				}
				if(val != '' && !$(this).next().hasClass('ref_er2')){
					$(this).after('<span class = "ajax_yes2"></span>');
				}else{
					if($(this).next().hasClass('ajax_yes2')){
						//$(this).next().remove();
					}
				}
			});
			var er_c2 = 0;
			$('.reg_exh #step3 textarea').each(function(){
				var val = $(this).text();
				var val2 = $(this).attr('placeholder');
				var va = $(this).val();
				if(va == '' && !$(this).next().hasClass('ref_er2')){
					//$(this).css({'border':'1px solid #ff1111'}).after('<p class = "ref_er2">Не заполнено поле "'+val2+'".</p>');
					$(this).css({'border':'1px solid #ff1111'}).after('<p class = "ref_er2">This field must be completed</p>');
					er_c2++;
				}
				if(va != '' && $(this).next().hasClass('ref_er2')){
					$(this).css({'border':'none'});
					//$(this).next().remove();
					er_c2--;
				}
				var rus = /а|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я/gi;
				if(va != '' && rus.test(va) && !$(this).next().hasClass('ref_er2')){
					//$(this).css({'border':'1px solid #ff1111'}).after('<p class = "ref_er2">Используйте английский язык".</p>');
					$(this).css({'border':'1px solid #ff1111'}).after('<p class = "ref_er2">Please use English only.</p>');
					er_c2++;
				}
				if(va != '' && !rus.test(va) && $(this).next().hasClass('ref_er2')){
					$(this).css({'border':'none'});
					//$(this).next().remove();
					er_c2--;
				}
				if(val != '' && !$(this).next().hasClass('ref_er2')){
					$(this).after('<span class = "ajax_yes2"></span>');
				}else{
					if($(this).next().hasClass('ajax_yes2')){
						//$(this).next().remove();
					}
				}
			});
			var active_1 = $('.reg_exh .active_1').size();
			var active_2 = $('.reg_exh .active_2').size();
			var active_3 = $('.reg_exh .active_3').size();
			var active_4 = $('.reg_exh .active_4').size();
			var active_5 = $('.reg_exh .active_5').size();
			var active_6 = $('.reg_exh .active_6').size();
			var active_7 = $('.reg_exh .active_7').size();
			var areas = active_1 + active_2 + active_3 + active_4 + active_5 + active_6 + active_7;
			$('.reg_exh .er_photos, .reg_exh .er_logo, .reg_exh .er_p_photo, .reg_exh .ex_agree, .reg_exh .er_area').remove();
			$('.reg_exh .p_fo').after('<span class="er_photos"></span>	 <span class="er_logo"></span><span class="er_area"></span>	 <span class="er_p_photo"></span><span class="ex_agree"></span>');
			
			if(areas == 0){
				$('.reg_exh .er_area').addClass('ref_er2').text('Вы не выбрали своего местоположения.');
				errors = true;
			}else{
				$('.reg_exh .er_area').hide().text('');
				errors = false;
			}
			//photos
			var c_photos = $('.reg_exh .act_file').size();
			/*if(c_photos == 0){
				$('.er_photos').addClass('ref_er2').text('Вы не загрузили фотографии.');
				errors = true;
			}else{
				if(c_photos < 6){
					$('.er_photos').addClass('ref_er2').text('Должно быть не менее 6 фотографий.');
					errors = true;
				}else{
					$('.er_photos').hide().text('');
					errors = false;
				}
			}*/
			//logotip
			var c_logo = $('.act_file2').size();
			/*if(c_logo == 0){
				$('.er_logo').addClass('ref_er2').text('Вы не загрузили логотип.');
				errors = true;
			}else{
				$('.er_logo').hide().text('');
				errors = false;
			}*/
			//персональное фото
			var c_pers = $('.act_file3').size();
			/*if(c_pers == 0){
				$('.er_p_photo').addClass('ref_er2').text('Вы не загрузили персональное фото.');
				errors = true;
			}else{
				$('.er_p_photo').hide().text('');
				errors = false;
			}*/
			
			var agree = $('.reg_exh .active3').size();
			if(agree == 0){
				$('.reg_exh .ex_agree').addClass('ref_er2').text('Вы должны согласиться с условиями.');
			}else{
				$('.reg_exh .ex_agree').hide().text('');
			}
			
			var soluti = $('.reg_exh .ex_sel2 .sel_name').text();
			var sol_u = 0;
			if(soluti == 'Title'){
				$('.reg_exh .ex_sel2 .sel_name').parent().after('<p class = "ref_er2">Не выбрано поле Title.</p>');
				sol_u = 1;
			}else{
				if($('.reg_exh .ex_sel2 .sel_name').parent().next().hasClass('ref_er2')){
					$('.reg_exh .ex_sel2 .sel_name').parent().next().remove();
				}
				sol_u = 0;
			}
			var actF = $('.act_file').size();
			//console.log(actF);
			if(actF > 0){
				if(actF < 6 || actF > 12){
					$('.reg_exh .ex_agree').addClass('ref_er2').show().text('Вы должны загрузить от 6 до 12 фотографий.');
					errors = true;
				}else{
					$('.reg_exh .ex_agree').hide().text('');
					errors = false;
				}
			}
			//отправка данных
			/*console.log(er_c);
			console.log(er_c2);
			console.log(areas);
			console.log(agree);
			console.log(errors);*/
			if(!errors && (er_c == 0 && er_c2 == 0 && areas > 0 && agree > 0 && $('.ref_er2').size() == 0 && sol_u == 0)){
				//console.log('yes');
				var of_name = $('input[placeholder="Official name for invoice, if different"]').val();
				var c_name = $('input[placeholder="Company or hotel name"]').val();
				var login = $('input[placeholder="Your login"]').val();
				var area_b = '';
				$('#form_dropdown_SIMPLE_QUESTION_284 option').each(function(){
					//if($(this).text() == $('.reg_exh .ex_sel .sel_name').text()){
					if($(this).text() == $(this).parent().next().children('.sel_name').text()){
						area_b = $(this).val();
					}
				});
				var salut = '';
				$('#form_dropdown_SIMPLE_QUESTION_889 option').each(function(){
					//if($(this).text() == $('.ex_sel2 .sel_name').text()){
					if($(this).text() == $(this).parent().next().children('.sel_name').text()){
						salut = $(this).val();
					}
				});
				//console.log(area_b);
				//console.log(salut);
				//var area_b = $('.ex_sel .sel_name').text();
				var adress = $('input[placeholder="Official adress"]').val();
				var city = $('input[placeholder="City"]').val();
				var country = $('input[placeholder="Country"]').val();
				var site = $('input[placeholder="http://"]').val();
				var textComp = $('textarea[name="form_textarea_39"]').val();
				//console.log(textComp);
				var t1 = '';
				var t2 = '';
				var t3 = '';
				var t4 = '';
				var t5 = '';
				var t6 = '';
				var t7 = '';
				$('.reg_exh .active_1').each(function(){
					t1 = t1+'|'+$(this).attr('for');
				});
				$('.reg_exh .active_2').each(function(){
					t2 = t2+'|'+$(this).attr('for');
				});
				$('.reg_exh .active_3').each(function(){
					t3 = t3+'|'+$(this).attr('for');
				});
				$('.reg_exh .active_4').each(function(){
					t4 = t4+'|'+$(this).attr('for');
				});
				$('.reg_exh .active_5').each(function(){
					t5 = t5+'|'+$(this).attr('for');
				});
				$('.reg_exh .active_6').each(function(){
					t6 = t6+'|'+$(this).attr('for');
				});
				$('.reg_exh .active_7').each(function(){
					t7 = t7+'|'+$(this).attr('for');
				});
				var areas = t1+'|'+t2+'|'+t3+'|'+t4+'|'+t5+'|'+t6+'|'+t7;
				var photos = '';
				$('.act_file').each(function(){
					photos = photos+'|'+$(this).val();
				});
				var logo = $('.act_file2').val();
				//console.log(logo2);
				var first_name = $('input[placeholder="Participant first name"]').val();
				var last_name = $('input[placeholder="Participant last name"]').val();
				//var salut = $('input[placeholder="Salutation"]').val();
				var job = $('input[placeholder="Job title"]').val();
				var phone_n = $('input[placeholder="Telephone"]').val();
				var mail = $('input[placeholder="E-mail"]').val();
				var con_mail = $('input[placeholder="Please confirm your e-mail"]').val();
				var alt_mail = $('input[placeholder="Alternative e-mail"]').val();
				var group_user = '';
				$('.reg_exh .active2').each(function(){
					group_user = group_user + '|' + $(this).attr('for');
				});
				$('.reg_exh #resp_ajax').load('/ajax/ex_registr.php', {of_name:of_name, c_name:c_name, login:login, area_b:area_b, adress:adress, city:city, country:country, site:site, textComp:textComp, areas:areas, first_name:first_name, last_name:last_name, salut:salut, job:job, phone_n:phone_n, mail:mail, con_mail:con_mail, alt_mail:alt_mail, group_user:group_user}, 
				function(response, status, xhr){
					if(response > 0){
						//console.log(12);
						document.location.href = '/registr/yes_e.php';
						//$(location).href('/registr/yes.php');
					}
				});
			}
		}
	});
	
	//
	$('#center').on('click', '.reg_buy .chek2', function(){
		if($(this).parent().hasClass('eve_b') && !$(this).parent().prev().children().hasClass('act_e')){
			//вечер вкл
			if(!$(this).hasClass('act_e')){
				$(this).addClass('act_e');
				$('#evernyng').show().addClass('y_ex');
			//вечер выкл
			}else{
				$(this).removeClass('act_e');
				$('#evernyng').hide().removeClass('y_ex');
			}
		}
	});
	
	$('#center').on('click', '.reg_buy .chek2', function(){
		if($(this).parent().hasClass('mon_b') && !$(this).parent().next().children().hasClass('act_e')){
			//утро вкл
			if(!$(this).hasClass('act_e')){
				$(this).addClass('act_e');
				$('#morning').show().addClass('y_ex');
			//утро выкл
			}else{
				$(this).removeClass('act_e');
				$('#morning').hide().removeClass('y_ex');
			}
		}
	});
	
	$('#center').on('click', '.reg_buy .chek2', function(){
		if($(this).parent().hasClass('mon_b') && $(this).parent().next().children().hasClass('act_e')){
			if(!$(this).hasClass('act_e')){
				//утро вкл + вечер уже акт-й
				$(this).addClass('act_e');
				$('#morning, #both_time').show().addClass('y_ex');
				$('#evernyng').hide().removeClass('y_ex');
			}else{
				//утро выкл + вечер уже акт-й
				$(this).removeClass('act_e');
				$('#morning, #both_time').hide().removeClass('y_ex');
				$('#evernyng').show().addClass('y_ex');
			}
		}
		if($(this).parent().hasClass('eve_b') && $(this).parent().prev().children().hasClass('act_e')){
			if(!$(this).hasClass('act_e')){
				//вечер вкл + утро уже акт-й
				$(this).addClass('act_e');
				$('#morning, #both_time').show().addClass('y_ex');
				$('#evernyng').hide().removeClass('y_ex');
			}else{
				//вечер выкл + утро уже акт-й
				$(this).removeClass('act_e');
				$('#evernyng, #both_time').hide().removeClass('y_ex');
				$('#morning').show().addClass('y_ex');
			}
		}
	});

	/*$('#center').on('click', '.reg_buy input[name="form_text_236"]', function(){
		$(this).attr('type', 'password');
	});
	$('#center').on('click', '.reg_buy input[name="form_text_237"]', function(){
		$(this).attr('type', 'password');
	});*/
	
	$('.reg_buy input[name="form_text_510"]').hide();
	
	$('#center').on('click', '.reg_buy .ex_sel2 span', function(){
		if($(this).text() == 'other' || $(this).text() == 'Other'){
			$('.reg_buy input[name="form_text_510"]').show().addClass('other_ues');
		}else{
			$('.reg_buy input[name="form_text_510"]').hide().removeClass('other_ues');
		}
	});

	
	$('#center').on('click', '.reg_buy .buy_but', function(){
		if($(this).hasClass('but_av')){
			$('.ref_er, .ref_er2').remove();
			var errors = false;
			var ex_count = $('.active2').size();
			if(ex_count == 0){
				errors = true;
			}
			var er_c = 0;
			$('.reg_buy #step3 input[type="text"]').each(function(){
				var te2 = $(this).attr('placeholder');
				if($(this).parent().hasClass('y_ex') || $(this).parent().hasClass('com_b')){
					if(te2 != 'Имя коллеги' && te2 != 'Фамилия коллеги' && te2 != 'Должность коллеги' && te2 != 'E-mail коллеги' && te2 != 'Индекс' && te2 != 'Город' && te2 != 'Страна (other)' && te2 != 'http://'){
						var te2 = $(this).attr('placeholder');
						var val = $(this).val();
						if(val == '' && !$(this).next().hasClass('ref_er2')){
							$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Не заполнено поле "'+te2+'".</p>');
							er_c++;
						}
						if(val != '' && $(this).next().hasClass('ref_er2')){
							$(this).css({'border':'none'});
							//$(this).next().remove();
						}
						var rus = /а|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я/gi;
						if(val != '' && rus.test(val) && !$(this).next().hasClass('ref_er2')){
							$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Используйте английский язык".</p>');
							er_c++;
						}
						if(val != '' && !rus.test(val) && $(this).next().hasClass('ref_er2')){
							$(this).css({'border':'none'});
							//$(this).next().remove();
						}
						var mail = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
						if(te2 == 'E-mail' || te2 == 'Введите E-mail ещё раз' || te2 == 'E-mail коллеги'){
							if(val != '' && !mail.test(val) && !$(this).next().hasClass('ref_er2')){
								$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Введите корректно Ваш E-mail.</p>');
								er_c++;
							}
							if(val != '' && mail.test(val) && $(this).next().hasClass('ref_er2')){
								$(this).css({'border':'none'});
								//$(this).next().remove();
							}
						}
						var number = /^\d+$/;
						if(te2 == 'Телефон' || te2 == 'Индекс'){
							if(!number.test(val) && !$(this).next().hasClass('ref_er2')){
								$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Номер должен состоять только из цифр.</p>');
								er_c++;
							}
							if(number.test(val) && $(this).next().hasClass('ref_er2')){
								$(this).css({'border':'none'});
								//$(this).next().remove();
							}
						}
						/*var site = /^(http?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
						if(te2 == 'http://'){
							if(!site.test(val) && !$(this).next().hasClass('ref_er2')){
								$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Введите корректно адрес сайта.</p>');
								er_c++;
							}
							if(site.test(val) && $(this).next().hasClass('ref_er2')){
								$(this).css({'border':'none'});
								//$(this).next().remove();
							}
						}*/
						if(te2 == 'Введите логин/гостевое имя'){
							var th = $(this);
							if(val != ''){
								$('#ex_login').load('/ajax/ex_login.php', {login: val}, function(response, status, xhr){
									if(response > 0){
										th.css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Данный логин уже занят.</p>');
										er_c++;
									}else{
										if(th.next().hasClass('ref_er2') && rus.test(val)){
											//th.next().remove();
											th.css({'border':'none'});
											th.after('<span class = "ajax_yes"></span>');
										}
									}
								});
							}
						}
						if(val != '' && !$(this).next().hasClass('ref_er2')){
							$(this).after('<span class = "ajax_yes2"></span>');
						}else{
							if($(this).next().hasClass('ajax_yes2')){
								//$(this).next().remove();
							}
							if($(this).next().hasClass('ref_er2')){
								//$(this).next().next().remove();
							}
						}
						if(val != '' && !$(this).next().hasClass('ref_er2')){
							$(this).after('<span class = "ajax_yes2"></span>');
						}else{
							if($(this).next().hasClass('ajax_yes2')){
								//$(this).next().remove();
							}
						}
						var val = $(this).val();
						if(val == '' && !$(this).next().hasClass('ref_er2')){
							$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Не заполнено поле "'+te2+'".</p>');
							er_c++;
						}
						if(val != '' && $(this).next().hasClass('ref_er2')){
							$(this).css({'border':'none'});
							//$(this).next().remove();
						}
						var rus = /а|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я/gi;
						if(val != '' && rus.test(val) && !$(this).next().hasClass('ref_er2')){
							$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Используйте английский язык".</p>');
							er_c++;
						}
						if(val != '' && !rus.test(val) && $(this).next().hasClass('ref_er2')){
							$(this).css({'border':'none'});
							//$(this).next().remove();
						}
						var mail = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
						if(te2 == 'E-mail' || te2 == 'Введите E-mail ещё раз' || te2 == 'E-mail коллеги'){
							if(val != '' && !mail.test(val) && !$(this).next().hasClass('ref_er2')){
								$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Введите корректно Ваш E-mail.</p>');
								er_c++;
							}
							if(val != '' && mail.test(val) && $(this).next().hasClass('ref_er2')){
								$(this).css({'border':'none'});
								//$(this).next().remove();
							}
						}
						var number = /^\d+$/;
						if(te2 == 'Телефон' || te2 == 'Индекс'){
							if(!number.test(val) && !$(this).next().hasClass('ref_er2')){
								$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Номер должен состоять только из цифр.</p>');
								er_c++;
							}
							if(number.test(val) && $(this).next().hasClass('ref_er2')){
								$(this).css({'border':'none'});
								//$(this).next().remove();
							}
						}
						/*var site = /^(http?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
						if(te2 == 'http://'){
							if(!site.test(val) && !$(this).next().hasClass('ref_er2')){
								$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Введите корректно адрес сайта.</p>');
								er_c++;
							}
							if(site.test(val) && $(this).next().hasClass('ref_er2')){
								$(this).css({'border':'none'});
								//$(this).next().remove();
							}
						}*/
						if(te2 == 'Введите логин/гостевое имя'){
							var th = $(this);
							if(val != ''){
								$('#ex_login').load('/ajax/ex_login.php', {login: val}, function(response, status, xhr){
									if(response > 0){
										th.css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Данный логин уже занят.</p>');
										er_c++;
									}else{
										if(th.next().hasClass('ref_er2') && rus.test(val)){
											//th.next().remove();
											th.css({'border':'none'});
											th.after('<span class = "ajax_yes"></span>');
										}
									}
								});
							}
						}
						if(val != '' && !$(this).next().hasClass('ref_er2')){
							$(this).after('<span class = "ajax_yes2"></span>');
						}else{
							if($(this).next().hasClass('ajax_yes2')){
								//$(this).next().remove();
							}
							if($(this).next().hasClass('ref_er2')){
								//$(this).next().next().remove();
							}
						}
						if(val != '' && !$(this).next().hasClass('ref_er2')){
							$(this).after('<span class = "ajax_yes2"></span>');
						}else{
							if($(this).next().hasClass('ajax_yes2')){
								//$(this).next().remove();
							}
						}
					}
					
					/*if(te2 == 'Индекс'){
				
						if(val == '' && !$(this).next().hasClass('ref_er2')){
							if(!$(this).next().next().hasClass('ref_er2')){
								$(this).css({'border':'1px solid #ff1111'}).next().after('<span class = "not_valid2"></span>').after('<p class = "ref_er2">Не заполнено поле "'+te+'".</p>');
							}
						}
						var rus = /а|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я/gi;
						if(val != '' && rus.test(val)){
							if($(this).next().next().hasClass('ref_er2')){
								$(this).next().next().remove();
								$('.not_valid2').remove();
							}
							$(this).css({'border':'1px solid #ff1111'}).next().after('<span class = "not_valid2"></span>').after('<p class = "ref_er2">Используйте английский язык".</p>');
						}
						
						
						var number = /^\d+$/;
						if(val != '' && !rus.test(val) && !number.test(val)){
							if($(this).next().next().hasClass('ref_er2')){
								$(this).next().next().remove();
								$('.not_valid2').remove();
							}
							$(this).css({'border':'1px solid #ff1111'}).next().after('<span class = "not_valid2"></span>').after('<p class = "ref_er2">Номер должен состоять только из цифр.</p>');
						}
						if(val != '' && !rus.test(val) && number.test(val)){
							if($(this).next().next().hasClass('ref_er2')){
								$(this).next().next().remove();
								$('.not_valid2').remove();
							}
							$(this).css({'border':'none'});
							$('.not_valid2').remove();
						}
					}*/
					if(te2 == 'Город'){
						
						if(val == '' && !$(this).next().hasClass('ref_er2')){
							if(!$(this).next().hasClass('ref_er2')){
								$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid3"></span>').after('<p class = "ref_er2">Не заполнено поле "'+te+'".</p>');
							}
						}
						var rus = /а|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я/gi;
						if(val != '' && rus.test(val)){
							if($(this).next().hasClass('ref_er2')){
								$(this).next().remove();
								$('.not_valid3').remove();
							}
							$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid3"></span>').after('<p class = "ref_er2">Используйте английский язык".</p>');
						}
						if(val != '' && !rus.test(val)){
							if($(this).next().hasClass('ref_er2')){
								$(this).next().remove();
								$('.not_valid3').remove();
							}
							$(this).css({'border':'none'});
						}
					}
					
				}
			});
			var er_c2 = 0;
			$('.reg_buy textarea').each(function(){
				if($(this).parent().hasClass('y_ex') || $(this).parent().hasClass('com_b')){
					var val = $(this).text();
					var val2 = $(this).attr('placeholder');
					var va = $(this).val();
					if(val2 == ''){
						//console.log(23);
						$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Не заполнено поле "'+val2+'".</p>');
						er_c2++;
					}
					if(va != '' && $(this).next().hasClass('ref_er2')){
						$(this).css({'border':'none'});
						//$(this).next().remove();
						er_c2--;
					}
					var rus = /а|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я/gi;
					if(va != '' && rus.test(va)){
						$(this).css({'border':'1px solid #ff1111'}).after('<span class = "not_valid"></span>').after('<p class = "ref_er2">Используйте английский язык".</p>');
						er_c2++;
					}
					if(va != '' && !rus.test(va) && $(this).next().hasClass('ref_er2')){
						$(this).css({'border':'none'});
						//$(this).next().remove();
						er_c2--;
					}
					if(val != '' && !$(this).next().hasClass('ref_er2')){
						$(this).after('<span class = "ajax_yes2"></span>');
					}else{
						if($(this).next().hasClass('ajax_yes2')){
							//$(this).next().remove();
						}
					}
				}
			});
			var active_1 = $('.reg_buy .active_1').size();
			var active_2 = $('.reg_buy .active_2').size();
			var active_3 = $('.reg_buy .active_3').size();
			var active_4 = $('.reg_buy .active_4').size();
			var areas = active_1 + active_2 + active_3 + active_4;
			if(!$('#evenin').hasClass('y_ex')){
				areas = 9999999;
			}
			//$('.reg_buy .ex_agree, .reg_buy .er_area').remove();
			var agree = $('.reg_buy .active3').size();
			if(areas == 0 || agree == 0){
				//console.log('test');
				if(!$('.reg_buy #t_step3').prev().hasClass('ref_er2')){
					$('.reg_buy #t_step3').before('<span class = "ref_er2" style = "margin-top: 10px;display: block;">Внимание! Некоторые поля не заполнены или заполнены некорректно!</span>');
				}
				errors = true;
			}else{
				if($('.reg_buy #t_step3').prev().hasClass('ref_er2')){
					//$('.reg_buy #t_step3').prev().remove();
				}
				errors = false;
			}
			var bb_c = $('.bb').size();
			if(bb_c == 0){
				if(!$('.reg_buy #t_step3').prev().hasClass('ref_er2')){
					$('.reg_buy #t_step3').before('<span class = "ref_er2" style = "margin-top: 10px;display: block;">Вы не выбрали ни одного мероприятия!</span>');
				}
				errors = true;
			}else{
				if($('.reg_buy #t_step3').prev().hasClass('ref_er2')){
					//$('.reg_buy #t_step3').prev().remove();
				}
				errors = false;
			}
			//console.log(er_c);
			//console.log(er_c2);
			
			//Вид деятельности обязателен
			var vid_u = 0;
			$('#bview_5 td').each(function(){
				if($(this).hasClass('active_5')){
					vid_u++;
				}
			});
			if(vid_u == 0){
				$('.com_b .ex_sel_5').after('<p class = "ref_er2">Не выбрано поле "Вид деятельности".</p>');
			}else{
				if($('.com_b .ex_sel_5').next().hasClass('ref_er2')){
					$('.com_b .ex_sel_5').next().remove();
				}
			}
			//Приоритетные направления обязательны
			var count_pr_n = 0;
			if($('#morning').hasClass('y_ex')){
				$('#bview_1 td').each(function(){
					if($(this).hasClass('active_1')){
						count_pr_n++;
					}
				});
				$('#bview_2 td').each(function(){
					if($(this).hasClass('active_2')){
						count_pr_n++;
					}
				});
				$('#bview_3 td').each(function(){
					if($(this).hasClass('active_3')){
						count_pr_n++;
					}
				});
				$('#bview_4 td').each(function(){
					if($(this).hasClass('active_4')){
						count_pr_n++;
					}
				});
				$('#bview_6 td').each(function(){
					if($(this).hasClass('active_6')){
						count_pr_n++;
					}
				});
				$('#bview_7 td').each(function(){
					if($(this).hasClass('active_7')){
						count_pr_n++;
					}
				});
				if(count_pr_n == 0){
					$('.ex_sel_7').after('<p class = "ref_er2">Не выбрано поле "Приоритетное направление".</p>');
				}else{
					if($('.ex_sel_7').next().hasClass('ref_er2')){
						$('.ex_sel_7').next().remove();
					}
				}
			}
			/*console.log(er_c);
			console.log(er_c2);
			console.log(count_pr_n);
			console.log(vid_u);*/
			var er_all = er_c + er_c2;
			//утро
			if($('#morning').hasClass('y_ex') && !$('#both_time').hasClass('y_ex')){
				console.log('утро');
				console.log(er_all);
				console.log(vid_u);
				console.log(count_pr_n);
				if(er_all != 0 || count_pr_n == 0 || vid_u == 0){
					$('.reg_buy #t_step3').before('<span class = "ref_er2" style = "margin-top: 10px;display: block;">Внимание! Некоторые поля не заполнены или заполнены некорректно!</span>');
				}else{
					if($('.reg_buy #t_step3').prev().hasClass('ref_er2')){
						$('.reg_buy #t_step3').prev().remove();
					}
				}
			}
			//вечер
			if($('#evernyng').hasClass('y_ex')){
				console.log('вечер');
				/*console.log('everning');
				console.log(er_all);
				console.log(vid_u);*/
				if(er_all != 0 || vid_u == 0){
					$('.reg_buy #t_step3').before('<span class = "ref_er2" style = "margin-top: 10px;display: block;">Внимание! Некоторые поля не заполнены или заполнены некорректно!</span>');
				}else{
					if($('.reg_buy #t_step3').prev().hasClass('ref_er2')){
						$('.reg_buy #t_step3').prev().remove();
					}
				}
			}
			//утро+вечер
			if($('#morning').hasClass('y_ex') && $('#both_time').hasClass('y_ex')){
				console.log('утро+вечер');
				/*console.log('everning');
				console.log(er_all);
				console.log(vid_u);*/
				if(er_all != 0 || vid_u == 0 || count_pr_n == 0){
					$('.reg_buy #t_step3').before('<span class = "ref_er2" style = "margin-top: 10px;display: block;">Внимание! Некоторые поля не заполнены или заполнены некорректно!</span>');
				}else{
					if($('.reg_buy #t_step3').prev().hasClass('ref_er2')){
						$('.reg_buy #t_step3').prev().remove();
					}
				}
			}
			var errors_new = 0;
			if($('#morning').hasClass('y_ex')){
				errors_new = count_pr_n;
			}else{
				errors_new = vid_u;
			}
			//console.log(count_pr_n);
			//отправка данных
			/*console.log(er_c);
			console.log(er_c2);
			console.log(areas);
			console.log(agree);
			console.log(errors);
			console.log($('.ref_er2').size());*/
			//console.log($('.ref_er2').size());
			/*console.log('____________');
			console.log(errors);
			console.log(er_all);
			console.log(areas);
			console.log(agree);
			console.log($('.ref_er2').size());
			console.log(errors_new);*/
			//console.log('no');
			if(!errors && (er_all == 0 && areas > 0 && agree > 0 && $('.ref_er2').size() == 0 && errors_new > 0)){
			
				//console.log('yes');
				
				var name_c = $('input[placeholder="Название компании"]').val();
				var det = '';
				$('#form_dropdown_SIMPLE_QUESTION_677 option').each(function(){
					if($(this).text() == $(this).parent().next().children('.sel_name').text()){
						det = $(this).val();
					}
				});
				var address = $('input[placeholder="Фактический адрес компании"]').val();
				var index = $('input[placeholder="Индекс"]').val();
				var town = $('input[placeholder="Город"]').val();
				var country = '';
				$('#form_dropdown_SIMPLE_QUESTION_678 option').each(function(){
					if($(this).text() == $(this).parent().next().children('.sel_name').text()){
						country = $(this).val();
					}
				});
				//var area_b = $('.ex_sel .sel_name').text();
				var name = $('input[placeholder="Имя"]').val();
				var f_name = $('input[placeholder="Фамилия"]').val();
				var dolj = $('input[placeholder="Должность"]').val();
				var phone = $('input[placeholder="Телефон"]').val();
				var mail = $('input[name="form_text_220"]').val();
				var site = $('input[name="form_text_222"]').val();

				var name_c1 = $('input[name="form_text_223"]').val();
				var f_name_c1 = $('input[name="form_text_224"]').val();
				var dolj_c1 = $('input[name="form_text_225"]').val();
				var mail_c1 = $('input[name="form_text_226"]').val();
				var name_c2 = $('input[name="form_text_227"]').val();
				var f_name_c2 = $('input[name="form_text_228"]').val();
				var dolj_c2 = $('input[name="form_text_229"]').val();
				var mail_c2 = $('input[name="form_text_230"]').val();
				var name_c3 = $('input[name="form_text_231"]').val();
				var f_name_c3 = $('input[name="form_text_232"]').val();
				var dolj_c3 = $('input[name="form_text_233"]').val();
				var mail_c3 = $('input[name="form_text_234"]').val();
				
				var login = $('input[placeholder="Введите логин/гостевое имя"]').val();
				var pas = $('input[placeholder="Введите пароль"]').val();
				
				var textComp = $('textarea[placeholder="Введите краткое описание"]').val();
				
				
				var t1 = '';
				var t2 = '';
				var t3 = '';
				var t4 = '';
				var t6 = '';
				var t7 = '';
				$('.reg_buy .active_1').each(function(){
					t1 = t1+'|'+$(this).attr('for');
				});
				$('.reg_buy .active_2').each(function(){
					t2 = t2+'|'+$(this).attr('for');
				});
				$('.reg_buy .active_3').each(function(){
					t3 = t3+'|'+$(this).attr('for');
				});
				$('.reg_buy .active_4').each(function(){
					t4 = t4+'|'+$(this).attr('for');
				});
				$('.reg_buy .active_6').each(function(){
					t6 = t6+'|'+$(this).attr('for');
				});
				$('.reg_buy .active_7').each(function(){
					t7 = t7+'|'+$(this).attr('for');
				});
				var areas = t1+'|'+t2+'|'+t3+'|'+t4+'|'+t6+'|'+t7;
				
				var detNew = '';
				$('.reg_buy .active_5').each(function(){
					detNew = detNew+'|'+$(this).attr('for');
				});
				
				var other_ues = $('.other_ues').size();
				if(other_ues > 0){
					other_country = $('.reg_buy input[name="form_text_510"]').val();
				}else{
					other_country = '';
				}
				
				var formId = $('.bb').children().children().attr('for');
				
				//console.log('yes');
				var morn = 0;
				var ever = 0;
				if($('.mon_b').children().hasClass('active2')){
					morn = 1;
				}
				if($('.eve_b').children().hasClass('active2')){
					ever = 1;
				}
				
				var eSob = $('.bb').children().next().next().children().attr('for').slice(0,4).toLowerCase();
				/*console.log(morn);
				console.log(ever);
				console.log(eSob);
				console.log(mail);*/
				//console.log('yes');
				$('.reg_buy #resp_ajax').load('/ajax/bu_registr.php', {name_c:name_c, det:detNew, address:address, index:index, town:town, country:country, name:name, f_name:f_name, dolj:dolj, phone:phone, mail:mail, site:site, name_c1:name_c1, f_name_c1:f_name_c1, dolj_c1:dolj_c1, mail_c1:mail_c1, name_c2:name_c2, f_name_c2:f_name_c2, dolj_c2:dolj_c2, mail_c2:mail_c2, name_c3:name_c3, f_name_c3:f_name_c3, dolj_c3:dolj_c3, mail_c3:mail_c3, login:login, pas:pas, textComp:textComp, areas:areas, other_country:other_country, morn:morn, ever:ever, eSob:eSob, formId:formId}, 
				function(response, status, xhr){
					if(response > 0){
						//console.log(12);
						document.location.href = '/registr/yes_g.php';
						//$(location).href('/registr/yes.php');
					}
				});
			}
		}
	});
	
	//ajax upload фотографии (от 6 до 12)
	var parPhotos = 0;
	new AjaxUpload($('.photos'), {
		action: '/ajax/photos.php',
		name: 'uploadfile',
		onSubmit: function(file0, ext0){
			if (! (ext0 && /^(jpg|jpeg)$/.test(ext0))){ 
				$('.photos').after('<p class = "ref_er2">Формат фотографий должен быть только JPEG.</p>');
				return false;
			}else{
				if($('.photos').next().hasClass('ref_er2')){
					$('.photos').next().remove();
				}
			}
			$('.photos').after('<span class = "ajax_load"></span>');
			if($('.act_file').size() == 12){
				$('.photos').after('<p class = "ref_er2">Максимум 12 фотографий.</p>');
				$('.photos').next().delay(3000).remove();
				if($('.photos').next().hasClass('ajax_load')){
					$('.photos').next().remove();
				}
				if($('.photos').next().next().hasClass('ajax_load')){
					$('.photos').next().next().remove();
				}
				if($('.photos').next().next().next().hasClass('ajax_load')){
					$('.photos').next().next().next().remove();
				}
				return false;
			}
		},
		onComplete: function(file0, response0){
			$('.phot_s:eq('+parPhotos+')').addClass('act_file');
			parPhotos++;
			$('.photos').text('Upload '+parPhotos+' photos (jpeg only. min 6, max. 12)');
			if(parPhotos > 5){
				$('.photos').after('<span class = "ajax_yes"></span>');
			}
			if($('.photos').next().hasClass('ajax_load')){
				$('.photos').next().remove();
			}
			if($('.photos').next().next().hasClass('ajax_load')){
				$('.photos').next().next().remove();
			}
		}
	});
	//ajax upload логотип
	new AjaxUpload($('.logo_f'), {
		action: '/ajax/logotip.php',
		name: 'uploadfile',
		onSubmit: function(file0, ext0){
			if (! (ext0 && /^(jpg|jpeg)$/.test(ext0))){ 
				$('.logo_f').after('<p class = "ref_er2">Формат фотографий должен быть только JPEG.</p>');
				return false;
			}else{
				if($('.logo_f').next().hasClass('ref_er2')){
					$('.logo_f').next().remove();
				}
			}
			$('.logo_f').after('<span class = "ajax_load"></span>');
		},
		onComplete: function(file0, response0){
			$('.logo_file').addClass('act_file2');
			$('.logo_file').after('<span class = "ajax_yes"></span>');
			if($('.logo_f').next().hasClass('ajax_load')){
				$('.logo_f').next().remove();
			}
			if($('.logo_f').next().next().hasClass('ajax_load')){
				$('.logo_f').next().next().remove();
			}
		}
	});
	//ajax upload персональное фото
	new AjaxUpload($('.p_fo'), {
		action: '/ajax/pers_photo.php',
		name: 'uploadfile',
		onSubmit: function(file0, ext0){
			if (! (ext0 && /^(jpg|jpeg)$/.test(ext0))){ 
				$('.p_fo').after('<p class = "ref_er2">Формат фотографий должен быть только JPEG.</p>');
				return false;
			}else{
				if($('.p_fo').next().hasClass('ref_er2')){
					$('.p_fo').next().remove();
				}
			}
			$('.p_fo').after('<span class = "ajax_load"></span>');
		},
		onComplete: function(file0, response0){
			$('.pers_photo').addClass('act_file3');
			$('.pers_photo').after('<span class = "ajax_yes"></span>');
			if($('.p_fo').next().hasClass('ajax_load')){
				$('.p_fo').next().remove();
			}
			if($('.p_fo').next().next().hasClass('ajax_load')){
				$('.p_fo').next().next().remove();
			}
		}
	});
});


function photos(elem){
	if($(elem).val() != ''){
		var col = 0;
		$('.phot_s').each(function(){
			var t = $(this).val();
			if(t != ''){
				col++;
			}
		});
		//console.log(file);
		if(col > 0){
			$('.photos').text('Upload '+col+' photos (jpeg only. min 6, max. 12)');
			$(elem).addClass('act_file');
		}else{
			$('.photos').text('Upload up to photos (jpeg only. min 6, max. 12)');
		}
	}
}
function photos2(elem){
	if($(elem).val() != ''){
		$(elem).addClass('act_file2');
	}
}
function photos3(elem){
	if($(elem).val() != ''){
		$(elem).addClass('act_file3');
	}
}
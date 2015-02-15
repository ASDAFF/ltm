$(document).ready(function(){
	
	var MESS = {
			en : 
			{
				show_contries: "Show all countries",
				hide_contries: "Hide list of countries",
			},
			ru :
			{
				show_contries: "Показать все страны",
				hide_contries: "Скрыть список",
			}
	};
	
	
	/*DropDown разворачивание*/
	$("div.create-company").on("click", ".dropdown-name", function(event){
		//$(this).siblings("ul.dropdown-items").toggle(300);
		
		//сворачивание селектов и приоритетных направлений при клике мимо
		var selectDiv = $(this).siblings("ul.dropdown-items");
		var selectDivId = selectDiv.attr("id");
		
	    if (selectDiv.css('display') != 'block') {
	    	selectDiv.show(300);
	 
	        var firstClick = true;
	        $(document).bind('click.myEvent-'+selectDivId, function(e) {
	            if (!firstClick && $(e.target).closest('#'+selectDivId).length == 0) {
	            	selectDiv.hide(300);
	                $(document).unbind('click.myEvent-'+selectDivId);
	            }
	            firstClick = false;
	        });
	    }
	 
	    event.preventDefault();
	});
	
	
	/*DropDown выбор*/
	$("div.create-company").on("click", "ul.dropdown-items li", function(){
		var selectedLi = $(this);
		var selectedId = selectedLi.data("id");
		var ul = selectedLi.closest("ul");
		var select = ul.siblings("select");
		var name = ul.siblings(".dropdown-name");

		
		select.val(selectedId);
		select.trigger("change");
		name.text(selectedLi.text());
		name.trigger("click");
	});
	
	
	
	/*Групповые чекбоксы приоритетные направления разворачивание*/
	$("div.priority-wrap").on("click", ".priority-toggle", function(){ 
		//$(this).siblings("div.priority-items").toggle(100); 
		$this = $(this);
		var selectDiv = $(this).closest(".priority-check-all").siblings("div.priority-items"); 
		var selectDivId = selectDiv.attr("id");
		
	    if (selectDiv.css('display') != 'block') {
	    	selectDiv.show(100);
	    	if($this.hasClass("priority-switch"))
	    	{
	    		$this.children("ins").text(MESS[LANGUAGE_ID]['hide_contries']);
	    	}
	    	else
	    	{
	    		$this.siblings(".priority-switch").children("ins").text(MESS[LANGUAGE_ID]['hide_contries']);
	    	}
	    	
	        var firstClick = true;
	        $(document).bind('click.myEvent-'+selectDivId, function(e) {
	            if (!firstClick && $(e.target).closest('#'+selectDivId).length == 0) {
	            	selectDiv.hide(100);
	    	    	if($this.hasClass("priority-switch"))
	    	    	{
	    	    		$this.children("ins").text(MESS[LANGUAGE_ID]['show_contries']);
	    	    	}
	    	    	else
	    	    	{
	    	    		$this.siblings(".priority-switch").children("ins").text(MESS[LANGUAGE_ID]['show_contries']);
	    	    	}
	                $(document).unbind('click.myEvent-'+selectDivId);
	            }
	            firstClick = false;
	        });
	    }
	 
	    event.preventDefault();
	});
	
	/*Групповые чекбоксы приоритетные направления выбор поодиночно*/
	$("div.priority-wrap").on("click", ".priority-items input:checkbox", function(){
		var checkbox = $(this);
		var checkboxId = checkbox.attr("id");
		var label = checkbox.siblings("label[for="+checkboxId+"]");
	
		/*Если выставку выбрали меняем стиль лейбла*/
		if(label.hasClass("active-group"))
		{
			label.removeClass("active-group");
			
			/*удаляем чекбокс выбраны все, если щелкнут*/
			var checkboxAll = checkbox.closest(".priority-items").siblings(".priority-check-all").find("input:checkbox");
		
			if(checkboxAll.prop("checked"))
			{
				checkboxAll.prop("checked", false);
				checkboxAll.siblings("label[for="+checkboxAll.attr("id")+"]").removeClass("active-all");
			}
		}
		else
		{
			label.addClass("active-group");
		}
	});
	
	/*Отключаем клик по лейблу*/
	$("div.priority-wrap").on("click", ".priority-check-all label", function(event){
		event.stopPropagation();
	});
	
	/*Групповые чекбоксы приоритетные направления выбор всех*/
	$("div.priority-wrap").on("click", ".priority-check-all input:checkbox", function(event){
		event.stopPropagation();
		
		var checkbox = $(this);
		var checkboxId = checkbox.attr("id");
		var label = checkbox.siblings("label[for="+checkboxId+"]");
	
		/*Если выбрали чекбокс меняем лейбл*/
		if(label.hasClass("active-all"))
		{
			label.removeClass("active-all");
		}
		else
		{ 
			label.addClass("active-all");
		}

		/*чекаем все чекбоксы*/
		var items = checkbox.closest(".priority-check-all").siblings(".priority-items");
		
		var delay = 0;
		items.find("input:checkbox").each(function(ind, element){
			var checkboxPA = $(element);
			
			setTimeout(function () {
			if(checkbox.prop("checked") && !checkboxPA.prop("checked"))
			{
				checkboxPA.trigger("click");
			}
			else if(!checkbox.prop("checked") && checkboxPA.prop("checked"))
			{
				checkboxPA.trigger("click");
			}
			},delay+=12);
		});
	});
	
	//все приоритетные направления
	$("div.priority-wrap").on("click", ".priority-check-global input:checkbox", function(event){
		event.stopPropagation();
		
		var checkbox = $(this);
		var checkboxId = checkbox.attr("id");
		var label = checkbox.siblings("label[for="+checkboxId+"]");
		var priorityBlock = checkbox.closest(".priority-wrap");
		
		/*Если выбрали чекбокс меняем лейбл*/
		if(label.hasClass("active-global"))
		{
			label.removeClass("active-global");
		}
		else
		{ 
			label.addClass("active-global");
		}
		
		setTimeout(function () {
			var delay = 0;
			priorityBlock.find(".priority-check-all input:checkbox").each(function(ind, element){
				var checkboxPA = $(element);

				setTimeout(function () {
					if(checkbox.prop("checked") && !checkboxPA.prop("checked"))
					{
						checkboxPA.trigger("click");
					}
					else if(!checkbox.prop("checked") && checkboxPA.prop("checked"))
					{
						checkboxPA.trigger("click");
					}
				},delay+=500);
			});
		},150);
		
	});
	
});

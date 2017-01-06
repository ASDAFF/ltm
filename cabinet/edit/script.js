$(document).ready(function(){

	var correctly = true;
	
	var MESS = {
			en : 
			{
				show_contries: "Show all countries",
				hide_contries: "Hide list of countries",
				capslock: "Caps lock should be off"
			},
			ru :
			{
				show_contries: "Показать все страны",
				hide_contries: "Скрыть список",
				capslock: "Внимание: нажат CapsLock!"
			}
	};

	/**
	 * Текущее состояние CapsLock
	 *  - null : неизвестно
	 *  - true/false : CapsLock включен/выключен
	 */
	var capsLockEnabled = null;
	function getChar(event) {
		if (event.which == null) {
			if (event.keyCode < 32) return null;
			return String.fromCharCode(event.keyCode) // IE
		}

		if (event.which != 0 && event.charCode != 0) {
			if (event.which < 32) return null;
			return String.fromCharCode(event.which) // остальные
		}

		return null; // специальная клавиша
	}

	if (navigator.platform.substr(0, 3) != 'Mac') { // событие для CapsLock глючит под Mac
		document.onkeydown = function(e) {
			if (e.keyCode == 20 && capsLockEnabled !== null) {
				capsLockEnabled = !capsLockEnabled;
			}
		}
	}

	document.onkeypress = function(e) {
		e = e || event;

		var chr = getChar(e);
		if (!chr) return // special key

		if (chr.toLowerCase() == chr.toUpperCase()) {
			// символ, не зависящий от регистра, например пробел
			// не может быть использован для определения CapsLock
			return;
		}

		capsLockEnabled = (chr.toLowerCase() == chr && e.shiftKey) || (chr.toUpperCase() == chr && !e.shiftKey);
	}

	$(".guest-form").on("keyup", ".data-control input, .data-control textarea", function(){
		if(capsLockEnabled) {
			showErrorMessage(this,MESS[LANGUAGE_ID]["capslock"]);
		} else {
			hideErrorMessage(this,MESS[LANGUAGE_ID]["capslock"]);
		}
	});
	
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

	$(".guest-form").on("click", ".send input[type=submit]", function(e){
		e.preventDefault();
		var errorStatus = $(".input-error-border").length;
		if(errorStatus == 0) {
			$(this).closest('form').submit();
		} else {
			$('body,html').animate({scrollTop:300},300);
		}
	});

	function showErrorMessage(element, erText)
	{
		var input = $(element);

		if(input.length > 0) {
			var errorTextDiv = input.siblings(".input-error-text");

			if(errorTextDiv.length == 0) {
				$("<div></div>").addClass("input-error-text").html(erText+"<br>").insertAfter(input);
			}
			else if(errorTextDiv.length > 0) {
				//если есть, то не добавляем
				var oldText = errorTextDiv.html();

				if(oldText.length > 0) {
					if(oldText.indexOf(erText) == -1) {
						errorTextDiv.html(oldText + erText+"<br>");
					}
				}
				else {
					errorTextDiv.html(erText+"<br>");
				}
			}

			input.addClass("input-error-border");
			correctly = false;
		}
	}

	function hideErrorMessage(element, erText)
	{
		var input = $(element);

		if(input.length > 0) {

			var errorTextDiv = input.siblings(".input-error-text");

			//если ошибок нет стави галочку, что все ок
			if(errorTextDiv.length > 0) {
				//если есть, то не добавляем
				var oldText = errorTextDiv.html();

				if(oldText.length > 0) {
					if(oldText.indexOf(erText) >= 0) {
						errorTextDiv.html(oldText.replace(erText+"<br>", ""));
					}
				}
				else {
					errorTextDiv.remove();
				}
			}

			if(errorTextDiv.length > 0 && errorTextDiv.html().length == 0) {
				errorTextDiv.remove();
				input.removeClass('input-error-border');
			}
		}
	}
});

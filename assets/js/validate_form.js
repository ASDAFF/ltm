$(function() {
	var en = {
			"require":"This field is required.",
			"email":"This field must be formatted as an email.",
			"web":"Web-address is incorrect, please check.",
			"phone":"This field must be formatted as phone number.",
			"minLength": "Min length is 3 symbols.",
			"maxLength": "Max length is 16 symbols.",
			"login": "Login must consist of letters, numbers or underscores.",
			"conf_email": "Email does not match.",
			"exhib":"Please select at least one event.",
			"priority": "Priority destinations are not selected.",
			"minPhoto": "Minimum 6 photos.",
			"en": "Only English characters.",
			"area":"Area of the business is not selected.",
			"space":"Space is not valid.",
			"conf_pass":"Passwords do not match.",
			"en_num":"Only English characters and numbers.",
			"login_busy": "Login has already been taken.",
			"capslock": "Caps lock should be off",
			"capitals": "Please don't use 2 or more capital letters one after another"
	};
	
	var ru = {
			"require":"Это поле является обязательным.",
			"email":"Некорректный E-mail.",
			"web":"Это поле должно быть отформатирован как URL.",
			"phone":"Это поле должно быть в формате телефонного номера.",
			"minLength": "Минимальная длина составляет 3 символа.",
			"maxLength": "Максимальная длина составляет 16 символов.",
			"login": "Логин должен состоять из букв, цифр или символов подчеркивания.",
			"conf_email": "E-mail не совпадают.",
			"exhib":"Вы не выбрали мероприятие.",
			"priority": "Приоритетные направления не выбраны.",
			"minPhoto": "Минимум 6 фотографий.",
			"en": "Только латинские символы.",
			"area":"Вид деятельности не выбран.",
			"space":"Пробел недопустим.",
			"conf_pass":"Пароли не совпадают.",
			"en_num":"Только латинские символы и цифры.",
			"login_busy": "Логин уже занят.",
			"capslock": "Внимание: нажат CapsLock!",
			"capitals": "Пожалуйста, не используйте две и более заглавные буквы в слове"
	};
	

	$errorText = {"en":en, "ru":ru};
	


});

function equalsValue(validator1, validator2)
{
	var email = $(validator1).val();
	var confemail = $(validator2).val();
	return email == confemail;
}

function showErrorMessage(element, erText, hideLabel)
{
	var input = $(element);
	
	if(input.length > 0)
	{
		var errorTextDiv = input.siblings(".input-error-text");
		var status = input.siblings(".input-status");
		
		if(errorTextDiv.length == 0)
		{
			$("<div></div>").addClass("input-error-text").html(erText+"<br>").insertAfter(input);
		}
		else if(errorTextDiv.length > 0)
		{
			//если есть, то не добавляем
			var oldText = errorTextDiv.html();
			
			if(oldText.length > 0)
			{
				if(oldText.indexOf(erText) == -1)
				{
					errorTextDiv.html(oldText + erText+"<br>");
				}
				
			}
			else
			{
				errorTextDiv.html(erText+"<br>");
			}
		}
		
		if(!hideLabel)
		{
			if(status.length == 0)
			{
				$("<div></div>").addClass("input-status input-error").insertAfter(input);
			}
			else
			{
				if(!status.hasClass("input-error"))
				{
					status.addClass("input-error");
				}
				
				if(status.hasClass("input-success"))
				{
					status.removeClass("input-success");
				}
				
				if(hideLabel)
				{
					status.remove();
				}
			}
		}
		
		
		input.css("border","1px solid #F00");
		correctly = false;
	}
	
}

function hideErrorMessage(element, erText, hideLabel)
{
	var input = $(element);
	
	if(input.length > 0)
	{
	
		var errorTextDiv = input.siblings(".input-error-text");
		var status = input.siblings(".input-status");
		
		//если ошибок нет стави галочку, что все ок
		if(errorTextDiv.length > 0)
		{
			//если есть, то не добавляем
			var oldText = errorTextDiv.html();
			
			if(oldText.length > 0)
			{
				if(oldText.indexOf(erText) >= 0)
				{
					errorTextDiv.html(oldText.replace(erText+"<br>", ""));
				}
			}
			else
			{
				errorTextDiv.remove();
			}
		}
		
		if(errorTextDiv.length > 0 && errorTextDiv.html().length == 0)
		{
			errorTextDiv.remove();
			input.css("border","");
		}
		
		//находим поновой, вдруг удалили
		errorTextDiv = input.siblings(".input-error-text");
		
		if(errorTextDiv.length == 0)
		{
			if(status.length == 0 && !hideLabel)
			{
				$("<div></div>").addClass("input-status input-success").insertAfter(input);
			}
			else
			{
				if(status.hasClass("input-error"))
				{
					status.removeClass("input-error");
				}
				
				if(!status.hasClass("input-success"))
				{
					status.addClass("input-success");
				}
				
				if(hideLabel)
				{
					status.remove();
				}
			}
		}
	}
	
}

//валидация

//английский язык
$(function() {
	$("#exhibition-tab-1 form").on("focusout", ".en", function(){
		var input = $(this);
		var value = input.val();
		
		if(!value.match(/^[^а-яА-Я]+$/) && value.length > 0)
		{
			showErrorMessage(this,$errorText[LANGUAGE_ID]["en"]);
		}
		else
		{
			hideErrorMessage(this,$errorText[LANGUAGE_ID]["en"], true);
		}
	});
});

//обязательные поля
$(function() {
	$("#exhibition-tab-1 form").on("focusout", ".require", function(){
		var input = $(this);
		var value = input.val();
		
		if(value.length <= 0)
		{
			showErrorMessage(this,$errorText[LANGUAGE_ID]["require"]);
		}
		else
		{
			hideErrorMessage(this,$errorText[LANGUAGE_ID]["require"]);
		}
	});
});



//email
$(function() {
	$("#exhibition-tab-1 form").on("focusout", ".email", function(){
		var input = $(this);
		var value = input.val();
		
		if(!value.match(/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*(\.[a-z]{2,8})+$/i) && value.length > 0)
		{
			showErrorMessage(this,$errorText[LANGUAGE_ID]["email"]);
		}
		else
		{
			hideErrorMessage(this,$errorText[LANGUAGE_ID]["email"],true);
		}
	});
});

/*Валидация формы участника*/
function validateRegFormP()
{
	correctly = true;
	
	//проверка на заполнение полей
	 $("#exhibition-tab-1 form input[type=text], #exhibition-tab-1 form textarea").each(function(ind, elem)
		{
			 $(elem).trigger("focusout");
		}
	 );
	 
	return correctly;
}
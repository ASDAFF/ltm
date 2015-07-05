$(function() {
	BX.addCustomEvent("onAjaxSuccess", photoUpload);
	
	//проверка имейла налету
	$("#REGISTER_FORM").on("keyup", "input[name=CONF_EMAIL]", function(eventObject){
		
		var inpConfEmail = $(this);
		var inpEmail = $("input[name=EMAIL]");
		
		if(inpEmail.length > 0)
		{
			var ConfEmail = inpConfEmail.val();
			var Email = inpEmail.val();
			
			var expr = new RegExp("^"+ConfEmail+".*");
			var errorTextDiv = inpConfEmail.siblings(".ajax-error-text");
			var errorText= "";
			
			if($(".registr_exh").length > 0)
			{
				errorText = "E-mail does not match";
			}
			else
			{
				errorText = "E-mail не совпадают";
			}
			
			if(!Email.match(expr))
			{
				if(errorTextDiv.length == 0)
				{
					$("<div></div>").addClass("ajax-error-text").text(errorText).insertAfter(inpConfEmail);
				}
			}
			else
			{
				if(errorTextDiv.length > 0)
				{
					errorTextDiv.remove();
				}
			}
		}
	});
	
	//копирования из названия компании
	$("#REGISTER_FORM").on("change", ".registr_exh input[name=COMPANY_NAME]", function(){
		
		var inpCompanyName = $(this);
		var inpCompanyNameForInvoice = $(".registr_exh input[name=COMPANY_NAME_FOR_INVOICE]");
		
		if(inpCompanyNameForInvoice.length > 0)
		{
			var CompanyName = inpCompanyName.val();
			var CompanyNameForInvoice = inpCompanyNameForInvoice.val();
			
			if(CompanyName == CompanyNameForInvoice || CompanyNameForInvoice.length == 0)
			{
				inpCompanyNameForInvoice.val(CompanyName);
			}
		}
	});
	
	//ajax проверка логина
	$("#REGISTER_FORM").on("focusout", "input[name=LOGIN]", function(){
		
		var loginInput = $(this);
		var login = $(this).val();
		var sid = $("#sessid").val();
		
		$.ajax({
			type: "POST",
			url: AjaxPatch.LOGIN,
			data: {SID:sid, LOGIN:login},
			success: function(data){
				data = BX.parseJSON(data);
				
				var status = loginInput.siblings(".ajax-status");
				var errorText = loginInput.siblings(".ajax-error-text");
				
				if(data.STATUS == "success")
				{
					if(status.length == 0)
					{
						$("<div></div>").addClass("ajax-status ajax-success").insertAfter(loginInput);
					}
					else
					{
						if(status.hasClass("ajax-error"))
						{
							status.removeClass("ajax-error");
						}
						
						if(!status.hasClass("ajax-success"))
						{
							status.addClass("ajax-success");
						}
					}
					
					if(errorText.length != 0)
					{
						errorText.remove();
					}
				}
				else
				{
					if(status.length == 0)
					{
						$("<div></div>").addClass("ajax-status ajax-error").insertAfter(loginInput);
					}
					else
					{
						if(status.hasClass("ajax-success"))
						{
							status.removeClass("ajax-success");
						}
						
						if(!status.hasClass("ajax-error"))
						{
							status.addClass("ajax-error");
						}
					}
					
					if(errorText.length == 0)
					{
						$("<div></div>").addClass("ajax-error-text").text(data.ERROR_TEXT).insertAfter(loginInput);
					}
					else
					{
						errorText.text(data.ERROR_TEXT);
					}
				}
			  }
		});
	});
	
	
	$.validity.setup({ scrollTo: true });
	
	//Клик по регистрации участника
	$("#REGISTER_FORM").on("click", ".registr_exh input[name=register_button]", function(){
		
		if($("input#ckeck_register").prop("checked") && validateRegFormP())
		{
			submitForm("Y");
		}
	});
	
	//Клик по регистрации гостя
	$("#REGISTER_FORM").on("click", ".registr_buy input[name=register_button]", function(){
		
		if($("input#ckeck_register").prop("checked") && validateRegFormB())
		{
			submitForm("Y");
		}
	});
	
	/*Выбор выставок участниками*/
	$("#register_form_content").on("click", ".registr_exh table.exh-select input:checkbox", function(){
		
		var checkbox = $(this);
		var checkboxId = checkbox.attr("id");
		var label = checkbox.siblings("label[for="+checkboxId+"]");
		var row = checkbox.closest("tr");
		var table = checkbox.closest("table.exh-select");
		
		/*Если на выставку нельзя зарегистрироваться не даем ее чекнуть*/
		if(row.hasClass("not-available"))
		{
			return false;
		}
		 
		/*Если выставку выбрали меняем стиль лейбла*/
		if(label.hasClass("active-exh"))
		{
			label.removeClass("active-exh");
		}
		else
		{
			label.addClass("active-exh");
		}
		
		var newText = "";
		/*отмечаем все выбранные выставки в спец поле*/
		table.find("input:checkbox:checked").each(function(ind, check){
			var row = $(check).closest("tr");
			if(!row.hasClass("not-available"))
			{
				var name = row.find("span.name_exhibition").text();
				newText += name + ", ";
			}

		});
		
		newText = newText.slice(0, -2);

		$("#selected-exhibition").text(newText);
	});
	
	/*Выбор выставки гостем*/
	$("#register_form_content").on("click", ".registr_buy table.exh-select input:checkbox", function(){
		
		var checkbox = $(this);
		var checkboxId = checkbox.attr("id");
		var label = checkbox.siblings("label[for="+checkboxId+"]");
		var row = checkbox.closest("tr");
		var table = checkbox.closest("table.exh-select");

		/*Если на выставку нельзя зарегистрироваться не даем ее чекнуть*/
		if(row.hasClass("not-available"))
		{
			return false;
		}
		
		/*Если выставку выбрали меняем стиль лейбла*/
		if(label.hasClass("active-exh"))
		{
			label.removeClass("active-exh");
		}
		else
		{
			label.addClass("active-exh");
		}
		
		/*если есть чекнутые выставки блокируем остальные*/
		if(table.find("input:checkbox:checked").length > 0)
		{
			table.find("tr").each(function(ind, element){
				var $row = $(element);
				if(!$row.hasClass("not-available") && $row.find("input:checkbox:checked").length == 0)
				{
					$row.addClass("not-available");
				}
				else if($row.find("input:checkbox:checked").length > 0)
				{
					var bMorning = $row.find("td.morning_b input:checkbox").prop("checked");
					var bEvening = $row.find("td.evening_b input:checkbox").prop("checked");
					var blockMorning = $("div.block-morning");
					var blockEvening= $("div.block-evening");
					//var blockMorningEvening= $("div.block-morning-evening");
					
					if(bMorning && !bEvening)
					{
						if(blockMorning.hasClass("hide"))
						{
							blockMorning.removeClass("hide");
						}
						if(!blockEvening.hasClass("hide"))
						{
							blockEvening.addClass("hide");
						}
						/*
						if(!blockMorningEvening.hasClass("hide"))
						{
							blockMorningEvening.addClass("hide");
						}
						*/
					}
					else if(!bMorning && bEvening)
					{
						if(!blockMorning.hasClass("hide"))
						{
							blockMorning.addClass("hide");
						}
						if(blockEvening.hasClass("hide"))
						{
							blockEvening.removeClass("hide");
						}
						/*
						if(!blockMorningEvening.hasClass("hide"))
						{
							blockMorningEvening.addClass("hide");
						}
						*/
						
					} 
					else if(bMorning && bEvening)
					{
						if(blockMorning.hasClass("hide"))
						{
							blockMorning.removeClass("hide");
						}
						if(blockEvening.hasClass("hide"))
						{
							blockEvening.removeClass("hide");
						}
						/*
						if(blockMorningEvening.hasClass("hide"))
						{
							blockMorningEvening.removeClass("hide");
						}
						*/

					}
					
				}
				
				/*Показываем дополнительные поля формы*/
				
				
			});
		}
		else	/*если чекнутых вообще нет удаляем неактивные выставки*/
		{
			table.find("tr").each(function(ind, element){
				var $row = $(element); 
				if($row.hasClass("not-available"))
				{
					$row.removeClass("not-available");
				}
			});
			
			var blockMorning = $("div.block-morning");
			var blockEvening= $("div.block-evening");
			var blockMorningEvening= $("div.block-morning-evening");
			
			if(!blockMorning.hasClass("hide"))
			{
				blockMorning.addClass("hide");
			}
			if(!blockEvening.hasClass("hide"))
			{
				blockEvening.addClass("hide");
			}
			if(!blockMorningEvening.hasClass("hide"))
			{
				blockMorningEvening.addClass("hide");
			}
			
		}
		
		
		var newText = "";
		/*отмечаем все выбранные выставки в спец поле*/
		table.find("input:checkbox:checked").each(function(ind, check){
			var row = $(check).closest("tr");
			
			if(!row.hasClass("not-available"))
			{
				var name = row.find("span.name_exhibition").text();
				newText = name;
			}
		});

		$("#selected-exhibition").text(newText);
	});
	
	/*Согласие с условиями*/
	$("#register_form_content").on("click", "input[name=CONFIRM_TERMS]:checkbox", function(){

		var checkbox = $(this);
		var checkboxId = checkbox.attr("id");
		var label = checkbox.siblings("label[for="+checkboxId+"]");
		var regButton = checkbox.siblings("input[type=button].register-button");

		/*Если принимаем условия*/
		if(label.hasClass("active-register"))
		{
			label.removeClass("active-register");
			regButton.removeClass("available");
			
		}
		else
		{
			label.addClass("active-register");
			regButton.addClass("available");
		}
	});
	
	/*Групповые чекбоксы разворачивание*/
	$("#register_form_content").on("click", ".group-name", function(){
		$(this).siblings("div.group-items").toggle(100);
	});
	
	/*DropDown разворачивание*/
	$("#register_form_content").on("click", ".dropdown-name", function(){
		$(this).siblings("ul.dropdown-items").toggle(300);
	});
	
	/*Групповые чекбоксы приоритетные направления разворачивание*/
	$("#register_form_content").on("click", ".priority-check-all", function(){
		$(this).siblings("div.priority-items").toggle(100);
	});
	
	/*Групповые чекбоксы выбор*/
	$("#register_form_content").on("click", ".group-items input:checkbox", function(){
		var checkbox = $(this);
		var checkboxId = checkbox.attr("id");
		var label = checkbox.siblings("label[for="+checkboxId+"]");
	
		/*Если выставку выбрали меняем стиль лейбла*/
		if(label.hasClass("active-group"))
		{
			label.removeClass("active-group");
		}
		else
		{
			label.addClass("active-group");
		}
	});
	
	/*Групповые чекбоксы приоритетные направления выбор поодиночно*/
	$("#register_form_content").on("click", ".priority-items input:checkbox", function(){
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
				console.log(121212);
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
	$("#register_form_content").on("click", ".priority-check-all label", function(event){
		event.stopPropagation();
	});
	/*Групповые чекбоксы приоритетные направления выбор всех*/
	$("#register_form_content").on("click", ".priority-check-all input:checkbox", function(event){
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
		
		items.find("input:checkbox").each(function(ind, element){
			var checkboxPA = $(element);
			
			if(checkbox.prop("checked") && !checkboxPA.prop("checked"))
			{
				checkboxPA.trigger("click");
			}
			else if(!checkbox.prop("checked") && checkboxPA.prop("checked"))
			{
				checkboxPA.trigger("click");
			}
		});
	});
	
	/*DropDown выбор*/
	$("#register_form_content").on("click", "ul.dropdown-items li", function(){
		var selectedLi = $(this);
		var selectedId = selectedLi.data("id");
		var ul = selectedLi.closest("ul");
		var select = ul.siblings("select");
		var name = ul.siblings(".dropdown-name");
		var country_other = $("#register_form_content input[name=COUNTRY_OTHER]");
		
		select.val(selectedId);
		name.text(selectedLi.text());
		name.trigger("click");
		
		/*если выбрали Другую страну*/
		if(selectedId == 509)
		{
			country_other.removeClass("hide");
			country_other.focus();
		}
		else if( typeof country_other != "undefined" && !country_other.hasClass("hide"))
		{
			country_other.addClass("hide");
		}

	});
	
	/*Удаление файлов*/
	$("#register_form_content").on("click", "div.delete-photo", function(){
		var delBtn = $(this);
		var sid = delBtn.data("sid");
		var delFile = delBtn.data("file");

		$.ajax({
			type: "POST",
			url: AjaxPatch.DELETE,
			data: {SID:sid, FILE:delFile},
			success: function(data){
				data = BX.parseJSON(data);
				
				if(data.STATUS == "success")
				{
					/*Снимаем количество*/
					
					var photoBlockId = delBtn.closest("ul").siblings(".upload-photos").attr("id");

					var addBtn = delBtn.closest("ul").siblings(".upload-photos");

					var photoBlockId = addBtn.attr("id");
					switch(photoBlockId)
					{
						case "personal-photo":
							{
								UploadPPCount--;
								if(UploadPPCount < UploadPPMaxCount)
								{
									obUploadPP.enable();
									if(addBtn.hasClass("disable-upload"))
									{
										addBtn.removeClass("disable-upload");
									}
								}
							}
							break;
						case "company-logo":
						{
							UploadLogoCount--;
							if(UploadLogoCount < UploadLogoMaxCount)
							{
								obUploadLogo.enable();
								if(addBtn.hasClass("disable-upload"))
								{
									addBtn.removeClass("disable-upload");
								}
							}
						}
						break;
						case "company-photos":
						{
							UploadPhotosCount--;
							
							var uploadedPhotos = addBtn.siblings(".uploaded");
							
							if(typeof uploadedPhotos != "undefined")
							{
								
								if(UploadPhotosCount > 0)
								{
									uploadedPhotos.text("Uploaded " + UploadPhotosCount + " photo");
								}
								else
								{
									uploadedPhotos.text("");
								}
							}
							
							if(UploadPhotosCount < UploadPhotosMaxCount)
							{
								obUploadPhotos.enable();
								if(addBtn.hasClass("disable-upload"))
								{
									addBtn.removeClass("disable-upload");
								}
							}
						}
						break;
					}
					
					delBtn.closest("li").remove();
				}
			  }
		});
	});

});





/*Загрузка файлов*/

function photoUpload()
{
	
	if($("#radio_PARTICIPANT").val() != "PARTICIPANT")
	{
		return true;
	}
	
/*Персональное фото*/
$(function(){
	
	var btnUploadPP=$('#personal-photo');
	
	if(btnUploadPP.length > 0)
	{
		var statusPP=btnUploadPP.siblings(".upload-status");
		var ulPP=btnUploadPP.siblings(".files");

		UploadPPMaxCount = 1;
		UploadPPCount = 0;

		obUploadPP = new AjaxUpload(btnUploadPP, {
			action: AjaxPatch.UPLOAD,
			name: 'uploadfile-personal',
			onSubmit: function(file, ext){
				if (! (ext && /^(jpg|png|jpeg)$/.test(ext))){ 
					// extension is not allowed 
					statusPP.text('Supported Formats JPG and PNG');
					return false;
				}
				
				statusPP.text('Loading...');
			},
			onComplete: function(file, response){
				response = BX.parseJSON(response);
				//On completion clear the status
				statusPP.text('');
				//Add uploaded file to list
				if(response.STATUS==="success"){
					$('<li></li>').appendTo(ulPP).html('<img src="'+response.FILE+'" alt="" class="upload-photo-preview"/><br /><div class="delete-photo" data-sid="'+ response.SID +'" data-file="'+ response.FILE +'">DELETE</div>').addClass('success');

					UploadPPCount++;
					
					if(UploadPPCount >= UploadPPMaxCount)
					{
						this.disable();
						
						if(!btnUploadPP.hasClass("disable-upload"))
						{
							btnUploadPP.addClass("disable-upload");
						}
					}
				} else{
					$('<li></li>').appendTo(ulPP).text(response.ERROR_TEXT).addClass('error');
				}
			}
		});
	}
});

/*Логотип*/
$(function(){
	
	var btnUploadLogo=$('#company-logo');
	
	if(btnUploadLogo.length > 0)
	{
		var statusLogo=btnUploadLogo.siblings(".upload-status");
		var ulLogo=btnUploadLogo.siblings(".files");

		UploadLogoMaxCount = 1;
		UploadLogoCount = 0;

		obUploadLogo = new AjaxUpload(btnUploadLogo, {
			action: AjaxPatch.UPLOAD,
			name: 'uploadfile-logo',
			onSubmit: function(file, ext){
				if (! (ext && /^(jpg|png|jpeg)$/.test(ext))){ 
					// extension is not allowed 
					statusLogo.text('Supported Formats JPG and PNG');
					return false;
				}
				
				statusLogo.text('Loading...');
			},
			onComplete: function(file, response){
				response = BX.parseJSON(response);
				//On completion clear the status
				statusLogo.text('');
				//Add uploaded file to list
				if(response.STATUS==="success"){
					$('<li></li>').appendTo(ulLogo).html('<img src="'+response.FILE+'" alt="" class="upload-photo-preview"/><br /><div class="delete-photo" data-sid="'+ response.SID +'" data-file="'+ response.FILE +'">DELETE</div>').addClass('success');

					UploadLogoCount++;
					
					if(UploadLogoCount >= UploadLogoMaxCount)
					{
						this.disable();
						
						if(!btnUploadLogo.hasClass("disable-upload"))
						{
							btnUploadLogo.addClass("disable-upload");
						}
					}
				} else{
					$('<li></li>').appendTo(ulLogo).text(response.ERROR_TEXT).addClass('error');
				}
			}
		});
	}
});

/*Фотографии*/
$(function(){
	
	var btnUploadPhotos=$('#company-photos');
	
	if(btnUploadPhotos.length > 0)
	{
		var statusPhotos=btnUploadPhotos.siblings(".upload-status");
		var ulPhotos=btnUploadPhotos.siblings(".files");
		var uploadedPhotos=btnUploadPhotos.siblings(".uploaded");

		UploadPhotosMaxCount =12;
		UploadPhotosCount = 0;

		obUploadPhotos = new AjaxUpload(btnUploadPhotos, {
			action: AjaxPatch.UPLOAD,
			name: 'uploadfile-photos',
			onSubmit: function(file, ext){
				if (! (ext && /^(jpg|png|jpeg)$/.test(ext))){ 
					// extension is not allowed 
					statusPhotos.text('Supported Formats JPG and PNG');
					return false;
				}
				
				statusPhotos.text('Loading...');
			},
			onComplete: function(file, response){
				response = BX.parseJSON(response);
				//On completion clear the status
				statusPhotos.text('');
				//Add uploaded file to list
				if(response.STATUS==="success"){
					$('<li></li>').appendTo(ulPhotos).html('<img src="'+response.FILE+'" alt="" class="upload-photo-preview"/><br /><div class="delete-photo" data-sid="'+ response.SID +'" data-file="'+ response.FILE +'">DELETE</div>').addClass('success');

					UploadPhotosCount++;
					
					if(typeof uploadedPhotos != "undefined")
					{
						uploadedPhotos.text("Uploaded " + UploadPhotosCount + " photo");
					}
					
					if(UploadPhotosCount >= UploadPhotosMaxCount)
					{
						this.disable();
						
						if(!btnUploadPhotos.hasClass("disable-upload"))
						{
							btnUploadPhotos.addClass("disable-upload");
						}
					}
				} else{
					$('<li></li>').appendTo(ulPhotos).text(response.ERROR_TEXT).addClass('error');
				}
			}
		});
	}
});
}

/*Валидация формы участника*/
function validateRegFormP()
{
	  // Start validation:
    $.validity.start();
    
    $("#REGISTER_FORM .require").require();
    $("#REGISTER_FORM select[nname=AREA_OF_BUSINESS]").require();
    $("#REGISTER_FORM .email").match("email");
    $("#REGISTER_FORM .web").match( /^(https?:\/\/)?([\w\.]+)\.([a-z]{2,6}\.?)(\/[\w\.]*)*\/?$/i, "This field must be formatted as a URL.");
    $("#REGISTER_FORM .phone").match(/^\+?[0-9()\t\n\r\f\v\s-]+$/, "This field must be formatted as phone number")
    $("#REGISTER_FORM .login").minLength(3, "Min length is 3 symbols").maxLength(16, "Max length is 16 symbols").match(/^[a-zA-Z0-9_-]{3,16}$/,"Login must consist of letters, numbers or underscores");
    $("#REGISTER_FORM .description").maxLength(1200, "Max length is 1200 symbols");
    $("#REGISTER_FORM .confemail").assert(equalsValue("input[name=EMAIL]", "input[name=CONF_EMAIL]"), "Email does not match");
    
    /*Выбрана выставка*/
    $("table.exh-select").assert(isExhibSelected(), "You have not selected exhibition.");
    
    /*Приоритетные направления*/
    $(".priority-areas .priority-title").assert(checkPriorityAreas(), "Priority destinations are not selected.");
    
    /*Персональное фото*/
    $("#company-photos").assert(checkCompanyPhoto(), "Minimum 6 photos");
    // All of the validator methods have been called:
    // End the validation session:
    var result = $.validity.end();
    
    // Return whether it's okay to proceed with the Ajax:
    return result.valid;
}

function checkCompanyPhoto()
{
	var btnUpload = $("#company-photos");
	var ulPhotos = btnUpload.siblings("ul.files");
	
	var photos = ulPhotos.find("li");
	
	if(photos.length > 0 && photos.length < 6)
	{
		return false;
	}
	else
	{
		return true;
	}
}

/*Валидация формы гостя*/
function validateRegFormB()
{
	  // Start validation:
    $.validity.start();
    
    $("#REGISTER_FORM .require").require("Это поле является обязательным.");
    $("#REGISTER_FORM .en").match(/^[^а-яА-Я]+$/, "Только английские символы.");
    $("#REGISTER_FORM .email").match("email", "Некорректный E-mail.");
    $("#REGISTER_FORM .index").match(/[a-zA-Z0-9]+/, "Только латинские символы и цифры");
    $("#REGISTER_FORM .web").match( /^(https?:\/\/)?([\w\.]+)\.([a-z]{2,6}\.?)(\/[\w\.]*)*\/?$/i, "Это поле должно быть отформатирован как URL.");
    $("#REGISTER_FORM .phone").match(/^\+?[0-9()\t\n\r\f\v\s-]+$/, "Это поле должно быть в формате телефонного номера.")
   
    
    $("#REGISTER_FORM .confemail").assert(equalsValue("input[name=EMAIL]", "input[name=CONF_EMAIL]"), "E-mail не совпадают.");
    
    if(!isOnlyEvening())
    {	
    	$("#REGISTER_FORM .description").require("Это поле является обязательным.").maxLength(1200, "Максимальная длина составляет 1200 символов").nonHtml();
        $("#REGISTER_FORM .login").require("Это поле является обязательным.").minLength(3, "Минимальная длина составляет 3 символа.").maxLength(16, "Максимальная длина составляет 16 символов.").match(/^[a-zA-Z0-9_-]{3,16}$/,"Логин должен состоять из букв, цифр или символов подчеркивания.").match(/\S+/, "Пробел недопустим");
        $("#REGISTER_FORM .pass").require("Это поле является обязательным.").match(/\S+/, "Пробел недопустим");
        $("#REGISTER_FORM .confpass").require("Это поле является обязательным.").assert(equalsValue("input[name=PASSWORD]", "input[name=CONF_PASSWORD]"), "Пароли не совпадают.").match(/\S+/, "Пробел недопустим");
    }

    /*Выбрана выставка*/
    $("table.exh-select").assert(isExhibSelected(), "Вы не выбрали мероприятие.");

    /*страна другая*/
    $(".country_other").assert(otherCountry(), "Это поле является обязательным.");

    /*Приоритетные направления*/
    $(".priority-areas .priority-title").assert(checkPriorityAreas(), "Приоритетные направления не выбраны.");
    
    /*Вид деятельности */
    $(".check-group .group-name").assert(checkGroup(), "Вид деятельности не выбран");
    
    // All of the validator methods have been called:
    // End the validation session:
    var result = $.validity.end();
    
    // Return whether it's okay to proceed with the Ajax:
    return result.valid;
}

function equalsValue(validator1, validator2)
{
	var email = $(validator1).val();
	var confemail = $(validator2).val();
	return email == confemail;
}

function otherCountry()
{
	var country = $("select[name=COUNTRY]").val();
	var country_other = $("input[name=COUNTRY_OTHER]").val();
	country_other = $.trim(country_other);

	if(country != 509)
	{
		return true;
	}
	
	if(country_other.length > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function isOnlyEvening()
{
	var evening = false;
	$("table.exh-select tbody tr:not(.not-available)").each(function(ind, element){
		var row = $(element); 

		var bMorning = row.find("td.morning_b input:checkbox").prop("checked");
		var bEvening = row.find("td.evening_b input:checkbox").prop("checked");
		 
		if((bMorning == false && bEvening == true) || (bMorning == false && bEvening == false))
		{
			evening = true;
		}

	});

	return evening;

}

function checkPriorityAreas()
{ 
	/*если чекнуто на вечер, то не проверяем*/
	var evening = isOnlyEvening();
	
	if(evening)
	{
		return true;
	}

	
	var checked = $(".priority-areas input:checkbox:checked");
	
	if(checked.length == 0)
	{
		return false;
	}
	else
	{
		return true;
	}
}

function checkGroup()
{
	var checked = $(".check-group input:checkbox:checked");
	
	if(checked.length == 0)
	{
		return false;
	}
	else
	{
		return true;
	}
}

function isExhibSelected()
{
	var checked = $("table.exh-select input:checkbox:checked");
	if(checked.length > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}


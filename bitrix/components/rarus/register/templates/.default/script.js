$(function() {
	BX.addCustomEvent("onAjaxSuccess", photoUpload);
	
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
			"login_busy": "Login has already been taken."
	};
	
	var ru = {
			"require":"��� ���� �������� ������������.",
			"email":"������������ E-mail.",
			"web":"��� ���� ������ ���� �������������� ��� URL.",
			"phone":"��� ���� ������ ���� � ������� ����������� ������.",
			"minLength": "����������� ����� ���������� 3 �������.",
			"maxLength": "������������ ����� ���������� 16 ��������.",
			"login": "����� ������ �������� �� ����, ���� ��� �������� �������������.",
			"conf_email": "E-mail �� ���������.",
			"exhib":"�� �� ������� �����������.",
			"priority": "������������ ����������� �� �������.",
			"minPhoto": "������� 6 ����������.",
			"en": "������ ��������� �������.",
			"area":"��� ������������ �� ������.",
			"space":"������ ����������.",
			"conf_pass":"������ �� ���������.",
			"en_num":"������ ��������� ������� � �����.",
			"login_busy": "����� ��� �����."
				
	};
	

	$errorText = {"en":en, "ru":ru};
	
	//�������� ������ ������
	$("#REGISTER_FORM").on("keyup", "input[name=CONF_EMAIL]", function(eventObject){
		
		var inpConfEmail = $(this);
		var inpEmail = $("input[name=EMAIL]");
		
		if(inpEmail.length > 0)
		{
			var ConfEmail = inpConfEmail.val();
			var Email = inpEmail.val();
			
			var expr = new RegExp("^"+ConfEmail+".*");
			var errorTextDiv = inpConfEmail.siblings(".input-error-text");
			
			if(!Email.match(expr))
			{
				if(errorTextDiv.length == 0)
				{
					$("<div></div>").addClass("input-error-text").text($errorText[errorLang]["conf_email"]).insertAfter(inpConfEmail);
				}

			}
			else
			{
				if(errorTextDiv.length > 0)
				{
					errorTextDiv.remove();
					inpConfEmail.css("border", "");
					inpConfEmail.siblings().remove();
				}

			}
		}
	});

	/*
	//����������� �� �������� ��������
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
	*/
	
	//ajax �������� ������
	$("#REGISTER_FORM").on("focusout", "input[name=LOGIN]", function(){
		
		var loginInput = $(this);
		var login = $(this).val();
		var sid = $("#sessid").val();
		
		$.ajax({
			type: "POST",
			url: AjaxPatch.LOGIN,
			data: {SID:sid, LOGIN:login},
			async: false,
			success: function(data){
				data = BX.parseJSON(data);
				
				var status = loginInput.siblings(".input-status");
				var errorText = loginInput.siblings(".input-error-text");
				
				if(data.STATUS == "success")
				{ 	
					//correctly= true;
					if(status.length == 0)
					{
						$("<div></div>").addClass("input-status input-success").insertAfter(loginInput);
						loginInput.css("border", "");
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
							loginInput.css("border", "");
						}
					}
					
					if(errorText.length != 0)
					{
						errorText.remove();
						loginInput.css("border", "");
					}
				}
				else
				{
					correctly=false;
					if(status.length == 0)
					{
						$("<div></div>").addClass("input-status input-error").insertAfter(loginInput);
						loginInput.css("border", "1px solid #F00");
					}
					else
					{
						if(status.hasClass("input-success"))
						{
							status.removeClass("input-success");
						}
						
						if(!status.hasClass("input-error"))
						{
							status.addClass("input-error");
							loginInput.css("border", "1px solid #F00");
						}
					}
					
					if(errorText.length == 0)
					{
						$("<div></div>").addClass("input-error-text").text($errorText[errorLang][data.ERROR_CODE]).insertAfter(loginInput);
					}
					else
					{
						errorText.text($errorText[errorLang][data.ERROR_CODE]);
					}
				}
			  }
		});
	});
	
	
	//$.validity.setup({ scrollTo: true });
	
	//���� �� ����������� ���������
	$("#REGISTER_FORM").on("click", ".registr_exh input[name=register_button]", function(){
		
		$(this).prop('disabled',true);
		if($("input#ckeck_register").prop("checked") && validateRegFormP())
		{
			submitForm("Y");
		}
		else
		{
			$('body,html').animate({scrollTop:300},300);
		}
		$(this).prop('disabled',false);
	});
	
	//���� �� ����������� �����
	$("#REGISTER_FORM").on("click", ".registr_buy input[name=register_button]", function(){
		$(this).prop('disabled',true);
		if($("input#ckeck_register").prop("checked") && validateRegFormB())
		{
			submitForm("Y");
		}
		else
		{
			$('body,html').animate({scrollTop:300},300);
		}
		$(this).prop('disabled',false);
	});
	
	/*����� �������� �����������*/
	$("#register_form_content").on("click", ".registr_exh table.exh-select input:checkbox", function(){
		
		var checkbox = $(this);
		var checkboxId = checkbox.attr("id");
		var label = checkbox.siblings("label[for="+checkboxId+"]");
		var row = checkbox.closest("tr");
		var table = checkbox.closest("table.exh-select");
		
		/*���� �� �������� ������ ������������������ �� ���� �� �������*/
		if(row.hasClass("not-available"))
		{
			return false;
		}
		 
		/*���� �������� ������� ������ ����� ������*/
		if(label.hasClass("active-exh"))
		{
			label.removeClass("active-exh");
		}
		else
		{
			label.addClass("active-exh");
		}
		
		var newText = "";
		/*�������� ��� ��������� �������� � ���� ����*/
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
		
		 //�������� ������ ��������
		if(!isExhibSelected())
		{
			var block = $("#REGISTER_FORM .exh-select");
			showErrorMessage(block,$errorText[errorLang]["exhib"], true);
		}
		else
		{ 
			var block = $("#REGISTER_FORM .exh-select");
			hideErrorMessage(block,$errorText[errorLang]["exhib"], true);
		}
	});
	
	/*����� �������� ������*/
	$("#register_form_content").on("click", ".registr_buy table.exh-select input:checkbox", function(){
		
		var checkbox = $(this);
		var checkboxId = checkbox.attr("id");
		var label = checkbox.siblings("label[for="+checkboxId+"]");
		var row = checkbox.closest("tr");
		var table = checkbox.closest("table.exh-select");

		/*���� �� �������� ������ ������������������ �� ���� �� �������*/
		if(row.hasClass("not-available"))
		{
			return false;
		}
		
		/*���� �������� ������� ������ ����� ������*/
		if(label.hasClass("active-exh"))
		{
			label.removeClass("active-exh");
		}
		else
		{
			label.addClass("active-exh");
		}
		
		/*���� ���� �������� �������� ��������� ���������*/
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
				
				/*���������� �������������� ���� �����*/
				
				
			});
		}
		else	/*���� �������� ������ ��� ������� ���������� ��������*/
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
		/*�������� ��� ��������� �������� � ���� ����*/
		table.find("input:checkbox:checked").each(function(ind, check){
			var row = $(check).closest("tr");
			
			if(!row.hasClass("not-available"))
			{
				var name = row.find("span.name_exhibition").text();
				newText = name;
			}
		});

		$("#selected-exhibition").text(newText);
		
		 //�������� ������ ��������
		if(!isExhibSelected())
		{
			var block = $("#REGISTER_FORM .exh-select");
			showErrorMessage(block,$errorText[errorLang]["exhib"], true);
		}
		else
		{ 
			var block = $("#REGISTER_FORM .exh-select");
			hideErrorMessage(block,$errorText[errorLang]["exhib"], true);
		}
	});
	
	/*����� �������� ������*/
	$("#register_form_content").on("click", ".registr_buy div.check-group input:checkbox", function(){
		
		 //�������� ���� ������������
		if(!checkGroup())
		{
			showErrorMessage($("#REGISTER_FORM div.check-group"),$errorText[errorLang]["area"]);
		}
		else
		{ 
			hideErrorMessage($("#REGISTER_FORM div.check-group"),$errorText[errorLang]["area"]);
		}
	});
	
	
	
	/*�������� � ���������*/
	$("#register_form_content").on("click", "input[name=CONFIRM_TERMS]:checkbox", function(){

		var checkbox = $(this);
		var checkboxId = checkbox.attr("id");
		var label = checkbox.siblings("label[for="+checkboxId+"]");
		var regButton = checkbox.siblings("input[type=button].register-button");

		/*���� ��������� �������*/
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
	
	/*��������� �������� ��������������*/
	$("#register_form_content").on("click", ".group-name", function(event){
		//$(this).siblings("div.group-items").toggle(100);
		
		var selectDiv = $(this).siblings("div.group-items");
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
	
	/*DropDown ��������������*/
	$("#register_form_content").on("click", ".dropdown-name", function(event){
		//$(this).siblings("ul.dropdown-items").toggle(300);
		
		//������������ �������� � ������������ ����������� ��� ����� ����
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
	
	/*��������� �������� ������������ ����������� ��������������*/
	$("#register_form_content").on("click", ".priority-check-all", function(){
		//$(this).siblings("div.priority-items").toggle(100);
		
		var selectDiv = $(this).siblings("div.priority-items");
		var selectDivId = selectDiv.attr("id");
		
	    if (selectDiv.css('display') != 'block') {
	    	selectDiv.show(100);
	 
	        var firstClick = true;
	        $(document).bind('click.myEvent-'+selectDivId, function(e) {
	            if (!firstClick && $(e.target).closest('#'+selectDivId).length == 0) {
	            	selectDiv.hide(100);
	                $(document).unbind('click.myEvent-'+selectDivId);
	            }
	            firstClick = false;
	        });
	    }
	 
	    event.preventDefault();
	});
	
	

	
	
	/*��������� �������� �����*/
	$("#register_form_content").on("click", ".group-items input:checkbox", function(){
		var checkbox = $(this);
		var checkboxId = checkbox.attr("id");
		var label = checkbox.siblings("label[for="+checkboxId+"]");
	
		/*���� �������� ������� ������ ����� ������*/
		if(label.hasClass("active-group"))
		{
			label.removeClass("active-group");
		}
		else
		{
			label.addClass("active-group");
		}
	});
	
	/*��������� �������� ������������ ����������� ����� ����������*/
	$("#register_form_content").on("click", ".priority-items input:checkbox", function(){
		var checkbox = $(this);
		var checkboxId = checkbox.attr("id");
		var label = checkbox.siblings("label[for="+checkboxId+"]");
	
		/*���� �������� ������� ������ ����� ������*/
		if(label.hasClass("active-group"))
		{
			label.removeClass("active-group");
			
			/*������� ������� ������� ���, ���� �������*/
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
	
	/*��������� ���� �� ������*/
	$("#register_form_content").on("click", ".priority-check-all label", function(event){
		event.stopPropagation();
	});
	
	/*��������� �������� ������������ ����������� ����� ����*/
	$("#register_form_content").on("click", ".priority-check-all input:checkbox", function(event){
		event.stopPropagation();
		
		var checkbox = $(this);
		var checkboxId = checkbox.attr("id");
		var label = checkbox.siblings("label[for="+checkboxId+"]");
	
		/*���� ������� ������� ������ �����*/
		if(label.hasClass("active-all"))
		{
			label.removeClass("active-all");
		}
		else
		{ 
			label.addClass("active-all");
		}

		/*������ ��� ��������*/
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
	
	$("#register_form_content").on("click", ".priority-check-global input:checkbox", function(event){
		event.stopPropagation();
		
		var checkbox = $(this);
		var checkboxId = checkbox.attr("id");
		var label = checkbox.siblings("label[for="+checkboxId+"]");
		var priorityBlock = checkbox.closest(".priority-check-global").siblings(".priority-wrap");
		
		/*���� ������� ������� ������ �����*/
		if(label.hasClass("active-global"))
		{
			label.removeClass("active-global");
			priorityBlock.show(100);
		}
		else
		{ 
			label.addClass("active-global");
			priorityBlock.hide(100);
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
	
	/*DropDown �����*/
	$("#register_form_content").on("click", "ul.dropdown-items li", function(){
		var selectedLi = $(this);
		var selectedId = selectedLi.data("id");
		var ul = selectedLi.closest("ul");
		var select = ul.siblings("select");
		var name = ul.siblings(".dropdown-name");
		var country_other = $("#register_form_content input[name=COUNTRY_OTHER]");
		
		select.val(selectedId);
		select.trigger("change");
		name.text(selectedLi.text());
		name.trigger("click");
		
		/*���� ������� ������ ������*/
		if(selectedId == 509)
		{
			country_other.removeClass("hide");
			country_other.focus();
		}
		else if( typeof country_other != "undefined" && !country_other.hasClass("hide"))
		{
			country_other.addClass("hide");
			country_other.val("");
			country_other.siblings().remove();
		}

	});
	
	/*�������� ������*/
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
					/*������� ����������*/
					
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





/*�������� ������*/

function photoUpload()
{
	if($(".registr_exh").length > 0)
	{
		errorLang = "en";
	}
	else
	{
		errorLang = "ru";
	}
	
	
	if($("#radio_PARTICIPANT").val() != "PARTICIPANT")
	{
		return true;
	}
	
/*������������ ����*/
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

/*�������*/
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

/*����������*/
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
		 
		//if((bMorning == false && bEvening == true) || (bMorning == false && bEvening == false))
		if(!bMorning && bEvening)
		{
			evening = true;
		}

	});

	return evening;

}

function checkPriorityAreas()
{ 
	/*���� ������� �� �����, �� �� ���������*/
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
			//���� ����, �� �� ���������
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
		
		//���� ������ ��� ����� �������, ��� ��� ��
		if(errorTextDiv.length > 0)
		{
			//���� ����, �� �� ���������
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
		
		//������� �������, ����� �������
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

//���������

//���������� ����
$(function() {
	$("#REGISTER_FORM").on("focusout", ".en", function(){
		var input = $(this);
		var value = input.val();
		
		if(!value.match(/^[^�-��-�]+$/) && value.length > 0)
		{
			showErrorMessage(this,$errorText[errorLang]["en"]);
		}
		else
		{
			hideErrorMessage(this,$errorText[errorLang]["en"], true);
		}
	});
});

//������������ ����
$(function() {
	$("#REGISTER_FORM").on("focusout", ".require", function(){
		var input = $(this);
		var value = input.val();
		
		if(value.length <= 0)
		{
			showErrorMessage(this,$errorText[errorLang]["require"]);
		}
		else
		{
			hideErrorMessage(this,$errorText[errorLang]["require"]);
		}
	});
});



//email
$(function() {
	$("#REGISTER_FORM").on("focusout", ".email", function(){
		var input = $(this);
		var value = input.val();
		
		if(!value.match(/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*(\.[a-z]{2,6})+$/i) && value.length > 0)
		{
			showErrorMessage(this,$errorText[errorLang]["email"]);
		}
		else
		{
			hideErrorMessage(this,$errorText[errorLang]["email"],true);
		}
	});
});

//index
$(function() {
	$("#REGISTER_FORM").on("focusout", ".index", function(){
		var input = $(this);
		var value = input.val();
		
		if(!value.match(/^[a-zA-Z0-9]+$/) && value.length > 0)
		{
			showErrorMessage(this,$errorText[errorLang]["en_num"]);
		}
		else
		{
			hideErrorMessage(this,$errorText[errorLang]["en_num"]);
		}
	});
});

//web
$(function() {
	$("#REGISTER_FORM").on("focusout", ".web", function(){
		var input = $(this);
		var value = input.val();
		
		if(!value.match(/^(https?:\/\/)?([\w\.-]+)\.([a-z]{2,6}\.?)(\/[\w\.]*)*\/?$/i) && value.length > 0)
		{
			showErrorMessage(this,$errorText[errorLang]["web"]);
		}
		else
		{
			hideErrorMessage(this,$errorText[errorLang]["web"]);
		}
	});
});

$(function() {
	$("#REGISTER_FORM").on("focusout", ".phone", function(){
		var input = $(this);
		var value = input.val();
		
		if(!value.match(/^\+?[0-9()\t\n\r\f\v\s-]+$/) && value.length > 0)
		{
			showErrorMessage(this,$errorText[errorLang]["phone"]);
		}
		else
		{
			hideErrorMessage(this,$errorText[errorLang]["phone"]);
		}
	});
});

$(function() {
	$("#REGISTER_FORM").on("focusout", ".pass", function(){
		var input = $(this);
		var value = input.val();
		
		if(value.match(/\s+/) && value.length > 0)
		{
			showErrorMessage(this,$errorText[errorLang]["space"]);
		}
		else
		{
			hideErrorMessage(this,$errorText[errorLang]["space"]);
		}
		
		if(value.length < 3 && value.length > 0)
		{
			showErrorMessage(this,$errorText[errorLang]["minLength"]);
		}
		else
		{
			hideErrorMessage(this,$errorText[errorLang]["minLength"]);
		}
		
		if(value.length > 16)
		{
			showErrorMessage(this,$errorText[errorLang]["maxLength"]);
		}
		else
		{
			hideErrorMessage(this,$errorText[errorLang]["maxLength"]);
		}
		
		if(value.length <= 0)
		{
			showErrorMessage(this,$errorText[errorLang]["require"]);
		}
		else
		{
			hideErrorMessage(this,$errorText[errorLang]["require"]);
		}
	});
});

$(function() {
	$("#REGISTER_FORM").on("focusout", ".confpass", function(){
		var input = $(this);
		var value = input.val();
		
		if(value.length <= 0)
		{
			showErrorMessage(this,$errorText[errorLang]["require"]);
		}
		else
		{
			hideErrorMessage(this,$errorText[errorLang]["require"]);
		}
		
		if(!equalsValue("input[name=PASSWORD]", "input[name=CONF_PASSWORD]"))
		{
			showErrorMessage(this,$errorText[errorLang]["conf_pass"]);
		}
		else
		{
			hideErrorMessage(this,$errorText[errorLang]["conf_pass"]);
		}
		
	});
});

$(function() {
	$("#REGISTER_FORM").on("focusout", ".confemail", function(){
		var input = $(this);
		var value = input.val();
		
		if(!equalsValue("input[name=EMAIL]", "input[name=CONF_EMAIL]"))
		{
			showErrorMessage(this,$errorText[errorLang]["conf_email"]);
		}
		else
		{
			hideErrorMessage(this,$errorText[errorLang]["conf_email"]);
		}
		
	});
});

$(function() {
	$("#REGISTER_FORM").on("focusout", ".description", function(){
		var input = $(this);
		var value = input.val();
		
		if(value.length <= 0)
		{
			showErrorMessage(this,$errorText[errorLang]["require"]);
		}
		else
		{
			hideErrorMessage(this,$errorText[errorLang]["require"]);
		}
		
	});
});

$(function() {
	$("#REGISTER_FORM").on("change", "select", function(){
	 	var value = $(this).val();
	 	var select = $(this).closest(".dropdown-group");

		if(!value)
		{
			showErrorMessage(select,$errorText[errorLang]["require"]);
		}
		else
		{
			hideErrorMessage(select,$errorText[errorLang]["require"]);
		}
	});
});

$(function() {
	$("#REGISTER_FORM").on("focusout", ".country_other", function(){
		var input = $(this);
		var value = input.val();
		
		if(!otherCountry())
		{
			showErrorMessage(this,$errorText[errorLang]["require"]);
		}
		else
		{
			hideErrorMessage(this,$errorText[errorLang]["require"], true);
		}
		
	});
});

/*��������� ����� �����*/
function validateRegFormB()
{
	correctly = true;
	//�������� �� ���������� �����
	 $("#REGISTER_FORM .require, .email, .index, .web, .phone, .country_other").each(function(ind, elem)
		{
			 $(elem).trigger("focusout");
		}
	 );
	
		//�������� ������ ��������
	 $("#REGISTER_FORM select").each(function(ind, elem)
		{
		 	$(elem).trigger("change");
		}
	 );
	
    if(!isOnlyEvening())
    {	
   	 	$("#REGISTER_FORM .description, .login, .pass, .confpass").each(function(ind, elem)
   	   			{
   	   				 $(elem).trigger("focusout");
   	   			}
   	   		 );
   	   	 
   			 //�������� ������������ �����������
   		 	if(!checkPriorityAreas())
   		 	{
   		 		showErrorMessage($("#REGISTER_FORM .priority-wrap"),$errorText[errorLang]["priority"], true);
   		 	}
   		 	else
   		 	{ 
   		 		hideErrorMessage($("#REGISTER_FORM .priority-wrap"),$errorText[errorLang]["priority"], true);
   		 	}
    }
	
    
	 //�������� ������ ��������
	if(!isExhibSelected())
	{
		var block = $("#REGISTER_FORM .exh-select");
		showErrorMessage(block,$errorText[errorLang]["exhib"], true);
	}
	else
	{ 
		var block = $("#REGISTER_FORM .exh-select");
		hideErrorMessage(block,$errorText[errorLang]["exhib"], true);
	}
	
	
	 //�������� ���� ������������
	if(!checkGroup())
	{
		showErrorMessage($("#REGISTER_FORM div.check-group"),$errorText[errorLang]["area"]);
	}
	else
	{ 
		hideErrorMessage($("#REGISTER_FORM div.check-group"),$errorText[errorLang]["area"]);
	}

	 return correctly;
}

/*��������� ����� ���������*/
function validateRegFormP()
{
	correctly = true;
	
	//�������� �� ���������� �����
	 $("#REGISTER_FORM input[type=text], #REGISTER_FORM textarea").each(function(ind, elem)
		{
			 $(elem).trigger("focusout");
		}
	 );
	
	//�������� ������ ��������
	 $("#REGISTER_FORM select").each(function(ind, elem)
		{
		 	$(elem).trigger("change");
		}
	 );
	 
	 //�������� ������������ �����������
	if(!checkPriorityAreas())
	{
		showErrorMessage($("#REGISTER_FORM .priority-wrap"),$errorText[errorLang]["priority"], true);
	}
	else
	{ 
		hideErrorMessage($("#REGISTER_FORM .priority-wrap"),$errorText[errorLang]["priority"], true);
	}
	 
	//�������� ����������� ����������
	 
	 //�������� ���� ��������
	if(!checkCompanyPhoto())
	{
		var block = $("#REGISTER_FORM .block-upload-photo:has(#company-photos)");
		showErrorMessage(block,$errorText[errorLang]["minPhoto"]);
	}
	else
	{ 
		var block = $("#REGISTER_FORM .block-upload-photo:has(#company-photos)");
		hideErrorMessage(block,$errorText[errorLang]["minPhoto"]);
	}
	
	 //�������� ������ ��������
	if(!isExhibSelected())
	{
		var block = $("#REGISTER_FORM .exh-select");
		showErrorMessage(block,$errorText[errorLang]["exhib"], true);
	}
	else
	{ 
		var block = $("#REGISTER_FORM .exh-select");
		hideErrorMessage(block,$errorText[errorLang]["exhib"], true);
	}
	 
	return correctly;
}




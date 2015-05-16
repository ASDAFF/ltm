/*Валидация формы гостя*/
function validateRegFormB()
{/*
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

    //Выбрана выставка
    $("table.exh-select").assert(isExhibSelected(), "Вы не выбрали мероприятие.");

    //страна другая
    $(".country_other").assert(otherCountry(), "Это поле является обязательным.");

    //Приоритетные направления
    $(".priority-areas .priority-title").assert(checkPriorityAreas(), "Приоритетные направления не выбраны.");
    
    //Вид деятельности 
    $(".check-group .group-name").assert(checkGroup(), "Вид деятельности не выбран");
    
    // All of the validator methods have been called:
    // End the validation session:
    var result = $.validity.end();
    
    // Return whether it's okay to proceed with the Ajax:
    return result.valid;
    */
}

/*Валидация формы участника*/
function validateRegFormP()
{/*
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
    
    //Выбрана выставка
    $("table.exh-select").assert(isExhibSelected(), "You have not selected exhibition.");
    
    //Приоритетные направления
    $(".priority-areas .priority-title").assert(checkPriorityAreas(), "Priority destinations are not selected.");
    
    //Персональное фото
    $("#company-photos").assert(checkCompanyPhoto(), "Minimum 6 photos");
    // All of the validator methods have been called:
    // End the validation session:
    var result = $.validity.end();
    
    // Return whether it's okay to proceed with the Ajax:
    return result.valid;
    */
}
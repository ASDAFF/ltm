/*��������� ����� �����*/
function validateRegFormB()
{/*
	  // Start validation:
    $.validity.start();
    
    $("#REGISTER_FORM .require").require("��� ���� �������� ������������.");
    $("#REGISTER_FORM .en").match(/^[^�-��-�]+$/, "������ ���������� �������.");
    $("#REGISTER_FORM .email").match("email", "������������ E-mail.");
    $("#REGISTER_FORM .index").match(/[a-zA-Z0-9]+/, "������ ��������� ������� � �����");
    $("#REGISTER_FORM .web").match( /^(https?:\/\/)?([\w\.]+)\.([a-z]{2,6}\.?)(\/[\w\.]*)*\/?$/i, "��� ���� ������ ���� �������������� ��� URL.");
    $("#REGISTER_FORM .phone").match(/^\+?[0-9()\t\n\r\f\v\s-]+$/, "��� ���� ������ ���� � ������� ����������� ������.")
   
    
    $("#REGISTER_FORM .confemail").assert(equalsValue("input[name=EMAIL]", "input[name=CONF_EMAIL]"), "E-mail �� ���������.");
    
    if(!isOnlyEvening())
    {	
    	$("#REGISTER_FORM .description").require("��� ���� �������� ������������.").maxLength(1200, "������������ ����� ���������� 1200 ��������").nonHtml();
        $("#REGISTER_FORM .login").require("��� ���� �������� ������������.").minLength(3, "����������� ����� ���������� 3 �������.").maxLength(16, "������������ ����� ���������� 16 ��������.").match(/^[a-zA-Z0-9_-]{3,16}$/,"����� ������ �������� �� ����, ���� ��� �������� �������������.").match(/\S+/, "������ ����������");
        $("#REGISTER_FORM .pass").require("��� ���� �������� ������������.").match(/\S+/, "������ ����������");
        $("#REGISTER_FORM .confpass").require("��� ���� �������� ������������.").assert(equalsValue("input[name=PASSWORD]", "input[name=CONF_PASSWORD]"), "������ �� ���������.").match(/\S+/, "������ ����������");
    }

    //������� ��������
    $("table.exh-select").assert(isExhibSelected(), "�� �� ������� �����������.");

    //������ ������
    $(".country_other").assert(otherCountry(), "��� ���� �������� ������������.");

    //������������ �����������
    $(".priority-areas .priority-title").assert(checkPriorityAreas(), "������������ ����������� �� �������.");
    
    //��� ������������ 
    $(".check-group .group-name").assert(checkGroup(), "��� ������������ �� ������");
    
    // All of the validator methods have been called:
    // End the validation session:
    var result = $.validity.end();
    
    // Return whether it's okay to proceed with the Ajax:
    return result.valid;
    */
}

/*��������� ����� ���������*/
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
    
    //������� ��������
    $("table.exh-select").assert(isExhibSelected(), "You have not selected exhibition.");
    
    //������������ �����������
    $(".priority-areas .priority-title").assert(checkPriorityAreas(), "Priority destinations are not selected.");
    
    //������������ ����
    $("#company-photos").assert(checkCompanyPhoto(), "Minimum 6 photos");
    // All of the validator methods have been called:
    // End the validation session:
    var result = $.validity.end();
    
    // Return whether it's okay to proceed with the Ajax:
    return result.valid;
    */
}
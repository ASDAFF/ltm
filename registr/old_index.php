<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$title = "";
if(LANGUAGE_ID == "ru")
{
	$title = "РЕГИСТРАЦИЯ";
}
elseif(LANGUAGE_ID == "en")
{
	$title = "REGISTRATION";
}
$APPLICATION->SetPageProperty("title", $title);
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("THE LEADING LUXURY TRAVEL EXHIBITION");

if(!$USER->IsAdmin())
{
	LocalRedirect("/");
}
?> 
<script  src="<?=SITE_TEMPLATE_PATH?>/js/ajaxupload.3.5.js"></script>
<script  src="<?=SITE_TEMPLATE_PATH?>/js/form.js"></script>
 <span class="ex">REGISTRATION FOR ALL EXHIBITONS</span> <form method="post" id="reg_ex"> 	<figure id="step1"> 		<span class="title">ABOUT YOU</span> 		
    <div class="fir"> 			<span>EXHIBITOR</span> 			<span class="descr">A representative of a hotel or group of hotels, airline, resort, medical facility, sports tourism organization, etc. The company is represented at the exhibition.
        <br />
      Paid participation.<br />Registration form is not supported in early versions of Internet Explorer (min. 10 version). Please use an alternative browser.</span> 		</div>
   		
    <div class="sec"> 			<span>BUYER, HOSTED BUYER</span> 			<span class="descr">Гостевая регистрация на утреннюю, вечернюю сессии и по программе Hosted Buyer. Участие бесплатное, требуется подтверждение организаторов.<br />Регистрационная форма не поддерживается в ранних версиях Internet Explorer (мин. 10 версия). Пожалуйста, используйте альтернативный браузер.</span> 		</div>
   	</figure> 	
  <div class="clear"></div>
 	
  <table id="t_step1"> 		
    <tbody>
      <tr> 			<td> 				<span class="chek active"></span> 				<input type="checkbox" class="none" value="fir" name="who" /> 			</td> 			<td> 				<span class="chek st2"></span> 				<input type="checkbox" class="none" value="sec" name="who" /> 			</td> 		</tr>
     	</tbody>
  </table>
 	<input type="hidden" value="EXH" class="user_gr" name="group" /> 	
  <div class="sep"></div>
 	
  <div class="registr_exh"> 		
    <div class="reg_exh"> 			
      <div id="block_exh"> 			<span class="before">Before registering please request <a href="/terms/" target = "_blank">terms &amp; conditions</a></span> 			<figure id="step2"> 				<span class="title">EXHIBITOR</span> 				<span class="choose">PLEASE SELECT THE EVENT YOU WOULD LIKE TO REGISTER FOR:</span> 				<?
				CModule::IncludeModule("iblock");
				$APPLICATION->IncludeComponent("luxor:news.list", "exh", array(
					"IBLOCK_TYPE" => "exhib",
					"IBLOCK_ID" => "15",
					"NEWS_COUNT" => "false",
					"SORT_BY1" => "SORT",
					"SORT_ORDER1" => "DESC",
					"SORT_BY2" => "SORT",
					"SORT_ORDER2" => "ASC",
					"FILTER_NAME" => "",
					"FIELD_CODE" => array(
						0 => "",
						1 => "",
					),
					"PROPERTY_CODE" => array(
						0 => "NAME_EN",
						1 => "DETAIL_TEXT_EN",
						2 => "PREVIEW_TEXT_EN",
						3 => "STATUS",
						4 => "STATUS_G_M",
						5 => "STATUS_G_E",
						6 => "USER_GROUP_ID",
						7 => "NC_PARTICIPANTS_GROUP",
						8 => "C_GUESTS_GROUP",
						9 => "NC_GUESTS_GROUP",
						10 => "FOR_G",
						11 => "FOR_E",
						12 => "USER",
						13 => "",
					),
					"CHECK_DATES" => "Y",
					"DETAIL_URL" => "",
					"AJAX_MODE" => "N",
					"AJAX_OPTION_JUMP" => "N",
					"AJAX_OPTION_STYLE" => "Y",
					"AJAX_OPTION_HISTORY" => "N",
					"CACHE_TYPE" => "A",
					"CACHE_TIME" => "36000000",
					"CACHE_FILTER" => "N",
					"CACHE_GROUPS" => "Y",
					"PREVIEW_TRUNCATE_LEN" => "",
					"ACTIVE_DATE_FORMAT" => "d.m.Y",
					"SET_TITLE" => "N",
					"SET_STATUS_404" => "N",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"ADD_SECTIONS_CHAIN" => "N",
					"HIDE_LINK_WHEN_NO_DETAIL" => "N",
					"PARENT_SECTION" => "",
					"PARENT_SECTION_CODE" => "",
					"INCLUDE_SUBSECTIONS" => "Y",
					"PAGER_TEMPLATE" => ".default",
					"DISPLAY_TOP_PAGER" => "N",
					"DISPLAY_BOTTOM_PAGER" => "N",
					"PAGER_TITLE" => "Новости",
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
					"PAGER_SHOW_ALL" => "N",
					"DISPLAY_DATE" => "Y",
					"DISPLAY_NAME" => "Y",
					"DISPLAY_PICTURE" => "Y",
					"DISPLAY_PREVIEW_TEXT" => "Y",
					"AJAX_OPTION_ADDITIONAL" => ""
					),
					false
				);?> 				<span id="cho">YOUR CHOICE: <span id="adr"></span></span> 			</figure> 			
        <div class="sep2"></div>
       			<figure id="step3"> 				<?$APPLICATION->IncludeComponent(
	"luxor:form",
	"company",
	Array(
		"START_PAGE" => "new",
		"SHOW_LIST_PAGE" => "Y",
		"SHOW_EDIT_PAGE" => "Y",
		"SHOW_VIEW_PAGE" => "Y",
		"SUCCESS_URL" => "",
		"WEB_FORM_ID" => "3",
		"RESULT_ID" => $_REQUEST[RESULT_ID],
		"SHOW_ANSWER_VALUE" => "Y",
		"SHOW_ADDITIONAL" => "Y",
		"SHOW_STATUS" => "Y",
		"EDIT_ADDITIONAL" => "Y",
		"EDIT_STATUS" => "Y",
		"NOT_SHOW_FILTER" => array(0=>"",1=>"",),
		"NOT_SHOW_TABLE" => array(0=>"",1=>"",),
		"IGNORE_CUSTOM_TEMPLATE" => "N",
		"USE_EXTENDED_ERRORS" => "N",
		"SEF_MODE" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"CHAIN_ITEM_TEXT" => "",
		"CHAIN_ITEM_LINK" => "",
		"AJAX_OPTION_ADDITIONAL" => "",
		"VARIABLE_ALIASES" => Array(
			"action" => "action"
		)
	)
);?> 				<?$APPLICATION->IncludeComponent(
	"luxor:form",
	"company2",
	Array(
		"START_PAGE" => "new",
		"SHOW_LIST_PAGE" => "Y",
		"SHOW_EDIT_PAGE" => "Y",
		"SHOW_VIEW_PAGE" => "Y",
		"SUCCESS_URL" => "",
		"WEB_FORM_ID" => "4",
		"RESULT_ID" => $_REQUEST[RESULT_ID],
		"SHOW_ANSWER_VALUE" => "Y",
		"SHOW_ADDITIONAL" => "Y",
		"SHOW_STATUS" => "Y",
		"EDIT_ADDITIONAL" => "Y",
		"EDIT_STATUS" => "Y",
		"NOT_SHOW_FILTER" => array(0=>"",1=>"",),
		"NOT_SHOW_TABLE" => array(0=>"",1=>"",),
		"IGNORE_CUSTOM_TEMPLATE" => "N",
		"USE_EXTENDED_ERRORS" => "N",
		"SEF_MODE" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"CHAIN_ITEM_TEXT" => "",
		"CHAIN_ITEM_LINK" => "",
		"AJAX_OPTION_ADDITIONAL" => "",
		"VARIABLE_ALIASES" => Array(
			"action" => "action"
		)
	)
);?> 			</figure> 			</div>
     			
      <div class="sep2"></div>
     			
      <table id="t_step3"> 				
        <tbody>
          <tr> 					<td> 						<span class="chek3"></span> 						<input type="checkbox" class="none" value="agree" name="agree" /> 					</td> 					<td> 						<span class="agr">I have read and agree to the <a href="/terms/" target = "_blank">terms and conditions</a> of participation</span> 					</td> 				</tr>
         			</tbody>
      </table>
     			<input type="button" class="ex_but" value="Send" name="ex_but" /> 			
      <div id="ex_login"></div>
     			
      <div id="resp_ajax"></div>
     		</div>
   		
    <div class="reg_buy"> 			
      <div id="block_exh"> 			<figure id="step2"> 				<span class="title">ГОСТЬ (VIP BUYER, BUYER, HOSTED BUYER)</span> 				<span class="choose2">ПОЖАЛУЙСТА, ВЫБЕРИТЕ МЕРОПРИЯТИЕ, НА КОТОРОЕ ВЫ БЫ ХОТЕЛИ ЗАРЕГИСТРИРОВАТЬСЯ. ВОЗМОЖНО ВЫБРАТЬ ТОЛЬКО ОДНО МЕРОПРИЯТИЕ.
            <br />
          ПОЖАЛУЙСТА, ЗАПОЛНЯЙТЕ ФОРМУ НА АНГЛИЙСКОМ ЯЗЫКЕ:</span> 				<?
				CModule::IncludeModule("iblock");
				$APPLICATION->IncludeComponent("luxor:news.list", "buy", array(
	"IBLOCK_TYPE" => "exhib",
	"IBLOCK_ID" => "15",
	"NEWS_COUNT" => "false",
	"SORT_BY1" => "SORT",
	"SORT_ORDER1" => "DESC",
	"SORT_BY2" => "SORT",
	"SORT_ORDER2" => "ASC",
	"FILTER_NAME" => "",
	"FIELD_CODE" => array(
		0 => "",
		1 => "",
	),
	"PROPERTY_CODE" => array(
		0 => "NAME_EN",
		1 => "DETAIL_TEXT_EN",
		2 => "PREVIEW_TEXT_EN",
		3 => "STATUS",
		4 => "STATUS_G_M",
		5 => "STATUS_G_E",
		6 => "USER_GROUP_ID",
		7 => "NC_PARTICIPANTS_GROUP",
		8 => "C_GUESTS_GROUP",
		9 => "NC_GUESTS_GROUP",
		10 => "FOR_G",
		11 => "FOR_E",
		12 => "USER",
		13 => "",
	),
	"CHECK_DATES" => "Y",
	"DETAIL_URL" => "",
	"AJAX_MODE" => "N",
	"AJAX_OPTION_JUMP" => "N",
	"AJAX_OPTION_STYLE" => "Y",
	"AJAX_OPTION_HISTORY" => "N",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "36000000",
	"CACHE_FILTER" => "N",
	"CACHE_GROUPS" => "Y",
	"PREVIEW_TRUNCATE_LEN" => "",
	"ACTIVE_DATE_FORMAT" => "d.m.Y",
	"SET_TITLE" => "N",
	"SET_STATUS_404" => "N",
	"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
	"ADD_SECTIONS_CHAIN" => "N",
	"HIDE_LINK_WHEN_NO_DETAIL" => "N",
	"PARENT_SECTION" => "",
	"PARENT_SECTION_CODE" => "",
	"INCLUDE_SUBSECTIONS" => "Y",
	"PAGER_TEMPLATE" => ".default",
	"DISPLAY_TOP_PAGER" => "N",
	"DISPLAY_BOTTOM_PAGER" => "N",
	"PAGER_TITLE" => "Новости",
	"PAGER_SHOW_ALWAYS" => "N",
	"PAGER_DESC_NUMBERING" => "N",
	"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
	"PAGER_SHOW_ALL" => "N",
	"DISPLAY_DATE" => "Y",
	"DISPLAY_NAME" => "Y",
	"DISPLAY_PICTURE" => "Y",
	"DISPLAY_PREVIEW_TEXT" => "Y",
	"AJAX_OPTION_ADDITIONAL" => ""
	),
	false
);?> 				<span id="cho2">ВЫ ВЫБРАЛИ: <span id="adr2"></span></span> 			</figure> 			
        <div class="sep2"></div>
       				<figure id="step3"> 					<?$APPLICATION->IncludeComponent(
	"luxor:form",
	"guest",
	Array(
		"START_PAGE" => "new",
		"SHOW_LIST_PAGE" => "Y",
		"SHOW_EDIT_PAGE" => "Y",
		"SHOW_VIEW_PAGE" => "Y",
		"SUCCESS_URL" => "",
		"WEB_FORM_ID" => "10",
		"RESULT_ID" => $_REQUEST[RESULT_ID],
		"SHOW_ANSWER_VALUE" => "N",
		"SHOW_ADDITIONAL" => "N",
		"SHOW_STATUS" => "Y",
		"EDIT_ADDITIONAL" => "N",
		"EDIT_STATUS" => "Y",
		"NOT_SHOW_FILTER" => array(0=>"SIMPLE_QUESTION_115",1=>"SIMPLE_QUESTION_677",2=>"SIMPLE_QUESTION_773",3=>"SIMPLE_QUESTION_756",4=>"SIMPLE_QUESTION_672",5=>"SIMPLE_QUESTION_678",6=>"SIMPLE_QUESTION_750",7=>"SIMPLE_QUESTION_823",8=>"SIMPLE_QUESTION_391",9=>"SIMPLE_QUESTION_636",10=>"SIMPLE_QUESTION_373",11=>"SIMPLE_QUESTION_279",12=>"SIMPLE_QUESTION_552",13=>"SIMPLE_QUESTION_367",14=>"SIMPLE_QUESTION_482",15=>"SIMPLE_QUESTION_187",16=>"SIMPLE_QUESTION_421",17=>"SIMPLE_QUESTION_225",18=>"SIMPLE_QUESTION_770",19=>"SIMPLE_QUESTION_384",20=>"SIMPLE_QUESTION_280",21=>"SIMPLE_QUESTION_765",22=>"SIMPLE_QUESTION_627",23=>"SIMPLE_QUESTION_788",24=>"SIMPLE_QUESTION_230",25=>"SIMPLE_QUESTION_474",26=>"SIMPLE_QUESTION_435",27=>"SIMPLE_QUESTION_300",28=>"SIMPLE_QUESTION_166",29=>"SIMPLE_QUESTION_383",30=>"SIMPLE_QUESTION_244",31=>"SIMPLE_QUESTION_212",32=>"SIMPLE_QUESTION_497",33=>"",),
		"NOT_SHOW_TABLE" => array(0=>"SIMPLE_QUESTION_115",1=>"SIMPLE_QUESTION_677",2=>"SIMPLE_QUESTION_773",3=>"SIMPLE_QUESTION_756",4=>"SIMPLE_QUESTION_672",5=>"SIMPLE_QUESTION_678",6=>"SIMPLE_QUESTION_750",7=>"SIMPLE_QUESTION_823",8=>"SIMPLE_QUESTION_391",9=>"SIMPLE_QUESTION_636",10=>"SIMPLE_QUESTION_373",11=>"SIMPLE_QUESTION_279",12=>"SIMPLE_QUESTION_552",13=>"SIMPLE_QUESTION_367",14=>"SIMPLE_QUESTION_482",15=>"SIMPLE_QUESTION_187",16=>"SIMPLE_QUESTION_421",17=>"SIMPLE_QUESTION_225",18=>"SIMPLE_QUESTION_770",19=>"SIMPLE_QUESTION_384",20=>"SIMPLE_QUESTION_280",21=>"SIMPLE_QUESTION_765",22=>"SIMPLE_QUESTION_627",23=>"SIMPLE_QUESTION_788",24=>"SIMPLE_QUESTION_230",25=>"SIMPLE_QUESTION_474",26=>"SIMPLE_QUESTION_435",27=>"SIMPLE_QUESTION_300",28=>"SIMPLE_QUESTION_166",29=>"SIMPLE_QUESTION_383",30=>"SIMPLE_QUESTION_244",31=>"SIMPLE_QUESTION_212",32=>"SIMPLE_QUESTION_497",33=>"",),
		"IGNORE_CUSTOM_TEMPLATE" => "N",
		"USE_EXTENDED_ERRORS" => "N",
		"SEF_MODE" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"CHAIN_ITEM_TEXT" => "",
		"CHAIN_ITEM_LINK" => "",
		"AJAX_OPTION_ADDITIONAL" => "",
		"VARIABLE_ALIASES" => Array(
			"action" => "action"
		)
	)
);?> 				</figure> 			</div>
     			
      <div class="sep2"></div>
     			
      <table id="t_step3"> 				
        <tbody>
          <tr> 					<td> 						<span class="chek3"></span> 						<input type="checkbox" class="none" value="agree" name="agree" /> 					</td> 					<td> 						<span class="agr">Я подтверждаю свое согласие на сбор, хранение и обработку вышеуказанных данных организаторами 							Luxury Travel Mart
                <br />
              в соответствии с ФЗ РФ «О персональных данных» от 27.07.2006 № 152-ФЗ.</span> 					</td> 				</tr>
         			</tbody>
      </table>
     			<input type="button" class="buy_but" value="Отправить" name="buy_but" /> 			
      <div id="ex_login"></div>
     			
      <div id="resp_ajax"></div>
     		</div>
   	</div>
 </form> <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
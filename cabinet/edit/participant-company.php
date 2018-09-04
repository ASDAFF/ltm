<?define("NEED_AUTH", true);?>
<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<script  src="<?=SITE_TEMPLATE_PATH?>/js/ajaxupload.3.5.js"></script>
<? 
$APPLICATION->SetAdditionalCSS("/cabinet/edit/style.css");
$APPLICATION->AddHeadScript("/cabinet/edit/script.js");
$APPLICATION->AddHeadScript("/assets/js/validate_form.js");
?>
<div id="exhibition-tab-1">
<?

define("MAX_PHOTO_COUNT", 12);
try{

    global $USER;
    if($USER->IsAdmin() && isset($_REQUEST["UID"])) {
        $userId = intval($_REQUEST["UID"]);
    } else {
        $userId = $USER->GetID();
    }

    $rsUser = CUser::GetList(($by = false), ($order = false), array("ID"=>$userId), array("SELECT"=>array("UF_*")));
    $arUser = $rsUser->Fetch();
    $curPage = "/cabinet/".$_REQUEST["EXHIBIT_CODE"]."/edit/company/".(isset($_REQUEST["UID"])?"?UID={$_REQUEST["UID"]}":"");

    $formID = 3;//форма компании представители всех выставок
    $resultId = $arUser["UF_ID_COMP"];

    $exhibCode = trim($_REQUEST["EXHIBIT_CODE"]);
    CModule::IncludeModule("form");
    CModule::IncludeModule("iblock");
    if($exhibCode)
    {
        $rsExhib = CIBlockElement::GetList(array("sort" => 'asc'), array("ACTVE" => "Y", "CODE" => $exhibCode), false, false, array("ID", "CODE", "NAME", "PROPERTY_SHORT_NAME", "PROPERTY_DATE", "PROPERTY_PARTICIPANT_EDIT", "PROPERTY_GUEST_EDIT"));
        if($arExhib = $rsExhib->Fetch())
        {
            $exhParticipantEdit = $arExhib["PROPERTY_PARTICIPANT_EDIT_VALUE"];
            $exhGuestEdit = $arExhib["PROPERTY_GUEST_EDIT_VALUE"];

        }
    }

    //получение количества незагруженных фотографий
    $galleryID = $arUser["UF_ID_GROUP"];

    $rsSectionElement = CIBlockElement::GetList(array(), array("ACTIVE" => "Y", "SECTION_ID" => $galleryID, "IBLOCK_ID" => PHOTO_GALLERY_ID), false, false, array("ID", "NAME"));
    $photoCount = $rsSectionElement->SelectedRowsCount();
	?>
<? /*
SID

SIMPLE_QUESTION_106 - Official name for invoice
SIMPLE_QUESTION_988 - Company or hotel name
SIMPLE_QUESTION_993 - Your login
SIMPLE_QUESTION_284 - Area of the business
SIMPLE_QUESTION_295 - Official adress
SIMPLE_QUESTION_320 - City
SIMPLE_QUESTION_778 - Country
SIMPLE_QUESTION_501 - http://
SIMPLE_QUESTION_163 - Company description
SIMPLE_QUESTION_876 - North America
SIMPLE_QUESTION_367 - Europe
SIMPLE_QUESTION_328 - South America
SIMPLE_QUESTION_459 - Africa
SIMPLE_QUESTION_931 - Asia
SIMPLE_QUESTION_445 - Oceania

SIMPLE_QUESTION_395 - Logo
*/?>

<? if($_SERVER["REQUEST_METHOD"] == "POST" && "Y" == $exhParticipantEdit)
{
	$arFields = array(
		"SIMPLE_QUESTION_163" => array("39" => htmlspecialcharsBx($_POST["SIMPLE_QUESTION_163"])), //Company description
		"SIMPLE_QUESTION_501" => array("38" => htmlspecialcharsBx($_POST["SIMPLE_QUESTION_501"])), //http://
		"SIMPLE_QUESTION_876" => ((isset($_POST["SIMPLE_QUESTION_876"]))?$_POST["SIMPLE_QUESTION_876"]:array()),
		"SIMPLE_QUESTION_367" => ((isset($_POST["SIMPLE_QUESTION_367"]))?$_POST["SIMPLE_QUESTION_367"]:array()),
		"SIMPLE_QUESTION_328" => ((isset($_POST["SIMPLE_QUESTION_328"]))?$_POST["SIMPLE_QUESTION_328"]:array()),
		"SIMPLE_QUESTION_459" => ((isset($_POST["SIMPLE_QUESTION_459"]))?$_POST["SIMPLE_QUESTION_459"]:array()),
		"SIMPLE_QUESTION_931" => ((isset($_POST["SIMPLE_QUESTION_931"]))?$_POST["SIMPLE_QUESTION_931"]:array()),
		"SIMPLE_QUESTION_445" => ((isset($_POST["SIMPLE_QUESTION_445"]))?$_POST["SIMPLE_QUESTION_445"]:array()),
		"SIMPLE_QUESTION_284" => array($_POST["SIMPLE_QUESTION_284"] => $_POST["SIMPLE_QUESTION_284"]),	
	);

	//получение старого описания компании
	$arAnswer = CFormResult::GetDataByID(
	    $resultId,
	    array(
	        "SIMPLE_QUESTION_163",// описание компании
	        "SIMPLE_QUESTION_988"//название компании
	    ),
	    $arResult,
	    $arAnswerDescr);


	$arAnswerCompDescr = reset($arAnswerDescr["SIMPLE_QUESTION_163"]);
	$oldCompanyDescription = $arAnswerCompDescr["USER_TEXT"];

	$arAnswerCompName = reset($arAnswerDescr["SIMPLE_QUESTION_988"]);
	$compName = $arAnswerCompName["USER_TEXT"];

	//лого
	if(isset($_POST["SIMPLE_QUESTION_395"]))
	{
		$path = $_POST["SIMPLE_QUESTION_395"]["PATH"];
		$filear =  CFile::MakeFileArray($path);
		$logotipFileId = CFile::SaveFile($filear, "logo");
		$logotipResize = CFile::ResizeImageGet($logotipFileId, array('width'=>200, 'height'=> 99999), BX_RESIZE_IMAGE_PROPORTIONAL, true);
		$filear = CFile::MakeFileArray($logotipResize['src']);

		if($filear)
		{
			$arFields["SIMPLE_QUESTION_395"] = array("193" => $filear);
		}
	}
 	foreach ($arFields as $SID => $value)
	{
		CFormResult::SetField($resultId, $SID, $value);
	}

	//отправка почтового сообщения администратору

	$newCompanyDescription = htmlspecialcharsBx($_POST["SIMPLE_QUESTION_163"]);

	$arSendFields = array(
		"USER_ID" => $arUser["ID"],
        "COMPANY_NAME" => $compName,
        "COMPANY_DESCRIPTION_OLD" => $oldCompanyDescription,
        "COMPANY_DESCRIPTION_NEW" => $newCompanyDescription
	);

    //CEvent::Send("COMPANY_INFO", "s1", $arSendFields);
    //вывод информации об успешном сохранении
    echo "<p style='color:green;'>Your information has been updated. Thank you!</p>";
}

if("Y" != $exhParticipantEdit)
{
    echo "<p style='color:red;'>Data editing is blocked by the administrator!</p>";
}

    //выборка данных для вывода в форме
    $arResult = array(); $arAnswers = array();
    $data  = CFormResult::GetDataByID($resultId, array(), $arResult, $arAnswers);
    $WebFormId = CForm::GetDataByID(
        $formID,
        $arForm,
        $arQuestions,
        $arAnswersList,
        $arDropDown,
        $arMultiSelect
    );
    ?>
<div class="create-page">
	<div class="creating-page">
	<form method="POST" action ="<?= $curPage?>" enctype="multipart/form-data" name="company-update">
	<?=bitrix_sessid_post()?>
		<div class="pull-overflow create-company first-line clearfix">
			<div class="pull-left company-info data-control">
				<div class="title">Name of your company</div>
				<?
				$arAnswer = reset($arAnswers["SIMPLE_QUESTION_988"]);
				$value = $arAnswer["USER_TEXT"];
				?>
				<input type="text" name="form_text_30" id="" class="form-control" value="<?= $value?>" disabled=disabled />
			</div>
			<div class="pull-left company-info data-control">
				<div class="title">Area of business</div>
				<?
				$arAnswer = reset($arAnswers["SIMPLE_QUESTION_284"]);
				$valueId = $arAnswer["ANSWER_ID"];
				$value = $arAnswer["ANSWER_TEXT"];
				?>
				<input type="hidden" name="SIMPLE_QUESTION_284" value="<?= $valueId?>" />
				<input type="text" name="area_of_business" class="form-control" value="<?= $value?>" disabled=disabled />
				
				<? $arAnswer = reset($arAnswers["SIMPLE_QUESTION_284"]);?>
			</div>
			
			<div class="pull-left company-info data-control">
				<div class="title">Web</div>
				<?
				$arAnswer = reset($arAnswers["SIMPLE_QUESTION_501"]);
				$value = $arAnswer["USER_TEXT"];
				?>
				<input type="text" name="SIMPLE_QUESTION_501" id="" value="<?= $value?>" class="form-control" />
			</div>
		</div>

		<div class="description">
			<div class="title"> Please enter a description of your company and service</div>
			<?
				$arAnswer = reset($arAnswers["SIMPLE_QUESTION_163"]);
				$value = $arAnswer["USER_TEXT"];
				?>
			<textarea name="SIMPLE_QUESTION_163" id="" cols="30" rows="10"><?= $value?></textarea>
			<?$APPLICATION->IncludeComponent(
				"rarus:members.photoloader",
				"",
				Array(
					"IBLOCK_ID" => "16",
					"GALLERY_ID" => $galleryID,
					"USER_ID" => $userId,
					"MAX_PHOTO_COUNT" => MAX_PHOTO_COUNT,
					"MAX_FILE_SIZE" => 0,
					"CACHE_TIME" => "3600"
				),
				false
			);?>
		</div>
		
		<? if("Y" == $exhParticipantEdit):?>
			<input type="submit" value="save changes" class="button-green ltm-btn" />
		<? endif;?>
		
		<div class="pull-overflow city-link">
			<div class="upload-logo">
				<div class="title">Jpg and Svg only!</div>
				<label class="button-dark ltm-btn" id="upload_logo" >upload logo<? /*<input type="hidden" name="SIMPLE_QUESTION_395" id="" value="" />*/?></label>
                <?
                $arAnswer = reset($arAnswers["SIMPLE_QUESTION_395"]);
                $value = !$arAnswer["USER_FILE_ID"]?:CFile::GetPath($arAnswer["USER_FILE_ID"]);
                ?>
                <img src="<?= $value?>" alt="" class="show-uploaded">
			</div>

			<div class="pull-left company-info priority-wrap" style="display: block; clear: both;">
			
				<div class="title">Select the country of your business</div>
				
				<div class="priority-check-global">
					<label class="check-global" for="check_priority_global" type="checkbox">Global / Worldwide</label>
					<input id="check_priority_global" type="checkbox" name="PRIORITY_GLOBAL" value="" class = "none" />
				</div>
				<?
                $arPriorArea = array("SIMPLE_QUESTION_876", "SIMPLE_QUESTION_367", "SIMPLE_QUESTION_328", "SIMPLE_QUESTION_459", "SIMPLE_QUESTION_931", "SIMPLE_QUESTION_445");
                foreach($arAnswersList as $SID => $arAnswerelement):?>
					
	                    <? if(in_array($SID, $arPriorArea)):?>
	                    <div class="check-priority">
	                    	<div class="priority-check-all">
								<label class="check-all <?if(count($arAnswerelement) == count($arAnswers[$SID])):?>active-all<?endif; ?>" for="check_<?=$SID?>_ALL" type="checkbox"></label>
								<input id="check_<?=$SID?>_ALL" type="checkbox" name="<?=$SID?>_ALL" value="" class = "none" <?if(count($arAnswerelement) == count($arAnswers[$SID])):?>checked='checked'<?endif;?>/>
								
								<a href="javascript:void(0);" class="priority-toggle priority-name"><ins><?= $arQuestions[$SID]["TITLE"]?></ins></a>
								<a href="javascript:void(0);" class="priority-toggle priority-switch"><ins>Show all countries</ins></a>
							</div>

	    					 <div class="priority-items" id="priority-items-<?= randString(5)?>">
		    					<? foreach ($arAnswerelement as $arAnswerRes):?>
		    						<label class="check-group <?= (isset($arAnswers[$SID][$arAnswerRes["ID"]]))?"active-group":"";?>" for="check_<?=$SID?>_<?=$arAnswerRes["ID"]?>" type="checkbox" ><?=$arAnswerRes["MESSAGE"]?></label>
									<input id="check_<?=$SID?>_<?=$arAnswerRes["ID"]?>" type="checkbox" name="<?= $SID?>[<?= $arAnswerRes["ID"]?>]" value="<?=$arAnswerRes["ID"]?>" class = "none" <?= (isset($arAnswers[$SID][$arAnswerRes["ID"]]))?"checked='checked'":"";?>/>
			    				<? endforeach;?>
	    					</div>
	    				</div>
	    				<?endif;?>
    				
               <? endforeach;?>

			</div>
		</div>
		<? if("Y" == $exhParticipantEdit):?>
			<input type="button" value="save changes" class="button-green ltm-btn submit-particip-btn" />
			<input type="submit" value="save changes" class="button-green ltm-btn participant-submit submit-particip-send" style="display: none;" />
		<? endif;?>
	</form>
	</div>
</div>

<?}catch(Exception $e){}?>
</div>

<script type="text/javascript">
$(function(){
	var btnUploadLogo = $("#upload_logo");
	var btnUploadPhoto = $("#upload_photo");
	var photoCount = $("#photo_count");
	new AjaxUpload(btnUploadLogo, {
		action: "/ajax/upload_logo.php",
		name: 	"logo",
		data: {sid: '<?= bitrix_sessid()?>'},
		onChange: function(file, ext){
			var fileData = this._input.files[0];
			if(btnUploadLogo.next().hasClass("load_img"))
			{
				return false;
			}

            if(!ext || !(/^(jpg|jpeg|svg|svgz)$/.test(ext[0])) )
            {
            	alert("Photo format should be only jpg or svg");
            	return false;
            };
			
			var reader = new FileReader();
			reader.readAsDataURL(fileData);
			reader.onload = function(e) {
				document.querySelector('.show-uploaded').src = e.target.result;
			}
		},
		onSubmit: function(file, ext)
		{
			if(!btnUploadLogo.next().hasClass("load_img"))
			{
				btnUploadLogo.after('<span class = "load_img"></span>');
			}
		},
		onComplete: function(file, response)
		{
			if(btnUploadLogo.next().hasClass("load_img"))
			{
				btnUploadLogo.next().remove();
			}
			response = BX.parseJSON(response);

			if(response.STATUS == "OK")
			{
				btnUploadLogo.siblings("input[type=hidden]").each(function(){$(this).remove()});
				btnUploadLogo.after("<input name='SIMPLE_QUESTION_395[PATH]' type='hidden' value='"+ response.PATH + "'/>");
				//document.querySelector('.show-uploaded').src = response.PATH;

			}
			else
			{
				alert(response.MESSAGE);
			}

		}
	});
});

LANGUAGE_ID = 'en';
</script>
<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
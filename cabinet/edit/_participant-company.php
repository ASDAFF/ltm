<?define("NEED_AUTH");?>
<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<script  src="<?=SITE_TEMPLATE_PATH?>/js/ajaxupload.3.5.js"></script>
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
		"SIMPLE_QUESTION_163" => array("39" => htmlspecialchars($_POST["SIMPLE_QUESTION_163"])), //Company description
		"SIMPLE_QUESTION_501" => array("38" => htmlspecialchars($_POST["SIMPLE_QUESTION_501"])), //http://
		"SIMPLE_QUESTION_876" => ((isset($_POST["SIMPLE_QUESTION_876"]))?$_POST["SIMPLE_QUESTION_876"]:array()),
		"SIMPLE_QUESTION_367" => ((isset($_POST["SIMPLE_QUESTION_367"]))?$_POST["SIMPLE_QUESTION_367"]:array()),
		"SIMPLE_QUESTION_328" => ((isset($_POST["SIMPLE_QUESTION_328"]))?$_POST["SIMPLE_QUESTION_328"]:array()),
		"SIMPLE_QUESTION_459" => ((isset($_POST["SIMPLE_QUESTION_459"]))?$_POST["SIMPLE_QUESTION_459"]:array()),
		"SIMPLE_QUESTION_931" => ((isset($_POST["SIMPLE_QUESTION_931"]))?$_POST["SIMPLE_QUESTION_931"]:array()),
		"SIMPLE_QUESTION_445" => ((isset($_POST["SIMPLE_QUESTION_445"]))?$_POST["SIMPLE_QUESTION_445"]:array())
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
		$logotipFileId = CFile::SaveFile($filear, "upload");
		$logotipResize = CFile::ResizeImageGet($logotipFileId, array('width'=>100, 'height'=> 99999), BX_RESIZE_IMAGE_PROPORTIONAL, true);
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

	//фото
	if(isset($_POST["PHOTO"]))
	{
		$el = new CIBlockElement;
		foreach ($_POST["PHOTO"] as $path)
		{
		    if($photoCount >= MAX_PHOTO_COUNT)
		    {
		    	break;
		    }
			$path = htmlspecialchars($path);

			$arFields = array(
				"MODIFIED_BY"    => $arUser["ID"], // элемент изменен текущим пользователем
				"IBLOCK_SECTION_ID" => $galleryID,          // элемент лежит в корне раздела
				"IBLOCK_ID"      => PHOTO_GALLERY_ID,//галерея
				"NAME"           => "Фото " . (++$photoCount),
				"ACTIVE"         => "Y",            // активен
				"PREVIEW_PICTURE" => CFile::MakeFileArray($path)
			);

			$resiltId =  $el->Add($arFields);
		}
	}

	//отправка почтового сообщения администратору

	$newCompanyDescription = htmlspecialchars($_POST["SIMPLE_QUESTION_163"]);

	$arSendFields = array(
		"USER_ID" => $arUser["ID"],
        "COMPANY_NAME" => $compName,
        "COMPANY_DESCRIPTION_OLD" => $oldCompanyDescription,
        "COMPANY_DESCRIPTION_NEW" => $newCompanyDescription
	);

    CEvent::Send("COMPANY_INFO", "s1", $arSendFields);
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
	<form method="POST" action ="<?= $curPage?>" enctype="multipart/form-data">
	<?=bitrix_sessid_post()?>
		<div class="pull-overflow create-company">
			<div class="pull-left company-info data-control">
				<div class="title">Name of your company</div>
				<?
				$arAnswer = reset($arAnswers["SIMPLE_QUESTION_988"]);
				$value = $arAnswer["USER_TEXT"];
				?>
				<input type="text" name="form_text_30" id="" class="form-control" value="<?= $value?>" disabled=disabled />
			</div>
			<div class="pull-left company-info data-control">
				<div class="title">Select area of business</div>
				<?
				$arAnswer = reset($arAnswers["SIMPLE_QUESTION_284"]);
				$value = $arAnswer["ANSWER_TEXT"];
				?>
			<input type="text" name="SIMPLE_QUESTION_284" id="" class="form-control" value="<?= $value?>" disabled=disabled />
			</div>
			<div class="pull-left company-info">
				<div class="title">Jpg only!</div>
				<label class="button-dark ltm-btn" id="upload_logo" >upload logo<? /*<input type="hidden" name="SIMPLE_QUESTION_395" id="" value="" />*/?></label>
			</div>
		</div>

		<div class="description">
			<div class="title"> Please enter a description of your company and service</div>
			<?
				$arAnswer = reset($arAnswers["SIMPLE_QUESTION_163"]);
				$value = $arAnswer["USER_TEXT"];
				?>
			<textarea name="SIMPLE_QUESTION_163" id="" cols="30" rows="10"><?= $value?></textarea>
			<div class="title">	Please upload a minimum of 6 (maximum 12) photos of your company/hotel. The maximum size of each photo is 3mb. Don’t forget &mdash; the better the quality of your photos, the better the impression of your company/hotel.</div>
			<label class="button-dark ltm-btn" id="upload_photo">upload photos<? /*<input type="file" name="" id="" value=""/>*/?></label>
			<span>You can upload <span id="photo_count"><?= MAX_PHOTO_COUNT - $photoCount?></span> photos</span>
		</div>

		<div class="pull-overflow city-link">
			<div class="pull-left company-info data-control">
				<div class="title">Web</div>
				<?
				$arAnswer = reset($arAnswers["SIMPLE_QUESTION_501"]);
				$value = $arAnswer["USER_TEXT"];
				?>
				<input type="text" name="SIMPLE_QUESTION_501" id="" value="<?= $value?>" class="form-control" />
			</div>

			<div class="pull-left company-info" style="display: block; clear: both;">
				<div class="title">Please select a country (or countries) of your business/location</div>
				<?
                $arPriorArea = array("SIMPLE_QUESTION_876", "SIMPLE_QUESTION_367", "SIMPLE_QUESTION_328", "SIMPLE_QUESTION_459", "SIMPLE_QUESTION_931", "SIMPLE_QUESTION_445");
                foreach($arAnswersList as $SID => $arAnswerelement):?>
					<div class="area">
	                    <? if(in_array($SID, $arPriorArea)):?>
	                    	<div class="title"><?= $arQuestions[$SID]["TITLE"]?></div>
	                    	<div >
	    	    				<input type="checkbox" name="<?= $SID?>_all" id="<?= $SID?>_all" class="check_all" />
	    	    				<label for="<?= $SID?>_all">All</label>
	    					</div>
	    					<? foreach ($arAnswerelement as $arAnswerRes):?>
	    					<div>
	    	    				<input type="checkbox" name="<?= $SID?>[<?= $arAnswerRes["ID"]?>]" id="<?= $SID?>[<?= $arAnswerRes["ID"]?>]" value="<?= $arAnswerRes["ID"]?>" <?= (isset($arAnswers[$SID][$arAnswerRes["ID"]]))?"checked='checked'":"";?>>
	    	    				<label for="<?= $SID?>[<?= $arAnswerRes["ID"]?>]"><?= $arAnswerRes["MESSAGE"]?></label>
	    					</div>

	    					<? endforeach;?>
	    				<?endif;?>
    				</div>
               <? endforeach;?>
			</div>
		</div>
		<? if("Y" == $exhParticipantEdit):?>
		<input type="submit" value="Save changes" class="button-green ltm-btn" />
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

			if(btnUploadLogo.next().hasClass("load_img"))
			{
				return false;
			}

            if(!ext || !(/^(jpg|jpeg)$/.test(ext[0])) )
            {
            	alert("Photo format should be only jpg");
            	return false;
            };
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
			response = $.parseJSON(response);

			if(response.STATUS == "OK")
			{
				btnUploadLogo.siblings("input[type=hidden]").each(function(){$(this).remove()});
				btnUploadLogo.after("<input name='SIMPLE_QUESTION_395[PATH]' type='hidden' value='"+ response.PATH + "'/>");
			}
			else
			{
				alert(response.MESSAGE);
			}

		}
	});


	new AjaxUpload(btnUploadPhoto, {
		action: "/ajax/upload_photo.php",
		name: 	"photo",
		data: {sid: '<?= bitrix_sessid()?>'},
		onChange: function(file, ext){
			var count = parseInt(photoCount.text());
			if(!count || (count <= 0) || btnUploadPhoto.next().hasClass("load_img"))
			{
				return false;
			}

            if(!ext || !(/^(jpg|jpeg)$/.test(ext[0])) )
            {
            	alert("Photo format should be only jpg");
            	return false;
            };
		},
		onSubmit: function(file, ext)
		{
			if(!btnUploadPhoto.next().hasClass("load_img"))
			{
				btnUploadPhoto.after('<span class = "load_img"></span>');
			}
		},
		onComplete: function(file, response)
		{
			if(btnUploadPhoto.next().hasClass("load_img"))
			{
				btnUploadPhoto.next().remove();
			}
			response = $.parseJSON(response);

			if(response.STATUS == "OK")
			{
				btnUploadPhoto.after("<input name='PHOTO[]' type='hidden' value='"+ response.PATH + "'/>");

				var count = parseInt(photoCount.text());
				if(count && (count > 0))
				{
					count--;
					photoCount.text(count);
				}
			}
			else
			{
				alert(response.MESSAGE);
			}

		}
	});

	$("input.check_all").change(function(){
		var input = $(this);
		var area = input.closest("div.area");

		if(!input.prop("checked"))
		{
			area.find("input[type=checkbox][name!=" + input.attr("name") + "]").each(function(){$(this).prop("checked", false)});
		}
		else
		{
			area.find("input[type=checkbox][name!=" + input.attr("name") + "]").each(function(){$(this).prop("checked", true)});
		}
	});

});
</script>

<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
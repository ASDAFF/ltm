<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Spectr\Meeting\Models\SettingsTable;
use Spectr\Meeting\Models\WishlistTable;

Loader::includeModule('doka.meetings');

class MeetingsWishlist extends CBitrixComponent
{
    private $componentTemplate = 'guest';

    public function onPrepareComponentParams(array $arParams): array
    {
        $result = $arParams;
        $result['EXHIBITION_ID'] = (int)$arParams['EXHIBITION_ID'];
        $result['USER_ID'] = (int)$arParams['USER_ID'];
        $result['ADD_LINK_TO_WISHLIST'] = $result['ADD_LINK_TO_WISHLIST'] ?: "cabinet/service/wish.php";
        return $result;
    }

    public function executeComponent()
    {
        $this->checkComponentTemplate();
        if ($this->request->get("mode") !== 'pdf') {
            $this->arResult = [
                'WISHLIST_FOR_USER' => $this->getWishListForUser(),
                'WISHLIST_FROM_USER' => $this->getWishListFromUser(),
            ];
            $this->includeComponentTemplate();
        } else {
            global $APPLICATION;
            $APPLICATION->RestartBuffer();
            $this->generetePdf();
        }

    }

    public function checkComponentTemplate()
    {
        if ($this->arParams['USER_TYPE'] === 'PARTICIP') {
            $this->componentTemplate = 'particip';
        }
    }

    public function getWishListForUser(): array
    {
        $result = WishlistTable::getWishlistForUser($this->arParams['USER_ID'], $this->arParams['EXHIBITION_ID']);
        return $result;
    }

    public function getWishListFromUser(): array
    {
        $result = WishlistTable::getWishlistFromUser($this->arParams['USER_ID'], $this->arParams['EXHIBITION_ID']);
        return $result;
    }

    public function generetePdf()
    {
        $wishListForUser = $this->getWishListForUser();
        $wishListFromUser = $this->getWishListFromUser();
        $exhibSettings = SettingsTable::getSettiongs($this->arParams['EXHIBITION_CODE']);
        $exhibition = SettingsTable::getExhibition($this->arParams['EXHIBITION_CODE']);
//        c($exhibition);
        $filter = ['ID' => $this->arParams['USER_ID']];
        $select = [
            'SELECT' => [$exhibSettings['REPR_PROP_CODE']],
            'FIELDS' => ['WORK_COMPANY', 'ID'],
        ];
        $rsUser = CUser::GetList(($by = "id"), ($order = "desc"), $filter, $select);
        if ($arUser = $rsUser->Fetch()) {
            if ($fioParticip == '') {
                $fioParticip = $arUser[$exhibSettings['REPR_PROP_CODE']];
            }
            $arResult['USER'] = [
                'REP' => $fioParticip,
                'COMPANY' => $arUser['WORK_COMPANY'],
                'CITY' => $arResult['CITY'],
                'COL_REP' => $col_rep,
            ];
        }

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddFont('freeserif', 'I', 'freeserifi.php');
        $pdf->AddPage();
        $pdf->ImageSVG($file = DOKA_MEETINGS_MODULE_DIR . '/images/logo.svg', $x = 30, $y = 5, $w = '150', $h = '', $link = '', $align = '', $palign = '', $border = 0, $fitonpage = false);
        $pdf->setXY(0, 23);
        $pdf->SetFont('freeserif', 'B', 17);
        // Если в свойствах выставки отмечено "Есть сессия НВ"
        if ($exhibition["PROPERTIES"]["HB_EXIST"]['VALUE']) {
            // Если в настройках встреч отмечено "Сессия с НВ"
            if ($exhibSettings["IS_HB"]) {
                $exhibition["PROPERTIES"]["V_RU"]['VALUE'] .= " Hosted Buyers сессия\n";
                $dayline = "День 1, 1 марта 2018";
            } else {
                $dayline = "День 2, 2 марта 2018";
                $exhibition["PROPERTIES"]["V_RU"]['VALUE'] .= "\n";
            }
        }
        $pdf->multiCell(210, 5, "Список неподтвержденных запросов на\n" . $exhibition["PROPERTIES"]["V_RU"]['VALUE'] . $dayline, 0, C);
        $pdf->SetFont('freeserif', '', 15);
        $pdf->setXY(30, $pdf->getY() + 2);
        if (in_array($arResult["APP_ID"], [1, 6]) && $arResult['IS_HB']) {
            $pdf->multiCell(210, 5, $arResult["USER"]['COMPANY'], 0, L);
        } else {
            $pdf->multiCell(210, 5, $arResult["USER"]['COMPANY'] . ", " . $arResult["USER"]['CITY'], 0, L);
        }

        $pdf->setXY(30, $pdf->getY() + 1);

        //если есть коллега, выводим его через запятую
        if (!empty($arResult["USER"]["REP"]) && !empty(trim($arResult["USER"]["COL_REP"]))) {
            $pdf->multiCell(300, 5, trim($arResult["USER"]["REP"]) . ", " . trim($arResult["USER"]["COL_REP"]), 0, L);
        } else {
            $pdf->multiCell(300, 5, trim($arResult["USER"]["REP"]), 0, L);
        }

        $pdf->SetFont('freeserif', 'B', 13);
        $pdf->setXY(0, $pdf->getY() + 5);
        $pdf->multiCell(210, 5, "Вы также хотели бы встретиться со следующими компаниями", 0, C);

        $pdf->SetFont('freeserif', '', 10);
        $pdf->setXY(0, $pdf->getY() + 1);
        $pdf->multiCell(210, 5, "(возможно, данные участники отклонили ваши запросы или их расписание уже полное):", 0, C);


        /* Формируем таблицу */
        if (!$wishListFromUser) {
            $pdf->setXY(0, $pdf->getY() + 5);
            $pdf->SetFont('freeserif', '', 13);
            $pdf->multiCell(210, 5, "Этот список запросов пуст.", 0, C);
        } else {
            $pdf->setXY(20, $pdf->getY() + 5);
            $pdf->SetFont('freeserif', '', 11);

            $tbl = '<table cellspacing="0" cellpadding="5" border="1">
			<tr>
				<td align="center" width="40">№</td>
				<td align="center" width="220">Компания</td>
				<td align="center" width="160">Представитель</td>
				<td align="center" width="90">Причина</td>
			</tr>';
            $i = 1;
            foreach ($wishListFromUser as $item) {
                $tbl .= '<tr>
				<td align="center">' . $i . '</td>
				<td>' . $item["COMPANY_NAME"] . '</td>
				<td>' . $item["COMPANY_REP"] . '</td>
				<td>' . $arResult['STATUS_REQUEST'][$item["REASON"]] . '</td>
			</tr>';
                $i++;
            }
            $tbl .= '</table>';
            $pdf->writeHTML($tbl, true, false, false, false, '');
        }


        $pdf->SetFont('freeserif', 'B', 13);
        $pdf->setXY(0, $pdf->getY() + 10);
        $pdf->multiCell(210, 5, "С вами также хотели бы встретиться следующие компании", 0, C);

        $pdf->SetFont('freeserif', '', 10);
        $pdf->setX(0);
        $pdf->multiCell(210, 5, "(возможно, вы отклонили запросы от этих участников или ваше расписание уже полное):", 0, C);

        if (!$wishListForUser) {
            $pdf->setXY(0, $pdf->getY() + 5);
            $pdf->SetFont('freeserif', '', 13);
            $pdf->multiCell(210, 5, "Этот список запросов пуст.", 0, C);
        } else {
            $pdf->setXY(20, $pdf->getY() + 5);
            $pdf->SetFont('freeserif', '', 11);

            $tbl = '<table cellspacing="0" cellpadding="5" border="1">
			<tr>
				<td align="center" width="40">№</td>
				<td align="center" width="220">Компания</td>
				<td align="center" width="160">Представитель</td>
				<td align="center" width="90">Причина</td>
			</tr>';
            $i = 1;
            foreach ($wishListForUser as $item) {
                $tbl .= '<tr>
				<td align="center">' . $i . '</td>
				<td>' . $item["COMPANY_NAME"] . '</td>
				<td>' . $item["COMPANY_REP"] . '</td>
				<td>' . $arResult['STATUS_REQUEST'][$item["REASON"]] . '</td>
			</tr>';
                $i++;
            }
            $tbl .= '</table>';
            $pdf->writeHTML($tbl, true, false, false, false, '');

        }
        $pdf->setXY(20, $pdf->getY() + 10);
        $html = '<p>Вы можете встретиться со всеми компаниями, указанными выше, в любое другое время Luxury Travel
Mart, например, во время ланча, перерыва на кофе или на вечерней сессии.<p>';
        $pdf->writeHTML($html, true, false, false, false, '');
        $pdf->Output("print_wish.pdf", I);
    }
}
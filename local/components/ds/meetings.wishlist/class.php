<?php
if ( !defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Spectr\Meeting\Models\WishlistTable;
use Bitrix\Main\Localization\Loc;
use Spectr\Meeting\Helpers\User;
use Spectr\Meeting\Models\RegistrGuestColleagueTable;

CBitrixComponent::includeComponentClass('ds:meetings.request');

class MeetingsWishlist extends MeetingsRequest
{
    private $templateNameForParticipant = 'PARTICIP';

    public function onPrepareComponentParams($arParams): array
    {
        $params = [];

        $params['USER_ID']              = (int)$arParams['USER_ID'];
        $params['EXHIBITION_IBLOCK_ID'] = (int)$arParams['EXHIBITION_IBLOCK_ID'];
        $params['EXHIBITION_CODE']      = (string)$arParams['EXHIBITION_CODE'];
        $params['ADD_LINK_TO_WISHLIST'] = $arParams['ADD_LINK_TO_WISHLIST'] ?: "cabinet/service/wish.php";
        $params['IS_HB']                = isset($arParams['IS_HB']) && $arParams['IS_HB'] === 'Y' ? true : false;

        return $params;
    }

    protected function init()
    {
        $this->arResult['USERS'] = [];

        return parent::init();
    }

    public function executeComponent()
    {
        $this->onIncludeComponentLang();
        try {
            $this->checkModules()
                 ->init()
                 ->getApp()
                 ->getWishListForUser()
                 ->getWishListFromUser()
                 ->getUserType()
                 ->getStatuses();
            if ($this->request->get('mode') !== 'pdf') {
                $this->includeComponentTemplate();
            } else {
                global $APPLICATION;
                $APPLICATION->RestartBuffer();
                $this->generatePdf();
            }
        } catch (\Exception $e) {
            ShowError($e->getMessage());
        }
    }

    /**
     * @throws Exception
     * TODO add sort by company name
     */
    private function getWishListForUser()
    {
        $wishlist      = WishlistTable::getWishlist($this->app->getId(), $this->arParams['USER_ID'], 'to');
        $isParticipant = $this->arResult['USER_TYPE'] !== User::PARTICIPANT_TYPE;
        $isHb          = (bool)$this->arResult['APP_SETTINGS']['IS_HB'];
        $users         = array_map(function ($item) {
            return $item['SENDER_ID'];
        }, $wishlist);
        $usersInfo     = $this->user->getUsersInfo($users, $isParticipant, $isHb);
        array_walk($usersInfo, function ($user) {
            $this->arResult['USERS'][$user['ID']] = $user;
        });
        $this->arResult['WISH_IN'] = array_map(function ($item) {
            return [
                'company_id'     => $item['SENDER_ID'],
                'company_name'   => $this->arResult['USERS'][$item['SENDER_ID']]['COMPANY'],
                'company_rep'    => $this->arResult['USERS'][$item['SENDER_ID']]['NAME'],
                'company_reason' => $item['REASON'],
            ];
        }, $wishlist);

        return $this;
    }

    /**
     * @throws Exception
     * TODO add sort by company name
     */
    private function getWishListFromUser()
    {
        $wishlist      = WishlistTable::getWishlist($this->app->getId(), $this->arParams['USER_ID'], 'from');
        $isParticipant = $this->arResult['USER_TYPE'] !== User::PARTICIPANT_TYPE;
        $isHb          = (bool)$this->arResult['APP_SETTINGS']['IS_HB'];
        $users         = array_map(function ($item) {
            return $item['RECEIVER_ID'];
        }, $wishlist);
        $usersInfo     = $this->user->getUsersInfo($users, $isParticipant, $isHb);
        array_walk($usersInfo, function ($user) {
            $this->arResult['USERS'][$user['ID']] = $user;
        });
        $this->arResult['WISH_OUT'] = array_map(function ($item) {
            return [
                'company_id'     => $item['RECEIVER_ID'],
                'company_name'   => $this->arResult['USERS'][$item['RECEIVER_ID']]['COMPANY'],
                'company_rep'    => $this->arResult['USERS'][$item['RECEIVER_ID']]['NAME'],
                'company_reason' => $item['REASON'],
            ];
        }, $wishlist);

        return $this;
    }

    private function getStatuses()
    {
        $this->arResult['STATUS_REQUEST'] = [
            WishlistTable::$types[WishlistTable::REASON_EMPTY]    => '',
            WishlistTable::$types[WishlistTable::REASON_REJECTED] => Loc::getMessage($this->arResult['USER_TYPE_NAME'].'_REJECTED'),
            WishlistTable::$types[WishlistTable::REASON_TIMEOUT]  => Loc::getMessage($this->arResult['USER_TYPE_NAME'].'_TIMEOUT'),
            WishlistTable::$types[WishlistTable::REASON_SELECTED] => Loc::getMessage($this->arResult['USER_TYPE_NAME'].'_SELECTED'),
        ];

        return $this;
    }

    /**
     * @throws Exception
     */
    private function getUserInfoForPDF()
    {
        $isParticipant  = $this->arResult['USER_TYPE'] === User::PARTICIPANT_TYPE;
        $userInfo       = $this->user->getUserInfo($this->arResult['USER_ID'], $isParticipant);
        $userInfoForPDF = [
            'COMPANY' => $userInfo['COMPANY'],
            'IS_HB'   => $userInfo['IS_HB'],
            'REP'     => $userInfo['NAME'],
        ];
        if ( !$isParticipant) {
            if ( !empty($userInfo['COLLEAGUES'])) {
                $colleague                 = RegistrGuestColleagueTable::getById($userInfo['COLLEAGUES'][0])->fetch();
                $userInfoForPDF['COL_REP'] = "{$colleague['UF_NAME']} {$colleague['UF_SURNAME']}";
            }
        }

        return $userInfoForPDF;
    }

    /**
     * @throws Exception
     */
    public function generatePdf()
    {
        $isParticipant = $this->arResult['USER_TYPE'] === User::PARTICIPANT_TYPE;
        $userType      = $isParticipant ? $this->templateNameForParticipant : $this->arResult['USER_TYPE_NAME'];
        require(DOKA_MEETINGS_MODULE_DIR.'/classes/pdf/tcpdf.php');
        require_once(DOKA_MEETINGS_MODULE_DIR."/classes/pdf/templates/wishlist_{$userType}.php");
        $pdfResult['USER']             = $this->getUserInfoForPDF();
        $pdfResult['EXHIBITION']       = $this->arResult['APP_SETTINGS'];
        $pdfResult['PARAM_EXHIBITION'] = $this->arResult['PARAM_EXHIBITION'];
        DokaGeneratePdf($pdfResult);
    }
}
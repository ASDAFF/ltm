<?php
if ( !defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Spectr\Meeting\Models\WishlistTable;
use Bitrix\Main\Localization\Loc;
use Spectr\Meeting\Helpers\User;
use Spectr\Meeting\Models\RegistrGuestColleagueTable;
use Spectr\Meeting\Models\TimeslotTable;
use Spectr\Meeting\Models\RequestTable;

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
        $this->arResult['USERS']   = [];
        $this->arResult['USER_ID'] = $this->arParams['USER_ID'];

        return parent::init();
    }

    public function executeComponent()
    {
        $this->onIncludeComponentLang();
        try {
            $this->checkModules()
                 ->init()
                 ->getApp()
                 ->getUserType()
                 ->getWishListIn()
                 ->getWishListOut()
                 ->getStatuses()
                 ->sortWishLists();
            if ($this->request->get('mode') !== 'pdf') {
                $this->getUsersForManualAddition()
                     ->includeComponentTemplate();
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
     */
    private function getWishListIn()
    {
        $wishlist      = WishlistTable::getWishlist($this->app->getId(), $this->arResult['USER_ID'], 'from');
        $isParticipant = $this->arResult['USER_TYPE'] !== User::PARTICIPANT_TYPE;
        $isHb          = (bool)$this->arResult['APP_SETTINGS']['IS_HB'];
        $users         = array_map(function ($item) {
            return $item['RECEIVER_ID'];
        }, $wishlist);
        $usersInfo     = $this->user->getUsersInfo($users, $isParticipant, $isHb);
        array_walk($usersInfo, function ($user) {
            $this->arResult['USERS'][$user['ID']] = $user;
        });
        $this->arResult['WISH_IN'] = array_map(function ($item) {
            return [
                'company_id'     => $item['RECEIVER_ID'],
                'company_name'   => $this->arResult['USERS'][$item['RECEIVER_ID']]['COMPANY'],
                'company_rep'    => $this->arResult['USERS'][$item['RECEIVER_ID']]['NAME'],
                'company_reason' => $item['REASON'],
            ];
        }, $wishlist);

        return $this;
    }

    /**
     * @throws Exception
     */
    private function getWishListOut()
    {
        $wishlist      = WishlistTable::getWishlist($this->app->getId(), $this->arResult['USER_ID'], 'to');
        $isParticipant = $this->arResult['USER_TYPE'] !== User::PARTICIPANT_TYPE;
        $isHb          = (bool)$this->arResult['APP_SETTINGS']['IS_HB'];
        $users         = array_map(function ($item) {
            return $item['SENDER_ID'];
        }, $wishlist);
        $usersInfo     = $this->user->getUsersInfo($users, $isParticipant, $isHb);
        array_walk($usersInfo, function ($user) {
            $this->arResult['USERS'][$user['ID']] = $user;
        });
        $this->arResult['WISH_OUT'] = array_map(function ($item) {
            return [
                'company_id'     => $item['SENDER_ID'],
                'company_name'   => $this->arResult['USERS'][$item['SENDER_ID']]['COMPANY'],
                'company_rep'    => $this->arResult['USERS'][$item['SENDER_ID']]['NAME'],
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

    private function sortWishLists()
    {
        usort($this->arResult['WISH_IN'], [static::class, 'sortByCompanyName']);
        usort($this->arResult['WISH_OUT'], [static::class, 'sortByCompanyName']);

        return $this;
    }

    private static function sortByCompanyName($a, $b)
    {
        return strcasecmp($a['company_name'], $b['company_name']);
    }

    /**
     * @throws Exception
     */
    private function getUsersForManualAddition()
    {
        $this->arResult['COMPANIES'] = [];
        $isHbExhibition              = $this->arResult['APP_SETTINGS']['IS_HB'];
        $isParticipant               = $this->arResult['USER_TYPE'] === User::PARTICIPANT_TYPE;
        if ($this->arResult['USER_TYPE'] === User::PARTICIPANT_TYPE) {
            $users = CGroup::GetGroupUser($this->arResult['APP_SETTINGS']['GUESTS_GROUP']);
        } else {
            $users = CGroup::GetGroupUser($this->arResult['APP_SETTINGS']['MEMBERS_GROUP']);
        }
        if ( !empty($users)) {
            $companies                          = $this->user->getUsersInfo($users, !$isParticipant, $isHbExhibition);
            $timeslotsCount                     = TimeslotTable::getList([
                'filter' => [
                    'SLOT_TYPE'     => TimeslotTable::$types[TimeslotTable::TYPE_MEET],
                    'EXHIBITION_ID' => $this->arResult['APP_ID'],
                ],
            ])->getSelectedRowsCount();
            $requests                           = RequestTable::getList([
                'filter' => [
                    '!=RECEIVER_ID' => $this->arResult['USER_ID'],
                    '!=SENDER_ID'   => $this->arResult['USER_ID'],
                    '!=STATUS'      => array_map(function ($status) {
                        return RequestTable::$statuses[$status];
                    }, RequestTable::$freeStatuses),
                    'EXHIBITION_ID' => $this->arResult['APP_ID'],
                ],
            ]);
            $this->arResult['REQUESTS_COUNTER'] = [];
            while ($request = $requests->fetch()) {
                if ($request['SENDER_ID'] !== $request['RECEIVER_ID']) {
                    $this->arResult['REQUESTS_COUNTER'][$request['SENDER_ID']]++;
                    $this->arResult['REQUESTS_COUNTER'][$request['RECEIVER_ID']]++;
                } else {
                    $this->arResult['REQUESTS_COUNTER'][$request['SENDER_ID']]++;
                }
            }
            $deniedUsers = array_map(function ($user) {
                return $user['company_id'];
            }, $this->arResult['WISH_IN']);
            array_walk($companies, function ($company) use ($timeslotsCount, $deniedUsers) {
                if (
                    $this->arResult['REQUESTS_COUNTER'][$company['ID']] === $timeslotsCount &&
                    !in_array($company['ID'], $deniedUsers)
                ) {
                    $this->arResult['COMPANIES'][] = [
                        'company_id'   => $company['ID'],
                        'company_name' => $company['COMPANY'],
                    ];
                }
            });
        }

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
                $colleagues = RegistrGuestColleagueTable::getList(['filter' => ['ID' => $userInfo['COLLEAGUES']]]);
                while ($colleague = $colleagues->fetch()) {
                    if ($colleague['UF_DAYTIME'] === RegistrGuestColleagueTable::MORNING_DAYTIME) {
                        $userInfoForPDF['COL_REP'] = "{$colleague['UF_NAME']} {$colleague['UF_SURNAME']}";
                        break;
                    }
                }
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
        $userInfo = $this->getUserInfoForPDF();
        require(DOKA_MEETINGS_MODULE_DIR.'/classes/pdf/tcpdf.php');
        require_once(DOKA_MEETINGS_MODULE_DIR."/classes/pdf/templates/wishlist_{$userType}.php");
        $pdfResult = [
            'APP_ID'           => $this->arResult['APP_ID'],
            'IS_HB'            => $userInfo['IS_HB'],
            'USER'             => $userInfo,
            'EXHIBITION'       => $this->arResult['APP_SETTINGS'],
            'PARAM_EXHIBITION' => $this->arResult['PARAM_EXHIBITION'],
            'STATUS_REQUEST'   => $this->arResult['STATUS_REQUEST'],
            'WISH_IN'          => $this->arResult['WISH_IN'],
            'WISH_OUT'         => $this->arResult['WISH_OUT'],
        ];
        DokaGeneratePdf($pdfResult);
    }
}
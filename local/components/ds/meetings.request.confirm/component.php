<?php
if ( !defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

CBitrixComponent::includeComponentClass('ds:meetings.request');

class MeetingsRequestConfirm extends MeetingsRequest
{
    protected function prepareFields()
    {
        return $this;
    }

    private function checkConfirmPosiibility()
    {
        return $this;
    }

    private function confirmRequest()
    {
        return $this;
    }

    public function executeComponent()
    {
        parent::executeComponent();
        $this->onIncludeComponentLang();
        try {
            $this->checkModules()
                 ->checkAuth()
                 ->getAppId()
                 ->getAppSettings()
                 ->getUserType()
                 ->checkRestRequestParams()
                 ->prepareFields()
                 ->checkConfirmPosiibility()
                 ->confirmRequest()
                 ->includeComponentTemplate();
        } catch (\Exception $e) {
            ShowError($e->getMessage());
        }
    }
}
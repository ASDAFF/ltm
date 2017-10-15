<?php
/**
 * Created by PhpStorm.
 * User: Anatoliy Kim
 * Date: 23.09.2017
 * Time: 0:05
 */
namespace Ltm\Domain\GuestStorage;

use Ltm\Domain\HlblockOrm\Manager as HlBlockManager;
use Ltm\Domain\GuestStorage\FormResult as LtmFormResult;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Data\Cache;

class Manager
{
    protected $countryList;
    protected $africa;
    protected $asia;
    protected $europe;
    protected $northamerica;
    protected $oceania;
    protected $southamerica;
    protected $areas;
    protected $salutation;

    public function __construct()
    {
        $this->init();
    }

    public function addActiveFormResult($resultId, $resultData = null, $user = null)
    {
        $ltmFormResult = new LtmFormResult();
        if (empty($resultId)) {
            return null;
        }
        if (empty($resultData)) {
            $resultData = $ltmFormResult->getActiveResultData($resultId);
        }
        if (empty($user)) {
            $user = $ltmFormResult->getUserByResultId($resultId);
        }

        if ($resultData !== false && $user !== false) {
            $resultData['fields']['UF_USER_ID'] = $user['ID'];
            $resultData['fields']['UF_COUNTRY'] = $this->countryList[$resultData['fields']['UF_COUNTRY']]['ID'];
            $resultData['fields']['UF_SALUTATION'] = $this->salutation[$resultData['fields']['UF_SALUTATION']]['ID'];

            $t = [];
            foreach ($resultData['fields']['UF_NORTH_AMERICA'] as $area) {
                $t[] = $this->northamerica[$area]['ID'];
            }
            $resultData['fields']['UF_NORTH_AMERICA'] = $t;

            $t = [];
            foreach ($resultData['fields']['UF_EUROPE'] as $area) {
                $t[] = $this->europe[$area]['ID'];
            }
            $resultData['fields']['UF_EUROPE'] = $t;

            $t = [];
            foreach ($resultData['fields']['UF_SOUTH_AMERICA'] as $area) {
                $t[] = $this->southamerica[$area]['ID'];
            }
            $resultData['fields']['UF_SOUTH_AMERICA'] = $t;

            $t = [];
            foreach ($resultData['fields']['UF_AFRICA'] as $area) {
                $t[] = $this->africa[$area]['ID'];
            }
            $resultData['fields']['UF_AFRICA'] = $t;

            $t = [];
            foreach ($resultData['fields']['UF_ASIA'] as $area) {
                $t[] = $this->asia[$area]['ID'];
            }
            $resultData['fields']['UF_ASIA'] = $t;

            $t = [];
            foreach ($resultData['fields']['UF_OCEANIA'] as $area) {
                $t[] = $this->oceania[$area]['ID'];
            }
            $resultData['fields']['UF_OCEANIA'] = $t;

            $t = [];
            if(!is_array($resultData['fields']['UF_PRIORITY_AREAS'])) $resultData['fields']['UF_PRIORITY_AREAS'] = [$resultData['fields']['UF_PRIORITY_AREAS']];
            foreach ($resultData['fields']['UF_PRIORITY_AREAS'] as $area) {
                $t[] = $this->areas[$area]['ID'];
            }
            $resultData['fields']['UF_PRIORITY_AREAS'] = $t;

            $conn = \Bitrix\Main\Application::getConnection();
            $conn->startTransaction();

            if (count($resultData['colleagues']) > 0) {
                $provider = HlBlockManager::getInstance()->getProvider('GuestStorageColleague');
                $entityColleague = $provider->getEntityClassName();
                $colIDs = [];
                foreach ($resultData['colleagues'] as $colleague) {
                    $colleague['UF_USER_ID'] = $user['ID'];
                    $res = $entityColleague::add($colleague);
                    if ($res->isSuccess()) {
                        $colId = $res->getId();
                        $colIDs[] = $colId;
                    } else {
                        $conn->rollbackTransaction();
                        return false;
                    }
                }
                $resultData['fields']['UF_COLLEAGUES'] = $colIDs;
            }
            $provider = HlBlockManager::getInstance()->getProvider('GuestStorage');
            $entity = $provider->getEntityClassName();
            $res = $entity::add($resultData['fields']);
            if ($res->isSuccess()) {
                $conn->commitTransaction();
                return $res->getId();
            }
            $conn->rollbackTransaction();
        }
        return false;
    }

    public function addFormResult($resultId, $resultData = null, $user = null)
    {
        $ltmFormResult = new LtmFormResult();
        if (empty($resultId)) {
            return false;
        }
        if (empty($resultData)) {
            $resultData = $ltmFormResult->getResultData($resultId);
        }
        if (empty($user)) {
            $user = $ltmFormResult->getUserByResultId($resultId);
        }

        if ($resultData !== false && $user !== false) {
            $resultData['fields']['UF_USER_ID'] = $user['ID'];
            $resultData['fields']['UF_COUNTRY'] = $this->countryList[$resultData['fields']['UF_COUNTRY']]['ID'];
            $resultData['fields']['UF_SALUTATION'] = $this->salutation[$resultData['fields']['UF_SALUTATION']]['ID'];

            $t = [];
            foreach ($resultData['fields']['UF_NORTH_AMERICA'] as $area) {
                $t[] = $this->northamerica[$area]['ID'];
            }
            $resultData['fields']['UF_NORTH_AMERICA'] = $t;

            $t = [];
            foreach ($resultData['fields']['UF_EUROPE'] as $area) {
                $t[] = $this->europe[$area]['ID'];
            }
            $resultData['fields']['UF_EUROPE'] = $t;

            $t = [];
            foreach ($resultData['fields']['UF_SOUTH_AMERICA'] as $area) {
                $t[] = $this->southamerica[$area]['ID'];
            }
            $resultData['fields']['UF_SOUTH_AMERICA'] = $t;

            $t = [];
            foreach ($resultData['fields']['UF_AFRICA'] as $area) {
                $t[] = $this->africa[$area]['ID'];
            }
            $resultData['fields']['UF_AFRICA'] = $t;

            $t = [];
            foreach ($resultData['fields']['UF_ASIA'] as $area) {
                $t[] = $this->asia[$area]['ID'];
            }
            $resultData['fields']['UF_ASIA'] = $t;

            $t = [];
            foreach ($resultData['fields']['UF_OCEANIA'] as $area) {
                $t[] = $this->oceania[$area]['ID'];
            }
            $resultData['fields']['UF_OCEANIA'] = $t;

            if (!empty($resultData['fields']['UF_PRIORITY_AREAS']) && !is_array($resultData['fields']['UF_PRIORITY_AREAS'])) {
                $resultData['fields']['UF_PRIORITY_AREAS'] = [$resultData['fields']['UF_PRIORITY_AREAS']];
            }

            $t = [];
            foreach ($resultData['fields']['UF_PRIORITY_AREAS'] as $area) {
                $t[] = $this->areas[$area]['ID'];
            }
            $resultData['fields']['UF_PRIORITY_AREAS'] = $t;


            $conn = \Bitrix\Main\Application::getConnection();
            $conn->startTransaction();

            if (count($resultData['colleagues']) > 0) {
                $provider = HlBlockManager::getInstance()->getProvider('GuestStorageColleague');
                $entityColleague = $provider->getEntityClassName();
                $colIDs = [];
                foreach ($resultData['colleagues'] as $colleague) {
                    $colleague['UF_USER_ID'] = $user['ID'];
                    $res = $entityColleague::add($colleague);
                    if ($res->isSuccess()) {
                        $colId = $res->getId();
                        $colIDs[] = $colId;
                    } else {
                        $conn->rollbackTransaction();
                        return false;
                    }
                }
                $resultData['fields']['UF_COLLEAGUES'] = $colIDs;
            }

            $provider = HlBlockManager::getInstance()->getProvider('GuestStorage');
            $entity = $provider->getEntityClassName();
            $res = $entity::add($resultData['fields']);
            if ($res->isSuccess()) {
                $conn->commitTransaction();
                $obUser = new \CUser();
                if($obUser->Update($user['ID'], ['UF_ID_COMP' => $res->getId()])){
                    \CFormResult::Delete($resultId);
                }
                return $res->getId();
            }
            $conn->rollbackTransaction();
        }
        return false;
    }

    public function getResultListByUserIDs($userIDs)
    {
        $provider = HlBlockManager::getInstance()->getProvider('GuestStorage');
        $entity = $provider->getEntityClassName();
        $provider = HlBlockManager::getInstance()->getProvider('GuestStorageColleague');
        $entityColleague = $provider->getEntityClassName();

        $res = $entity::getList(['filter' => ['UF_USER_ID' => $userIDs]]);
        $results = [];

        $ltmFormResult = new LtmFormResult();
        $mapping = $ltmFormResult->getMapping();
        $questions = $ltmFormResult->getQuestionArrays();
        while ($record = $res->Fetch()) {
            foreach ($this->countryList as $country => $v) {
                if ($v['ID'] == $record['UF_COUNTRY']) {
                    $record['UF_COUNTRY'] = $v['UF_VALUE'];
                    break;
                }
            }
            foreach ($this->salutation as $salutation => $v) {
                if ($v['ID'] == $record['UF_SALUTATION']) {
                    $record['UF_SALUTATION'] = $v['UF_VALUE'];
                }
            }
            $t = [];
            foreach ($this->northamerica as $area => $v) {
                if (in_array($v['ID'], $record['UF_NORTH_AMERICA'])) {
                    $t[] = $v['UF_VALUE'];
                }
            }
            $record['UF_NORTH_AMERICA'] = $t;

            $t = [];
            foreach ($this->europe as $area => $v) {
                if (in_array($v['ID'], $record['UF_EUROPE'])) {
                    $t[] = $v['UF_VALUE'];
                }
            }
            $record['UF_EUROPE'] = $t;

            $t = [];
            foreach ($this->southamerica as $area => $v) {
                if (in_array($v['ID'], $record['UF_SOUTH_AMERICA'])) {
                    $t[] = $v['UF_VALUE'];
                }
            }
            $record['UF_SOUTH_AMERICA'] = $t;

            $t = [];
            foreach ($this->africa as $area => $v) {
                if (in_array($v['ID'], $record['UF_AFRICA'])) {
                    $t[] = $v['UF_VALUE'];
                }
            }
            $record['UF_AFRICA'] = $t;

            $t = [];
            foreach ($this->asia as $area => $v) {
                if (in_array($v['ID'], $record['UF_ASIA'])) {
                    $t[] = $v['UF_VALUE'];
                }
            }
            $record['UF_ASIA'] = $t;

            $t = [];
            foreach ($this->oceania as $area => $v) {
                if (in_array($v['ID'], $record['UF_OCEANIA'])) {
                    $t[] = $v['UF_VALUE'];
                }
            }
            $record['UF_OCEANIA'] = $t;

            $t = [];
            foreach ($this->areas as $area => $v) {
                if (in_array($v['ID'], $record['UF_PRIORITY_AREAS'])) {
                    $t[] = $v['UF_VALUE'];
                }
            }
            $record['UF_PRIORITY_AREAS'] = $t;

            $item = $questions;
            foreach ($record as $k => $v) {
                if (isset($mapping[$k])) {
                    $item[$mapping[$k]]['VALUE'] = $v;
                }
            }

            $e = 0;
            foreach ($record['UF_COLLEAGUES'] as $k => $colId) {
                $colleague = $entityColleague::getById($colId)->Fetch();
                if(in_array(LtmFormResult::MORNING_VAL, $colleague['UF_DAYTIME'])) {
                    $colleagueItem = [
                        '650' => ['VALUE' => $colleague['UF_NAME']],
                        '651' => ['VALUE' => $colleague['UF_SURNAME']],
                        '652' => ['VALUE' => $colleague['UF_JOB_TITLE']],
                        '653' => ['VALUE' => $colleague['UF_EMAIL']],
                        '672' => ['VALUE' => $colleague['UF_SALUTATION']],
                        '657' => ['VALUE' => $colleague['UF_PHOTO']],
                    ];

                    foreach($colleagueItem as $k1=>$v1)
                    {
                        $item[$k1] = array_merge($item[$k1], $v1);
                    }
                }
                if(in_array(LtmFormResult::EVENING_VAL, $colleague['UF_DAYTIME'])) {
                    $colleagueItem = [];
                    if ($e == 0) {
                        $colleagueItem = [
                            '628' => ['VALUE' => $colleague['UF_NAME']],
                            '629' => ['VALUE' => $colleague['UF_SURNAME']],
                            '630' => ['VALUE' => $colleague['UF_JOB_TITLE']],
                            '631' => ['VALUE' => $colleague['UF_EMAIL']],
                            '668' => ['VALUE' => $colleague['UF_SALUTATION']],
                        ];
                        $e++;
                    } elseif ($e == 1) {
                        $colleagueItem = [
                            '632' => ['VALUE' => $colleague['UF_NAME']],
                            '633' => ['VALUE' => $colleague['UF_SURNAME']],
                            '635' => ['VALUE' => $colleague['UF_JOB_TITLE']],
                            '634' => ['VALUE' => $colleague['UF_EMAIL']],
                            '669' => ['VALUE' => $colleague['UF_SALUTATION']],
                        ];
                        $e++;
                    } elseif ($e == 2) {
                        $colleagueItem = [
                            '636' => ['VALUE' => $colleague['UF_NAME']],
                            '637' => ['VALUE' => $colleague['UF_SURNAME']],
                            '638' => ['VALUE' => $colleague['UF_JOB_TITLE']],
                            '639' => ['VALUE' => $colleague['UF_EMAIL']],
                            '670' => ['VALUE' => $colleague['UF_SALUTATION']],
                        ];
                        $e++;
                    }
                    foreach($colleagueItem as $k1=>$v1)
                    {
                        $item[$k1] = array_merge($item[$k1], $v1);
                    }
                }
            }

            $results[$record['UF_USER_ID']] = $item;
        }
        return $results;
    }

    public function deleteResult($userId)
    {
        $provider = HlBlockManager::getInstance()->getProvider('GuestStorageColleague');
        $entityColleague = $provider->getEntityClassName();
        $provider = HlBlockManager::getInstance()->getProvider('GuestStorage');
        $entity = $provider->getEntityClassName();
        $res = $entity::getList(['filter' => ['=UF_USER_ID' => $userId]]);
        while ($record = $res->Fetch()) {
            foreach ($record['UF_COLLEAGUES'] as $k => $colId) {
                $entityColleague::delete($colId);
            }
            $entity::delete($record['ID']);
        }
    }

    public function init()
    {
        $cacheTime = 3600;
        $cacheId = "ltm.gueststorage";
        $cacheDir = "ltmHL";

        $cache = Cache::createInstance();

        if ($cache->initCache($cacheTime, $cacheId, $cacheDir)) {
            $result = $cache->getVars();
        } elseif ($cache->startDataCache()) {

            // country
            $list = [];
            $provider = HlBlockManager::getInstance()->getProvider('GuestCountries');
            $entity = $provider->getEntityClassName();
            $res = $entity::getList();
            while ($country = $res->Fetch()) {
                $list[$country['UF_VALUE']] = $country;
            }
            $result['countries'] = $list;

            // africa
            $list = [];
            $provider = HlBlockManager::getInstance()->getProvider('PrioritiesAfrica');
            $entity = $provider->getEntityClassName();
            $res = $entity::getList();
            while ($country = $res->Fetch()) {
                $list[$country['UF_VALUE']] = $country;
            }
            $result['africa'] = $list;

            // asia
            $list = [];
            $provider = HlBlockManager::getInstance()->getProvider('PrioritiesAsia');
            $entity = $provider->getEntityClassName();
            $res = $entity::getList();
            while ($country = $res->Fetch()) {
                $list[$country['UF_VALUE']] = $country;
            }
            $result['asia'] = $list;

            // europe
            $list = [];
            $provider = HlBlockManager::getInstance()->getProvider('PrioritiesEurope');
            $entity = $provider->getEntityClassName();
            $res = $entity::getList();
            while ($country = $res->Fetch()) {
                $list[$country['UF_VALUE']] = $country;
            }
            $result['europe'] = $list;

            // na
            $list = [];
            $provider = HlBlockManager::getInstance()->getProvider('PrioritiesNorthAmerica');
            $entity = $provider->getEntityClassName();
            $res = $entity::getList();
            while ($country = $res->Fetch()) {
                $list[$country['UF_VALUE']] = $country;
            }
            $result['northamerica'] = $list;

            // oceania
            $list = [];
            $provider = HlBlockManager::getInstance()->getProvider('PrioritiesOceania');
            $entity = $provider->getEntityClassName();
            $res = $entity::getList();
            while ($country = $res->Fetch()) {
                $list[$country['UF_VALUE']] = $country;
            }
            $result['oceania'] = $list;

            // sa
            $list = [];
            $provider = HlBlockManager::getInstance()->getProvider('PrioritiesSouthAmerica');
            $entity = $provider->getEntityClassName();
            $res = $entity::getList();
            while ($country = $res->Fetch()) {
                $list[$country['UF_VALUE']] = $country;
            }
            $result['southamerica'] = $list;

            // pa
            $list = [];
            $provider = HlBlockManager::getInstance()->getProvider('PriorityAreas');
            $entity = $provider->getEntityClassName();
            $res = $entity::getList();
            while ($country = $res->Fetch()) {
                $list[$country['UF_VALUE']] = $country;
            }
            $result['areas'] = $list;

            // salutation
            $list = [];
            $provider = HlBlockManager::getInstance()->getProvider('Salutation');
            $entity = $provider->getEntityClassName();
            $res = $entity::getList();
            while ($country = $res->Fetch()) {
                $list[$country['UF_VALUE']] = $country;
            }
            $result['salutation'] = $list;
            $cache->endDataCache($result);
        }

        $this->countryList = $result['countries'];
        $this->africa = $result['africa'];
        $this->asia = $result['asia'];
        $this->europe = $result['europe'];
        $this->northamerica = $result['northamerica'];
        $this->oceania = $result['oceania'];
        $this->southamerica = $result['southamerica'];
        $this->areas = $result['areas'];
        $this->salutation = $result['salutation'];
    }

    public function getInitValues()
    {
        return [
            $this->countryList,
            $this->africa,
            $this->asia,
            $this->europe,
            $this->northamerica,
            $this->oceania,
            $this->southamerica,
            $this->areas,
            $this->salutation,
        ];
    }

    public function getCountryList()
    {
        return $this->countryList;
    }

    public function getAfricaList()
    {
        return $this->africa;
    }

    public function getAsiaList()
    {
        return $this->asia;
    }

    public function getEuropeList()
    {
        return $this->europe;
    }

    public function getNAmericaList()
    {
        return $this->northamerica;
    }

    public function getOceaniaList()
    {
        return $this->oceania;
    }

    public function getSAmericaList()
    {
        return $this->southamerica;
    }

    public function getAreasList()
    {
        return $this->areas;
    }

    public function getSalutationList()
    {
        return $this->salutation;
    }
}
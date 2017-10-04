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

    public function addFormResult($resultId, $resultData = null, $user = null)
    {
        $ltmFormResult = new LtmFormResult();
        if(empty($resultId)) return null;
        if(empty($resultData)) $resultData = $ltmFormResult->getResultData($resultId);
        if(empty($user)) $user = $ltmFormResult->getUserByResultId($resultId);
        if($resultData !== false && $user !== false)
        {
            $resultData['fields']['UF_NORTH_AMERICA'] = $this->northamerica[ $resultData['fields']['UF_NORTH_AMERICA'] ]['ID'];
            $resultData['fields']['UF_EUROPE'] = $this->europe[ $resultData['fields']['UF_EUROPE'] ]['ID'];
            $resultData['fields']['UF_SOUTH_AMERICA'] = $this->southamerica[ $resultData['fields']['UF_SOUTH_AMERICA'] ]['ID'];
            $resultData['fields']['UF_AFRICA'] = $this->africa[ $resultData['fields']['UF_AFRICA'] ]['ID'];
            $resultData['fields']['UF_ASIA'] = $this->asia[ $resultData['fields']['UF_ASIA'] ]['ID'];
            $resultData['fields']['UF_OCEANIA'] = $this->oceania[ $resultData['fields']['UF_OCEANIA'] ]['ID'];
            $resultData['fields']['UF_COUNTRY'] = $this->countryList[ $resultData['fields']['UF_COUNTRY'] ]['ID'];
            $resultData['fields']['UF_SALUTATION'] = $this->salutation[ $resultData['fields']['UF_SALUTATION'] ]['ID'];
            $resultData['fields']['UF_USER_ID'] = $user['ID'];

            $conn = \Bitrix\Main\Application::getConnection();
            $conn->startTransaction();

            if (count($resultData['colleagues']) > 0) {
                $provider = HlBlockManager::getInstance()->getProvider('GuestStorageColleague');
                $entityColleague = $provider->getEntityClassName();
                $colIDs = [];
                foreach ($resultData['colleagues'] as $colleague) {
                    $f = false;
                    if (isset($colleague['MORNING'])) {
                        $f = true;
                        unset($colleague['MORNING']);
                    }
                    $colleague['UF_USER_ID'] = $user['ID'];
                    $res = $entityColleague::add($colleague);
                    if ($res->isSuccess()) {
                        $colId = $res->getId();
                        $colIDs[] = $colId;

                        if ($f) {
                            $resultData['fields']['UF_MORNING_COLLEAGUE'] = $colId;
                        }
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
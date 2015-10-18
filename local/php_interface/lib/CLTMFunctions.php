<?
class CLTMFunctions
{
	/**
	 * Очищает в перечисленных группах, поля пользователей заданные вторым параметром
	 * @param mixed $groups - ID группы пользователей, массив или единичное значение
	 * @param mixed $properties - Список свойств/полей пользователя, массив или единичное значение
	 * @return bool|array
	 */
	public  static function ClearGroupProperties($groups, $properties)
	{
		if(!$groups || !$properties)
		{
			return false;
		}

		if(!is_array($groups))
		{
			$groups = array($groups);
		}

		$obUser = new CUser();
		$arResult = array();

		if(!is_array($properties))
		{
			$properties = array($properties);
		}

		if(!empty($groups) && !empty($properties))
		{
			foreach($groups as $groupId)
			{
				$arClearFields = array_fill_keys($properties, "");

				$arUsersList = CGroup::GetGroupUser($groupId);

				foreach($arUsersList as $userId)
				{
					if($obUser->Update($userId, $arClearFields))
					{
						$arResult["UPDATED"][$groupId][] = $userId;
					}
					else
					{
						$arResult["NOT_UPDATED"][$groupId][] = $userId;
					}
				}
			}
		}

		return $arResult;
	}
}
?>
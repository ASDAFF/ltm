<?php

namespace Ltm\Domain\Util;

class SplitArray {

    private $arItems = array();
    private $iBlockCount = 1;

    public function init($arItems, $iBlockCount) {
        $this->arItems = $arItems;
        $this->iBlockCount = $iBlockCount;
        $this->arSplitedSections = array();
    }

    private function doSplit($arItems, $iBlockCount) {
        // $iBlockCount - Число блоков, на которые надо разбать элементы $arItems
        // Обработка особых случаев
        // Если число элементов меньше либо равно числу свободных блоков.
        if (count($arItems) <= $iBlockCount) {
            $arSplit = array();
            foreach ($arItems as $k => $v) {
                $arSplit[] = array('SUM' => $v, 'ITEMS' => array($v));
            }
            return array($arSplit);
        }

        // Считаем сумму в переданных элементах
        $TotalItemsCount = 0;
        foreach ($arItems as $k => $v) {
            $TotalItemsCount += $v;
        }
        // Желательное число в блоке
        $items_per_block = round($TotalItemsCount / $iBlockCount);

        if ($iBlockCount > 1) {
            $iSubSumm = 0;
            $j = 0;
            $arSubItems = array();
            while ($iSubSumm + $arItems[$j] <= $items_per_block) {
                $arSubItems[] = $arItems[$j];
                $iSubSumm += $arItems[$j];
                $j++;
            }
            if ($iSubSumm == 0) {
                // Первый элемент больше чем средняя сумма по столбцу
                $arNextItems = array();
                for ($i = 1; $i < count($arItems); $i++) {
                    $arNextItems[] = $arItems[$i];
                }
                $arNextSplit = $this->doSplit($arNextItems, $iBlockCount - 1);
                $arThisSplit = array('SUM' => $arItems[0], 'ITEMS' => array($arItems[0]));
                $arThisSplits = array();
                foreach ($arNextSplit as $v1) {
                    $v1[-1]['SUM'] += $arThisSplit['SUM'];
                    $v1[-1]['ITEMS'] = $arThisSplit['ITEMS'];
                    // Сдвигаем массив на 0;
                    ksort($v1);
                    $v1 = array_values($v1);
                    $arThisSplits[] = $v1;
                }
                return $arThisSplits;
            } else {
                // два варианта разбиения
                $arThisSplits = array();
                // первый
                $arNextItems = array();
                for ($i = $j; $i < count($arItems); $i++) {
                    $arNextItems[] = $arItems[$i];
                }
                $arNextSplit = $this->doSplit($arNextItems, $iBlockCount - 1);
                $arThisSplit = array('SUM' => $iSubSumm, 'ITEMS' => $arSubItems);
                foreach ($arNextSplit as $v1) {
                    $v1[-1] = $arThisSplit;
                    // Сдвигаем массив на 0;
                    ksort($v1);
                    $v1 = array_values($v1);
                    $arThisSplits[] = $v1;
                }

                // Следующая итерация (второй вариант разбиения)
                $iSubSumm += $arItems[$j];
                $arSubItems[] = $arItems[$j];
                $j++;
                $arNextItems = array();
                for ($i = $j; $i < count($arItems); $i++) {
                    $arNextItems[] = $arItems[$i];
                }
                // Особый случай - не надо добавлять это разбиение
                if (count($arNextItems) == 0) {
                    return $arThisSplits;
                }

                $arNextSplit = $this->doSplit($arNextItems, $iBlockCount - 1);
                $arThisSplit = array('SUM' => $iSubSumm, 'ITEMS' => $arSubItems);
                foreach ($arNextSplit as $v1) {
                    $v1[-1] = $arThisSplit;
                    // Сдвигаем массив на 0;
                    ksort($v1);
                    $v1 = array_values($v1);
                    $arThisSplits[] = $v1;
                }
                return $arThisSplits;
            }
        } else {
            return array(
                array(
                    array('SUM' => $TotalItemsCount, 'ITEMS' => $arItems)
                )
            );
        }
    }

    public function split() {
        // Получим все варианты разбиения
        $arResult = $this->doSplit($this->arItems, $this->iBlockCount);
        // Найдем лучшее
        $iMidDelta = 99999999;
        $iBestResult = 0;
        foreach ($arResult as $k => $v) {
            $sMax = 0;
            $sMin = $v[0]['SUM'];
            for ($i = 0; $i <= $this->iBlockCount; $i++) {
                if (array_key_exists($i, $v)) {
                    $s = $v[$i]['SUM'];
                    $sMax = ($sMax > $s) ? $sMax : $s;
                    $sMin = ($sMin < $s) ? $sMin : $s;
                }
            }
            $delta = $sMax - $sMin;
            if ($delta <= $iMidDelta) {
                $iMidDelta = $delta;
                $iBestResult = $k;
            }
        }

        return $arResult[$iBestResult];
    }
}

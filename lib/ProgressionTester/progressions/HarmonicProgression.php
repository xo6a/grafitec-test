<?php

namespace ProgressionTester\progressions;

use ProgressionTester\components\AbstractProgression;

class HarmonicProgression extends AbstractProgression
{
    /** @inheritdoc */
    protected $name = 'Harmonic progression';

    /**
     * @inheritdoc
     */
    protected function getDelta($input, $i)
    {
        if (!$this->isSetRange($input, $i, $i + 1))
            return null;
        if ($input[$i] - $input[$i + 1] != -1)
            throw new \Exception('delta is not valid');
        return $input[$i] - $input[$i + 1];
    }

    /**
     * @inheritdoc
     */
    protected function prepareInputItem($item)
    {
        $item = $this->getFractionDenominator($item);
        return parent::prepareInputItem($item);
    }

    /**
     * Получить знаменатель
     * @param $fraction string
     * @return int|null
     * @throws \Exception
     */
    protected function getFractionDenominator($fraction)
    {
        $fractionAr = explode('/', $fraction);
        if (!is_numeric($fractionAr[0]) || $fractionAr[0] != 1 || !isset($fractionAr[1]) || !is_numeric($fractionAr[1]))
            throw new \Exception("$fraction is not valid element");
        return $fractionAr[1];
    }
}
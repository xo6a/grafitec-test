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
        return $input[$i] - $input[$i + 1];
    }

    /**
     * @inheritdoc
     */
    protected function prepareInput($input)
    {
        $newInput = [];
        foreach ($input as $item) {
            if ($this->getFractionDenominator($item) === null)
                throw new \Exception($item . ' is not fraction');
            $newInput[] = $this->getFractionDenominator($item);
        }
        return $newInput;
    }

    /**
     * Получить знаменатель
     * @param $fraction string
     * @return null|int
     */
    protected function getFractionDenominator($fraction)
    {
        $fraction = explode('/', $fraction);
        if (!isset($fraction[1]))
            return null;
        return $fraction[1];
    }
}
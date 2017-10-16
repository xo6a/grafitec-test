<?php

namespace ProgressionTester\progressions;

use ProgressionTester\components\AbstractProgression;

class ArithmeticProgression extends AbstractProgression
{
    /** @inheritdoc */
    protected $name = 'Arithmetic progression';

    /**
     * @inheritdoc
     */
    protected function getDelta($input, $i)
    {
        if (!$this->isSetRange($input, $i, $i + 1))
            return null;
        return $input[$i] - $input[$i + 1];
    }
}
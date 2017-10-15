<?php

namespace ProgressionTester\progressions;

use ProgressionTester\components\AbstractProgression;

class ArithmeticProgression extends AbstractProgression
{
    protected $name = 'Арифметическая прогрессия';

    protected function getDelta($input, $i)
    {
        if (!isset($input[$i]) || !isset($input[$i + 1]))
            return null;
        return $input[$i] - $input[$i + 1];
    }
}
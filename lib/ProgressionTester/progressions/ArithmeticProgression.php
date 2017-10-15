<?php

namespace ProgressionTester\progressions;

use ProgressionTester\components\AbstractProgression;

class ArithmeticProgression extends AbstractProgression
{
    protected $name = 'Арифметическая прогрессия';

    protected function getDelta($input, $i)
    {
        return 0;
    }
}
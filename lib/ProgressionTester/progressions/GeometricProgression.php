<?php

namespace ProgressionTester\progressions;

use ProgressionTester\components\AbstractProgression;

class GeometricProgression extends AbstractProgression
{
    protected $name = 'Геометрическое прогрессия';

    protected function getDelta($input, $i)
    {
        if (!$this->isSetRange($input, $i, $i + 1))
            return null;
        return $input[$i] / $input[$i + 1];
    }
}
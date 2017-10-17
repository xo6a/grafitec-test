<?php

namespace ProgressionTester\progressions;

use ProgressionTester\components\AbstractProgression;

class GeometricProgression extends AbstractProgression
{
    /** @inheritdoc */
    protected $name = 'Geometric progression';

    /**
     * @inheritdoc
     */
    protected function getDelta($input, $i)
    {
        if (!$this->isSetRange($input, $i, $i + 1))
            return null;
        return $input[$i] / $input[$i + 1];
    }

    /**
     * @inheritdoc
     */
    protected function prepareInputItem($item)
    {
        if ($item == 0)
            throw new \Exception("'$item' is not valid progression element");
        return parent::prepareInputItem($item);
    }
}
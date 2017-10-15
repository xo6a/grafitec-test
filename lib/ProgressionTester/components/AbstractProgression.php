<?php

namespace ProgressionTester\components;

abstract class AbstractProgression
{

    protected $input;
    protected $delta;
    protected $name = 'Абстракная прогрессия';

    public function getName()
    {
        return $this->name;
    }

    public function validate($input)
    {
        $this->input = $this->prepareInput($input);
        $this->delta = $this->getStandardDelta($input);
        return $this->validateInput($input);
    }

    protected function prepareInput($input)
    {
        return $input;
    }

    protected function validateInput($input)
    {
        $validate = $this->isInputValid($input);
        if ($validate == null) {
            throw new \Exception('Progression is to short.');
        } else {
            return $validate;
        }
    }

    protected function isInputValid($input)
    {
        $valid = null;
        foreach ($input as $key => $item) {
            if ($key == 0)
                continue;
            if ($this->getDelta($input, $key) === null)
                return $valid;
            if ($this->getDelta($input, $key) == $this->delta)
                $valid = true;
            else
                return false;
        }
        return true;
    }

    protected function getStandardDelta($input)
    {
        $delta = $this->getDelta($input, 0);
        if ($delta === null)
            throw new \Exception('Can\'t get standart delta for progression.');
        return $delta;
    }

    protected abstract function getDelta($input, $i);
}
<?php

namespace ProgressionTester\components;

abstract class AbstractProgression
{
    /** @var mixed эталонная разница между символами */
    protected $delta;
    /** @var string информация об элементе на котором вышла ошибка */
    protected $failElement;
    /** @var string название прогрессии */
    protected $name = 'Абстракная прогрессия';

    /**
     * Получить имя прогрессии
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Получить информацию б элементе на котором вышла ошибка
     * @return string
     */
    public function getFailElement()
    {
        return $this->failElement;
    }

    /**
     * Проверка ряда на прогрессию
     * @param $input
     * @return bool|null
     */
    public function validate($input)
    {
        $this->setFailElement(0, $input[0]);
        $input = $this->prepareInput($input);
        $this->delta = $this->getStandardDelta($input);
        return $this->validateInput($input);
    }

    /**
     * Преобразование и проверка ряда перед запуском
     * @param $input
     * @return mixed
     * @throws \Exception
     */
    protected function prepareInput($input)
    {
        $newInput = [];
        foreach ($input as $item) {
            $newInput[] = $this->prepareInputItem($item);
        }
        return $newInput;
    }

    /**
     * Преобразование и проверка элемента ряда
     * @param $item
     * @return mixed
     * @throws \Exception
     */
    protected function prepareInputItem($item)
    {
        if (!is_numeric($item))
            throw new \Exception("'$item' is not valid progression element. It must be number.");
        if ($item[0] == '0' && strlen($item) > 1)
            throw new \Exception("'$item' is not valid progression element. It can't start from zero.");
        return $item;
    }

    /**
     * Проверка ряда на минимальную допустимую длину
     * @param $input
     * @return bool|null
     * @throws \Exception
     */
    protected function validateInput($input)
    {
        $validate = $this->isInputValid($input);
        if ($validate === null) {
            throw new \Exception('Progression is to short.');
        } else {
            return $validate;
        }
    }

    /**
     * Пповерка всех элементов ряда на совпадение дельты с эталонной
     * @param $input array список элементов ряда
     * @return bool|null если null - значит не удалось проверить ни одного элемента, true - ряд является прогрессией, false - ряд не является прогрессией
     */
    protected function isInputValid($input)
    {
        $valid = null;
        foreach ($input as $key => $item) {
            $this->setFailElement($key, $item);
            if ($key == 0)
                continue;
            $delta = $this->getDelta($input, $key);
            if ($delta === null)
                return $valid;
            if (bccomp($delta, $this->delta, 16) == 0)
                $valid = true;
            else
                return false;
        }
        return true;
    }

    /**
     * Запомнить информацию о последнем фейловом элементе
     * @param $key
     * @param $item
     */
    protected function setFailElement($key, $item)
    {
        $this->failElement = "#$key = $item";
    }

    /**
     * Получить эталонное дельта
     * @param $input
     * @return mixed
     * @throws \Exception
     */
    protected function getStandardDelta($input)
    {
        $delta = $this->getDelta($input, 0);
        if ($delta === null)
            throw new \Exception('Can\'t get standart delta for progression.');
        return $delta;
    }

    /**
     * Проверка ряда существуют ли элементы с min до max
     * @param $input array список элементов ряда
     * @param $min int номер первого элемента проверки
     * @param $max int номер последнего элемента проверки
     * @return bool true - все элемент от min до max сущестувуют, false - не все нужные элементы существуют
     */
    protected function isSetRange($input, $min, $max)
    {
        foreach (range($min, $max) as $i) {
            if (!isset($input[$i]))
                return false;
        }
        return true;
    }

    /**
     * Вычислить дельту для текущего элемента
     * @param $input array список элементов ряда
     * @param $i int номер элемента
     * @return mixed
     */
    protected abstract function getDelta($input, $i);
}
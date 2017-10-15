<?php

foreach ($data as $result) {
    if ($result['result']) {
        $this->log('Данная последовательность символов ' . $result['progFullName']);
    } else {
        $this->log('Данная последовательность символов НЕ ' . $result['progFullName']);
        $this->log('Ошибка на элементе последовательности ' . $result['failElement']);
    }
    $this->log('Имя php класса: ' . $result['progClassName']);
    if (isset($result['error'])) {
        $this->log('Ошибка: ' . $result['error']['message']);
    }
}
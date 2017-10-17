<?php

foreach ($data as $result) {
    if ($result['result']) {
        $this->log('[success] This sequence of characters ' . $result['progFullName']);
    } else {
        $this->log('[fail] This sequence of characters is NOT ' . $result['progFullName']);
        $this->log('       Php class name: ' . $result['progClassName']);
        $this->log('       Error on the element of the sequence ' . $result['failElement']);
    }
    if (isset($result['error'])) {
        $this->log('       Error: ' . $result['error']['message']);
    }
}
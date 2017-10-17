<?php

foreach ($data as $result) {
    if ($result['result']) {
        $this->log('[success] This sequence of characters ' . $result['progFullName']);
    } else {
        $this->log('[fail] This sequence of characters is NOT ' . $result['progFullName']);
    }
}
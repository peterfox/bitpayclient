<?php

require_once('vendor/autoload.php');
    
\VCR\VCR::turnOn();

if (!file_exists('tests/fixtures')) {
	mkdir('tests/fixtures');
}
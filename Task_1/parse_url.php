<?php

function fillParameters($url) {
    $doc = new \DOMDocument('1.0', 'UTF-8');
    $internalErrors = libxml_use_internal_errors(true);
    $doc->loadHTMLFile($url);
    libxml_use_internal_errors($internalErrors);
    $xpath = new DOMXpath($doc);
    
    $date = $xpath->query("//caption")[1];
    $cinemas = $xpath->query('//div[@class="header"]//h2');
    $programs = $xpath->query('//table');
    return array($date, $cinemas, $programs);
}

?>
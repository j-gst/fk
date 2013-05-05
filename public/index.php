<?php

// autoloader fuer Klassen
require_once "../classloader.php"; //einmalig einladen der classloader.php

$test = array('indexNr1' => "test", 66, 'hallo' => true, "xy");

set_time_limit(0); 
ini_set('upload_max_filesize', '512M');
ini_set('memory_limit', '512M');
ini_set('max_execution_time', 7200);
ini_set('session.gc_maxlifetime',7200);  

// Error Reporting abschalten
error_reporting(0);

// Melde alle PHP Fehler - fuer debuging
error_reporting(E_ALL);


// Startzeit Speichern
defined('START_TIME') ? null : define('START_TIME', microtime(true));


// Controller::getInstance() liefert eine Instanz des korrekten Controllers (i.d.R. per GET Parameter)
$c = \controllers\Controller::getInstance(); 
$c->run(); //ruft die Methode run() des Controller-Objektes auf


















?>
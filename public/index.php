<?php

// autoloader fuer Klassen
require_once "../classloader.php"; //einmalig einladen der classloader.php

$test = array('indexNr1' => "test", 66, 'hallo' => true, "xy");

// Start der Session
session_start();

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
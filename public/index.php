<?php

// autoloader fuer Klassen
require_once "../classloader.php"; //einmalig einladen der classloader.php



// Error Reporting abschalten
error_reporting(0);

// Melde alle PHP Fehler - fuer debuging
// error_reporting(E_ALL);


// Startzeit Speichern
defined('START_TIME') ? null : define('START_TIME', microtime(true));



/*
* 
*/
$c = \controllers\Controller::getInstance(); // erschafft eine Instanz von Controller.php
$c->run(); //ruft die Methode run() des Controller-Objektes auf






















?>
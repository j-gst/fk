<?php
require_once "../classloader.php"; //einmalig einladen der classloader.php

// Startzeit Speichern
defined('START_TIME') ? null : define('START_TIME', microtime(true));


//print_r($_SERVER);

$c = \controllers\Controller::getInstance(); // erschafft eine Instanz von Controller.php
$c->run(); //ruft die Methode run() des Controller-Objektes auf






















?>
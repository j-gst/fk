<?php
/**
 * INDEX Seite - alle Aufrufe kommen hier an
 * @author Gerrit Storm, Beate Gericke
 */


/*Konfiguration ********************************************
 * Pfad zum Applikationsverzeichnis
 * Das Applikationsverzeichnis und das Bildverzeichnis 
 * sollten ausserhalb des Server Root Verzeichnisses liegen
 */
$appPath = "../app/";
$imgPah = "../../images/";
/*
 /************************************************************/
 

// Startzeit Speichern
defined('START_TIME') ? null : define('START_TIME', microtime(true));


defined('APP_DIR') ? null : define('APP_DIR',$appPath);
defined('IMG_DIR') ? null : define('IMG_DIR',$imgPah );

//registrieren der Classloader Funktion (s.u.)
spl_autoload_register("classLoader");

// wird fuer DateTime benoetigt
date_default_timezone_set('Europe/Berlin');

// Start der Session
session_start();

// Error Reporting abschalten
error_reporting(0);

// Melde alle PHP Fehler - fuer debuging
// error_reporting(E_ALL);



// Controller::getInstance() liefert eine Instanz des korrekten Controllers (i.d.R. per GET Parameter)
$c = \controllers\Controller::getInstance();


$c->run(); //ruft die Methode run() des Controller-Objektes auf


/**
 * automatisches includen von php Klassen
 * bei Bedarf
 *@autor: Gerrit Storm
 */
// Class Loader wird registriert
function classLoader($class){
    
    // \ durch / ersetzen
    $class = preg_replace( "/\\\/","/",$class);
    
    $file = APP_DIR.$class.".php";

    if (is_readable($file)) {
        require_once $file;
    } else {
        die("File:  ".$file." not found!");
    }

}

?>
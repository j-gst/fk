<?php
/*
 * @autor: Gerrit Storm, Maike Schröder, Thies Schillhorn
 * automatisches includen von php Klassen
 * bei Bedarf
 */


// Class Loader wird registriert
spl_autoload_register(function($class)
{
    $ds = DIRECTORY_SEPARATOR;
    $dir = __DIR__;

    $file = "{$class}.php";

    // Thies Schillhorn, 20130522 : Anpassungen der Pfade je nach OS-System
    $pathPre = ".";
    $osInfo = php_uname('s');
    if (strtoupper(substr($osInfo, 0, 3)) === 'WIN') {
        $pathPre = "";
    } else {
        $pathPre = "..";
    }

    if (is_readable($file)) {
        require_once $file;
    } else {
        $file = preg_replace( "/\\\/","/",$file);
        require_once $pathPre . "/" . $file;
    }

});
?>
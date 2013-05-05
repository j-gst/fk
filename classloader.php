<?php
/*
 * automatisches includen von php Klassen
 * bei Bedarf
 */



// Class Loader wird registriert
spl_autoload_register(function($class)
{

  
        $ds = DIRECTORY_SEPARATOR;
        $dir = __DIR__;

    // 
        $file = "{$dir}{$ds}{$class}.php";

    // 
        if (is_readable($file))
            require_once $file;


});
?>






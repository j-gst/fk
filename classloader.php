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
        $file = "{$class}.php";

    // 
//echo $file;
        if (is_readable($file)){
            require_once $file;
        }else{
                   $file = preg_replace( "/\\\/","/",$file);
            require_once "/".$file;
        }

});
?>





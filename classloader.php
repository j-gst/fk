<?php
/*
 * wozu dient die Datei?
 */
defined('CLASS_PATH') ? null : define('CLASS_PATH', "..");


// Class Loader
spl_autoload_register(function($class)
{

    # Usually I would just concatenate directly to $file variable below
    # this is just for easy viewing on Stack Overflow)
        $ds = DIRECTORY_SEPARATOR;
        $dir = __DIR__;


    // replace namespace separator with directory separator (prolly not required)
        $class = strtr($class, '\\', $ds);

    // get full name of file containing the required class
        $file = "{$dir}{$ds}{$class}.php";

    // get file if it is readable
        if (is_readable($file))
            require_once $file;


});
?>






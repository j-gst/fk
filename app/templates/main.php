<?php 
/**
 * Main Template
 * Wird bei jedem Seitenaufruf eingebunden
 * bindet dynamisch das content Template ein
 * @author  Beate Gericke, Gerrit Storm
 */
?>
<?php
/*
* Um Code-Doppelungen zu vermeiden werden hier die immer gleich bleibenden Teile der Seite (head und foot)
* mit dem sich ändernden Teil (content) zusammengefügt
*/
include "head.php";
include "content_".$contentTpl.".php";
include "foot.php";
?>
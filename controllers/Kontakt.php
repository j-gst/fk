<?php
namespace controllers;
/*
 * @auto Thies Schillhorn
* Klasse Kontakt dient zur Darstellung des Kontaktformulars und ist abgeleitet von der Controllerklasse
*/
class Kontakt extends Controller
{
    /**
     * Die run-Methode einer vom Controller abgeleiteten Klasse wird nach der Initialisierung im classloader aufgerufen.
     */
    public function run()
    {
        $this->displayData['kontakt'] = $this->conf->mymail;
        $this->display("kontakt");
    }
} ?>
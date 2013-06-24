<?php
namespace controllers;
/**
 *
 * Klasse Kontakt dient zur Darstellung des Kontaktformulars und ist abgeleitet von der Controllerklasse
 * @author Thies Schillhorn
 *
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
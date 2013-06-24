<?php
namespace controllers;
/**
 * Klasse Impressum dient zur Darstellung der Impressumstexte und ist abgeleitet von der Controllerklasse
 * @author Thies Schillhorn
 */
class Impressum extends Controller
{
    /**
     * Die run-Methode einer vom Controller abgeleiteten Klasse wird nach der Initialisierung im classloader aufgerufen.
     */
    public function run(){
        $this->displayData['impressum'] = $this->conf->impressum;
        $this->display("impressum");
    }
} ?>
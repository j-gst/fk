<?php
namespace controllers;
/*
 * @auto Thies Schillhorn
 * Klasse Impressum dient zur Darstellung der Impressumstexte und ist abgeleitet von der Controllerklasse
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
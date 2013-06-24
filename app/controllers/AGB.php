<?php
/**
 * Controller AGB
 * Dieser Controller ruft nur das passende Template auf
 * @author: Maike Schroeder
 */
namespace controllers;
class AGB extends Controller
{
    /**
     * die Methode run() wird implementiert
     * Einstiegspunkt in den Controller
     */
   public function run(){
	   $this->display("agb"); 
   }
}// class
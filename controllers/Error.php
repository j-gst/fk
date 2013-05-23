<?php
namespace controllers;
/*
 * Klasse Error, erbt von der Klasse Controller
 */
class Error extends Controller
{
  

   /* ???
    * die Funktion run wird implementiert
    * dem aktuellen Objekt wird ein assoziatives Array zugeordnet in dem Die Error-Nachrichten gespeichert werden
    * die display-Methode wird auf dem Objekt mit dem Error-Template aufgerufen
    */
   public function run(){
        $this->displayData['errorMsg'] = $this->errorMsg;
		$this->display("error");
   }
  

} ?>
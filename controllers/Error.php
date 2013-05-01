<?php
namespace controllers;
/*
 * Klasse Error, erbt von der Klasse Controller
 */
class Error extends Controller
{
  

   /*
    * die Funktion run wird implementiert
    */
   public function run(){
        $this->displayData['errorMsg'] = $this->errorMsg;
		$this->display("error");
   }
  

} ?>
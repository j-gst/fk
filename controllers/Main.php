<?php namespace controllers;
// Klasse Main, erbt von Controller
class Main extends Controller
{
  


  
  


   /*
    * die Methode run() wird implementiert
    * das erzeugte Objekt lädt ein array mit Bildern durch die Methode getImagesFromDB()
    * das erzeugte Ojekt lädt ein array mit Seitenzahlen über die Funktion getPagination()
    * die Methode display wird mit dem Parameter main auf dem Objekt aufgerufen
   */
   public function run(){

    $this->displayData['images'] = $this->getImagesFromDB();
	$this->displayData['pagination'] = $this->getPagination();
    $this->display("main");
   }
/**
 * Methode getImagesFromDB
 * 
 *
  */
  private function getImagesFromDB(){
      $offset = 0;
	  /*
	   * Wenn die Variable offset, die mit 0 initialisiert wurde exisitiert und größer 0 ist,
	   * wird  
	   */
	  if(isset($_REQUEST['offset']) && (int)$_REQUEST['offset'] > 0){
		$offset = ((int)$_REQUEST['offset'] - 1) * $this->conf->showImgNum; // ?? ja was genau wird dann ?? eine Abfrage mit diesem offset sird zum int geparst und um 1 verringert und dann  multipliziert mit ??
	  }
	  
	  /*
	   * Datenbankabfrage wird als formatierter String in Variable gespeichert. 
	   * ?? den rest versteh ich nur grob, aber nicht was genau da abgeht
	   */
	  $q = sprintf('SELECT FK_Picture.Id, Name, CreaDateTime, Description, UserName 
			FROM FK_Picture LEFT JOIN FK_User ON FK_User.Id = FK_Picture.UserId
			ORDER BY CreaDateTime DESC LIMIT %d, %d', $offset, $this->conf->showImgNum);
	  $images = $this->db->query_array($q); //??
	  $displayImages = array();
	  foreach($images as $key => $img){ // ??
		$displayImages[$key] = new \classes\imageArea();
		$displayImages[$key]->titel = $img['Name'];
		$date = new \DateTime($img['CreaDateTime']);
		$displayImages[$key]->date = $date->format('d.m.Y H:i:s');
		$displayImages[$key]->user = $img['UserName'];
		$displayImages[$key]->desc = nl2br($img['Description']);
		$displayImages[$key]->thumbnail = "../images/tn_image".$img['Id'].".jpg";
		$displayImages[$key]->imgLink = "../images/image".$img['Id'].".jpg";
	  }
	  return $displayImages;
  }//getImagesFromDB()
 
 /*
  * Es wird geprüft, wieviele Bilder in der Datenbank liegen
  * Je nachdem wird errechnet, wieviele Seiten benötigt werden
  */
  private function getPagination(){
  
  	  $q = "SELECT COUNT(Id) AS COUNT FROM FK_Picture"; // Datenbankabfrage
	  $count = $this->db->query_array($q); // ?? Die Datenbankabfrage wird auf die aktuelle Instanz der DB angewendet und in ein Array gespeichert ??
	  return ceil((float)$count[0]['COUNT'] / $this->conf->showImgNum); // die Anzahl der anzuzeigenden Bilder wird durch die Einstellung, wieviele bilder pro Seite angezeigt werden sollen geteilt und das Ergebnis aufgerundet
	  
  
  
  }//getPagination()

} ?>
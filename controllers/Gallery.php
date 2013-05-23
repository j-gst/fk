<?php
namespace controllers;
/**
* Gallery Controller
* bearbeiten der Datei uploads (Bild- und ZIP-Dateien)
* @Author: Beate Gericke (Nebenautor), Gerrit Storm (Hauptautor)
*/
class Gallery extends Controller
{
   /**
   * implementierung der Funktion run
   */
   public function run(){
		$this->redirectOnInsufficientRights(array('view')); // auf dem Objekt wird die Funktion redirectOnInsufficientRights aufgerufen, dem das array view uebergeben wird
 
		/*
		 * wenn download vorhanden ist und dort eine id vorhanden ist und diese id numerisch ist,
		 * wird auf dem Objekt die Funktion downloadGallery aufgerufen, der die in der Anfrage uebergebene id mitgegeben wird
		 */
	   if(isset($_REQUEST['download']) 
			&& isset($_REQUEST['id']) 
			&& is_numeric($_REQUEST['id'])){
			

			
			
			$this->downloadGallery($_REQUEST['id']);
		}
		
		/*
		 * dem Index error des arrays displayData wird der Wert false zugeordnet
		 * wenn die id vorhanden ist und ein numerischer Wert, wird die Funktion showGallery mit dieser id aufgerufen
		 * ansonsten wird der Wert im array displayData beim Index error auf true gesetzt
		 */
       $this->displayData['error'] = false;
	   if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])){
			$this->showGallery($_REQUEST['id']);
	   
	   }else{
	      $this->displayData['error'] = true;
	   
	   }
	   
   	// Auswahl des korrekten Templates - Gallery
	$this->display("gallery"); 
   }// run()


/*
 * Funktion zum Anzeigen des Gallery-Objekts mit der gewaehlten id
 */
private function showGallery($id){


    //ruft auf dem Objekt die Funktion getImages mit der uebergebenen id auf und speichert das Ergebnis in die Variable images
    $images = $this->getImages($id);

    /*
     * ordnet beim Index archiv ein array zu
     * dort wird ein neues Objekt der Klasse imageArea erstellt               ????
     * die Variablen des imageArea-Objekts werden mit einem leeren String instanziert
     */
	$this->displayData['archive']  = array();
	$this->displayData['archive'] = new \classes\imageArea();
	
	$this->displayData['archive']->titel = "";
	$this->displayData['archive']->date = "";
	$this->displayData['archive']->user = "";
	$this->displayData['archive']->desc = "";
	  
	  /*
	   * wenn images ein array ist und dort mehr als ein Element enthalten ist
	   *                                                                               ??
	   */
	 if(is_array($images) &&  count($images) > 1){
	

	$this->displayData['id'] = $id;
	$this->displayData['archive']->titel = htmlentities($images[0]['Name']);
	$date = new \DateTime($images[0]['CreaDateTime']);
	$this->displayData['archive']->date = $date->format('d.m.Y H:i:s');
	$this->displayData['archive']->user = $images[0]['UserName'] ? $images[0]['UserName'] : 'Gast';
	$this->displayData['archive']->desc = nl2br(htmlentities($images[0]['Description']));
		
	
	/*
	 * für jedes Element im array images wird ein neues Objekt der Klasse imageArea erstellt 
	 * und die Werte des entsprechenden Objekts an den zugehoerigen Stellen gespeichert
	 */
	 foreach($images as $key => $img){ 
		$this->displayData['images'][$key] = new \classes\imageArea();
		$this->displayData['images'][$key]->id = $img['Id'];
		$this->displayData['images'][$key]->titel = htmlentities($img['Name']);
		$this->displayData['images'][$key]->thumbnail = $this->conf->imgDir."tn_image".$img['Id'].".jpg";
		$this->displayData['images'][$key]->imgLink = $this->conf->imgDir."image".$img['Id'].".jpg";
	}//foreach

} //if(!is_arry($images) &&  count($images) < 1)
else{                                                             // sonst wird error auf true gesetzt
	$this->displayData['error'] = true;
}









}//showGallery()


/**
* Bildinformationen aus DB laden
*/
private function getImages($id){

	  // query String
	  $q = sprintf('SELECT FK_Picture.Id, Name, CreaDateTime, Description, UserName, ArchiveId 
			FROM FK_Picture LEFT JOIN FK_User ON FK_User.Id = FK_Picture.UserId
			WHERE ArchiveId = %d',  $id);
	  
	  // alle Bildinformationen aus der DB laden
	  return  $this->db->query_array($q);

}

/**
* Download Gallery als ZIP
*/
private function downloadGallery($id){
	$images = $this->getImages($id);
	// wenn images kein array ist oder weniger als ein Element enthalten ist, wird false zurueckgegeben
	if (! is_array($images) || count($images) < 1){
		return false;
	}
	set_time_limit(0);

	//die noetigen Variablen werden instantiert und mit Werten belegt
	$zip = new \ZipArchive();
	$downloadName = "Gallery_".$id.".zip";
	$zipFileName = sys_get_temp_dir().$downloadName;

	if ($zip->open($zipFileName, \ZIPARCHIVE::CREATE)!==TRUE) {
	    return false;
	}
	
	// fuer jedes Element im images-Array wird dem zip-Archiv eine Datei zugefuegt
	foreach($images as $img){
		$zip->addFile($this->conf->imgDir."image".$img['Id'].".jpg");
	}//foreach
	
	$zip->close(); //der Datenstrom wird geschlossen                                                    ???
	
	//                                                                                                  ???
	header('Content-type: application/octetstream');
	header('Content-Disposition: attachment; filename="' . $downloadName . '"');
	readfile($zipFileName);	
	
	
	
	
	
	
}


}// class
<?php
namespace controllers;

/*
 * Klasse Upload, erbt von der Klasse Controller
 */

class Upload extends Controller
{

 private $uploaderror = "";
  
   /*
    * implementierung der Funktion run
    * wenn die Aktion speichern/upload ausgelöst wird, wird die Funktion saveImageToDB auf dem Objekt aufgerufen
    * wenn, die daten in die Datenbank geschrieben wurden, wird wieder auf die Startseite geleitet 
    */
   public function run(){
   $this->displayData['uploaderror'] = "";
   if (isset($_REQUEST['action'])){
	   switch($_REQUEST['action']){
			case 'save':
				$rVal = $this->saveImageToDB();
				if($rVal === true){
					header( 'Location: index.php' ) ;
				}//if
				$this->displayData['uploaderror'] = $this->uploaderror;
				break;
	   
	   }//switch
   }// if
   
   
   
   

    /*
	 * die Funktion display wird mit dem Parameter "upload" aufgerufen
	 * dadurch wird die content_upload in der main in die Variable eingesetzt
	 */
	$this->display("upload");  
   }//run
   
 
  

  
  
 
  /*
   * Funktion saveImageToDB
   * hier werden die eingegeben Daten in die Datenbank geschrieben
   */
  private function saveImageToDB(){
  
    // gueltige Dateiformate fuer den Upload
    $validFiles = array(
	   'image/jpeg',
	);
  
	//var_dump($_FILES);
	//var_dump($_REQUEST);
	
	/**
	 * 
	 *
	 * Die eingegebenen Daten werden ausgelesen
	 * 
	 */
	if(isset($_FILES) && $_FILES['Durchsuchen']['error'] === 0){	// ?? $_Files also wenn Daten ausgewählt wurden und es keinen Error gab??
		$insertArgs = array(
			'LocationFS' => $_FILES['Durchsuchen']['name'],
			'Name' => $_REQUEST['Bildtitel'],
			'UserId' => $this->user->getId(),
			'Format' => $_FILES['Durchsuchen']['type'],
			'CreaDateTime' => date("Y-m-d H:i:s"),
			'Description' => $_REQUEST['Bildbeschreibung'],
			'PictureState' => '1',
		);
	
		if( !in_array($_FILES['Durchsuchen']['type'], $validFiles)){
		   $this->uploaderror = "Falsches Dateiformat!";
			return false;
		}
	
		$insertId = $this->db->insert('FK_Picture',$insertArgs,'ssdsssd' ); // ??
		if($insertId !== false){
			$filename = "image".$insertId.".jpg" ; // der Bildname wird erzeugt
			$move = move_uploaded_file($_FILES['Durchsuchen']['tmp_name'],$this->conf->imgDir.$filename);
			$this->makeThumbnail($filename); // die Funktion makeThumbnail wird aufgerufen
		}else{
		
		}
		return true;
  } else {
      $this->uploaderror = "Fehler beim upload ( Fehlercode: ".$_FILES['Durchsuchen']['error']." )!";
  }
  
  
  
  
  
  }// saveImageToDB()
  
  /* ??warum immer die var_dumps??
   * Funktion makeThumbnail
   * hier weden von den hochladenden Bildern Bilder in kleinerem Format/Thumbnails erstellt
   */
  private function makeThumbnail($filename){
  	$image = imagecreatefromjpeg($this->conf->imgDir.$filename); //von dem Bild wird ein Bild gemacht
	$w = imagesx($image); //Breite und Höhe des Bildes werden abgefragt
	$h = imagesy($image);
	$new_w = 250; // eine neue Breite wird festgelegt
	$new_h = floor( $h * ($new_w / $w) ); // anhand der bekannten Maße und der festgelegten neuen Breite wird die neue Höhe berechnet
	$tmpImg = imagecreatetruecolor($new_w, $new_h); // es wird ein neues Bild mit neuer Höhe und Breite erstellt, dass noch leer ist
	$dst_image = $this->conf->imgDir."tn_".$filename;
	$r = imagecopyresized($tmpImg, $image, 0, 0, 0, 0, $new_w, $new_h, $w, $h); // das Bild wird mit der neuen Größe erstellt
	$r = imagejpeg($tmpImg, $dst_image); // Das Bild wird als jpeg gespeichert
  	
	
  }//makeThumbnail()
  
  



} ?>
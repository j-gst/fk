<?php
namespace controllers;

/*
 * Klasse Upload, erbt von der Klasse Controller
 */

class Upload extends Controller
{
  
   /*
    * implementierung der Funktion run
    * wenn die Aktion speichern/upload ausgelöst wird, wird die Funktion saveImageToDB auf dem Objekt aufgerufen
    * wenn, die daten in die Datenbank geschrieben wurden, wird wieder auf die Startseite geleitet 
    */
   public function run(){
   
   if (isset($_REQUEST['action'])){
	   switch($_REQUEST['action']){
			case 'save':
				$rVal = $this->saveImageToDB();
				if($rVal === true){
					header( 'Location: index.php' ) ;
				}//if
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
			'UserId' => '1',
			'Format' => $_FILES['Durchsuchen']['type'],
			'CreaDateTime' => date("Y-m-d H:i:s"),
			'Description' => $_REQUEST['Bildbeschreibung'],
			'PictureState' => '1',
		
		);
	
		$insertId = $this->db->insert('FK_Picture',$insertArgs,'ssdsssd' ); // ??
		if($insertId !== false){
			$filename = "image".$insertId.".jpg" ; // der Bildname wird erzeugt
			echo "vor1"; // Testzeile
			$move = move_uploaded_file($_FILES['Durchsuchen']['tmp_name'],$this->conf->imgDir.$filename);
			echo "vor"; // testzeile
			$this->makeThumbnail($filename); // die Funktion makeThumbnail wird aufgerufen
			//var_dump($move);
		}else{
		
		}
		return true;
  } else {
   var_dump($_FILES);
  }
  
  
  
  
  
  }// saveImageToDB()
  
  /* ??warum immer die var_dumps??
   * Funktion makeThumbnail
   * hier weden von den hochladenden Bildern Bilder in kleinerem Format/Thumbnails erstellt
   */
  private function makeThumbnail($filename){
  	echo "makeThumbnail";
  	$image = imagecreatefromjpeg($this->conf->imgDir.$filename); //von dem Bild wird ein Bild gemacht
	$w = imagesx($image); //Breite und Höhe des Bildes werden abgefragt
	$h = imagesy($image);
	$new_w = 250; // eine neue Breite wird festgelegt
	$new_h = floor( $h * ($new_w / $w) ); // anhand der bekannten Maße und der festgelegten neuen Breite wird die neue Höhe berechnet
	$tmpImg = imagecreatetruecolor($new_w, $new_h); // es wird ein neues Bild mit neuer Höhe und Breite erstellt, dass noch leer ist
	var_dump($tmpImg); 
	$dst_image = $this->conf->imgDir."tn_".$filename;
	var_dump($dst_image); //??
	$r = imagecopyresized($tmpImg, $image, 0, 0, 0, 0, $new_w, $new_h, $w, $h); // das Bild wird mit der neuen Größe erstellt
	var_dump($r);
	$r = imagejpeg($tmpImg, $dst_image); // Das Bild wird als jpeg gespeichert
	var_dump($r);
  	
	
  }//makeThumbnail()
  
  



} ?>
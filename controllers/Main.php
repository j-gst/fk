<?php namespace controllers;
// Klasse Main, erbt von Controller
class Main extends Controller
{
  


  
  


   /*
    * die Methode run() wird implementiert
    * das erzeugte Objekt lädt ein array mit Bildern durch die Methode getsFromDB()
    * das erzeugte Ojekt lädt ein array mit Seitenzahlen über die Funktion getPagination()
    * die Methode display wird mit dem Parameter main auf dem Objekt aufgerufen
   */
   public function run(){

   //var_dump($_REQUEST);
   
    if ( isset($_REQUEST['save_comment']) && isset($_REQUEST['id']) ){
		$this->saveCommentToDB();	
	}
   
    $this->displayData['images'] = $this->getImagesFromDB();
	$this->displayData['pagination'] = $this->getPagination();
    $this->display("main");
   }
/**
 * Methode getImagesFromDB
 * laed alle Daten zu den Bildern aus der DB
 *
  */
  private function getImagesFromDB(){
      $offset = 0;
	  $this->displayData['p'] = 1;
	  /*
	   * Wenn die Variable offset, die mit 0 initialisiert wurde exisitiert und größer 0 ist,
	   * wird  
	   */
	  if(isset($_REQUEST['p']) && (int)$_REQUEST['p'] > 0){
		$offset = ((int)$_REQUEST['p'] - 1) * $this->conf->showImgNum; // ?? ja was genau wird dann ?? eine Abfrage mit diesem offset sird zum int geparst und um 1 verringert und dann  multipliziert mit ??
	    $this->displayData['p'] = (int)$_REQUEST['p'];
	  }
	  
	  // query String
	  $q = sprintf('SELECT FK_Picture.Id, Name, CreaDateTime, Description, UserName 
			FROM FK_Picture LEFT JOIN FK_User ON FK_User.Id = FK_Picture.UserId
			ORDER BY CreaDateTime DESC LIMIT %d, %d', $offset, $this->conf->showImgNum);
	  
	  // alle Bildinformationen aus der DB laden
	  $images = $this->db->query_array($q); 
	  $displayImages = array();
	  foreach($images as $key => $img){ 
		$displayImages[$key] = new \classes\imageArea();
		$displayImages[$key]->id = $img['Id'];
		$displayImages[$key]->titel = $img['Name'];
		$date = new \DateTime($img['CreaDateTime']);
		$displayImages[$key]->date = $date->format('d.m.Y H:i:s');
		$displayImages[$key]->user = $img['UserName'];
		$displayImages[$key]->desc = nl2br($img['Description']);
		$displayImages[$key]->thumbnail = $this->conf->imgDir."tn_image".$img['Id'].".jpg";
		$displayImages[$key]->imgLink = $this->conf->imgDir."image".$img['Id'].".jpg";
		

		
		// zu jedem Bild noch die Kommentare laden
		$q = sprintf('SELECT UserName,Comment,CreaDateTime
				FROM FK_Comments LEFT JOIN FK_User ON UserId = FK_User.Id
				WHERE PictureId = %d ORDER BY CreaDateTime ',$img['Id']);

		$comments = array();
	    $comments = $this->db->query_array($q); 
		if($comments !== false){
			$displayImages[$key]->commentsCount = count($comments);
			foreach($comments as $cKey => $comment){
				$displayImages[$key]->comments[$cKey]['UserName'] = $comment['UserName'];
				$displayImages[$key]->comments[$cKey]['Comment'] = nl2br($comment['Comment']);
				$cDate = new \DateTime($comment['CreaDateTime']);
				$displayImages[$key]->comments[$cKey]['date'] = $cDate->format('d.m.Y H:i:s');
				
			}//foreach($comments as $cKey => $comment)
		}
		
		
		
	  }// foreach($images as $key => $img)
	  
	 
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

  
  
  
  
  private function saveCommentToDB(){
  
  $insertArgs = array(
			'PictureId' => $_REQUEST['id'],
			'Comment' => $_REQUEST['comment_text'],
			'UserId' => 1,
			'CreaDateTime' => date("Y-m-d H:i:s"),
			'CommentState' => 1,
		);
	
		$insertId = $this->db->insert('FK_Comments',$insertArgs,'ssdsd' ); // ??
		if($insertId !== false){
			//echo "OK";
        }else{
		
		}
  
  
  
  }
} ?>
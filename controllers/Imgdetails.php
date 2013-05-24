<?php namespace controllers;
// Klasse Imgdetails, erbt von Controller
class Imgdetails extends Controller
{
	/*
	 * die Funktion run wird implementiert 
	*/
	public function run(){
		if(isset($_REQUEST['imgid'])){
			$this->displayData['imgdetails'] = $this->getImgDetails((int)$_REQUEST['imgid']);
		}//if


		$this->display("imgdetails") ;
	}//run


	/**
	 * Thies Schillhorn, 20130524
	 * Holen saemtlicher Bildinformationen zu gegebener BildId aus der DB
	 */
	private function getImgDetails($myPicId) {
		$this->displayData['p'] = 1;
		/*
		 * Inhalt des Requests holen
		 */
		if(isset($_REQUEST['p']) && (int)$_REQUEST['p'] > 0){
			$this->displayData['p'] = (int)$_REQUEST['p'];
		}
		 
		// query String um Bildinformationen aus der DB zu holen
		$q = sprintf('SELECT distinct FK_Picture.Id, FK_Picture.Name, FK_Picture.CreaDateTime,
				FK_Picture.Description,
				(SELECT UserName from FK_User where FK_User.Id = FK_Picture.UserId) AS UserName
				FROM FK_Picture
				WHERE FK_Picture.Id = %d', $myPicId);

		// alle Bildinformationen aus der DB laden
		$images = $this->db->query_array($q);

		// Array fuer spaetere Darstellung im Browser mit Bildinformationen fuellen
		$displayImages = array();
		foreach($images as $key => $img){
			$displayImages[$key] = new \classes\imageArea();
			$displayImages[$key]->id = $img['Id'];
			$displayImages[$key]->titel = htmlentities($img['Name']);
			$date = new \DateTime($img['CreaDateTime']);
			$displayImages[$key]->date = $date->format('d.m.Y H:i:s');
			$displayImages[$key]->user = $img['UserName'] ? $img['UserName'] : 'Gast';
			$displayImages[$key]->desc = nl2br(htmlentities($img['Description']));
			$displayImages[$key]->thumbnail = $this->conf->imgDir."tn_image".$img['Id'].".jpg";
			$displayImages[$key]->imgLink = $this->conf->imgDir."image".$img['Id'].".jpg";

			// zu jedem Bild noch die Kommentare laden
			$q = sprintf('SELECT UserName,Comment,CreaDateTime
					FROM FK_Comments LEFT JOIN FK_User ON UserId = FK_User.Id
					WHERE PictureId = %d ORDER BY CreaDateTime ',$img['Id']);
			$comments = array();
			$comments = $this->db->query_array($q);

			// Kommentare ebenfalls im Array fuer Darstellung im Browser ablegen
			if($comments !== false){
				$displayImages[$key]->commentsCount = count($comments);
				foreach($comments as $cKey => $comment){
					$displayImages[$key]->comments[$cKey]['UserName'] = $comment['UserName'] ? $comment['UserName'] : 'Gast';;
					$displayImages[$key]->comments[$cKey]['Comment'] = nl2br($comment['Comment']);
					$cDate = new \DateTime($comment['CreaDateTime']);
					$displayImages[$key]->comments[$cKey]['date'] = $cDate->format('d.m.Y H:i:s');

				}//foreach($comments as $cKey => $comment)
			}
		}// foreach($images as $key => $img)
		 

		return $displayImages;
	}//getImgDetails()
}
?>
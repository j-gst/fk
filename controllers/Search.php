<?php namespace controllers;
// Klasse Search, erbt von Controller
// 20130526 Thies Schillhorn
class Search extends Controller
{
	/*
	 * die Funktion run muss implemntiert werden, da diese nach der initialisierung einer vom Controller
	* abgeleiteten Klasse aufgerufen wird.
	*/
	public function run(){
		if (isset($_REQUEST['s'])) {
			$this->displayData['search'] =  $this->suche($_REQUEST['s']);
		}//if

		$this->display("search");
	}//run


	/**
	 * Suche Bilder der angegebenen Kategorie / Suchbegriffs;
	 */
	private function suche($keywords) {
		$association = 'AND';
		$keywords = explode(" ",$keywords);
		$query = array();

		foreach($keywords as $keyword) {
			$query[] = "(   `Name`     LIKE '%".$keyword."%'
					OR  `Comment`    LIKE '%".$keyword."%' )
							";
		}

		$query = implode("\n ".$association." ",$query);

		$sql = "
				SELECT * FROM `FK_Picture`
				WHERE Id IN (
				SELECT  `PictureId`
				FROM `FK_Picture_Category`
				WHERE `CategoryId` in (
				SELECT Id
				FROM `FK_CategoryAndTag`
				WHERE   ".$query."
						)
			   )
						;" ;

		return $this->showResult($sql);
	}


	private function showResult($sql) {

		$images = $this->db->query_array($sql);
		$displayImages = array();
		if (isset($images) and (count($images) > 0)){
			foreach($images as $key => $img){
				$displayImages[$key] = new \classes\imageArea();
				$displayImages[$key]->id = $img['Id'];
				$displayImages[$key]->titel = htmlentities($img['Name']);
				$displayImages[$key]->archive = $img['ArchiveId'];
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
		}

		return $displayImages;
	}

}
?>
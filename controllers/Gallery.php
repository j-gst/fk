<?php
namespace controllers;
/**
* Upload Controller
* bearbeiten der Datei uploads (Bild- und ZIP-Dateien)
* @Author: Beate Gericke, Gerrit Storm
*/
class Gallery extends Controller
{
   /**
   *
   */
   public function run(){
		$this->redirectOnInsufficientRights(array('view'));
 
	   if(isset($_REQUEST['download']) 
			&& isset($_REQUEST['id']) 
			&& is_numeric($_REQUEST['id'])){
			

			
			
			$this->downloadGallery($_REQUEST['id']);
		}
		
       $this->displayData['error'] = false;
	   if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])){
			$this->showGallery($_REQUEST['id']);
	   
	   }else{
	      $this->displayData['error'] = true;
	   
	   }
	   
   	// Auswahl des korrekten Templates - Gallery
	$this->display("gallery"); 
   }// run()



private function showGallery($id){



    $images = $this->getImages($id);
	  
	$this->displayData['archive']  = array();
	$this->displayData['archive'] = new \classes\imageArea();
	
	$this->displayData['archive']->titel = "";
	$this->displayData['archive']->date = "";
	$this->displayData['archive']->user = "";
	$this->displayData['archive']->desc = "";
	  
	  
	 if(is_array($images) &&  count($images) > 1){
	

	$this->displayData['id'] = $id;
	$this->displayData['archive']->titel = htmlentities($images[0]['Name']);
	$date = new \DateTime($images[0]['CreaDateTime']);
	$this->displayData['archive']->date = $date->format('d.m.Y H:i:s');
	$this->displayData['archive']->user = $images[0]['UserName'] ? $images[0]['UserName'] : 'Gast';
	$this->displayData['archive']->desc = nl2br(htmlentities($images[0]['Description']));
		
	 foreach($images as $key => $img){ 
		$this->displayData['images'][$key] = new \classes\imageArea();
		$this->displayData['images'][$key]->id = $img['Id'];
		$this->displayData['images'][$key]->titel = htmlentities($img['Name']);
		$this->displayData['images'][$key]->thumbnail = $this->conf->imgDir."tn_image".$img['Id'].".jpg";
		$this->displayData['images'][$key]->imgLink = $this->conf->imgDir."image".$img['Id'].".jpg";
	}//foreach

} //if(!is_arry($images) &&  count($images) < 1)
else{
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
	if (! is_array($images) || count($images) < 1){
		return false;
	}
	set_time_limit(0);

	$zip = new \ZipArchive();
	$downloadName = "Gallery_".$id.".zip";
	$zipFileName = sys_get_temp_dir().$downloadName;

	if ($zip->open($zipFileName, \ZIPARCHIVE::CREATE)!==TRUE) {
	    return false;
	}
	
	foreach($images as $img){
		$zip->addFile($this->conf->imgDir."image".$img['Id'].".jpg");
	}//foreach
	
	$zip->close();
	
	header('Content-type: application/octetstream');
	header('Content-Disposition: attachment; filename="' . $downloadName . '"');
	readfile($zipFileName);	
	
	
	
	
	
	
}


}// class
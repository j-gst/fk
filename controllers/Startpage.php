<?php
// autor: maike schroeder


namespace controllers;

class Startpage extends Controller
{

   public function run(){
       $this->displayData['error'] = false;
       $this->showStartpage();
	   $this->display("startpage"); 
   }

	private function showStartpage(){

		// hier werde alle bildnamen die mit "tn_" beginnen (alle Thumbnails), in das Array $images gelegt
	    $images = glob($this->conf->imgDir.'tn_*');
	
		// ab hier aus "gallery-controller" uebernommen und angepasst
		$this->displayData['archive']  = array();
		$this->displayData['archive'] = new \classes\imageArea();
				  
		 if(is_array($images) &&  count($images) > 0){
				
			 foreach($images as $key => $img){ 
				$this->displayData['images'][$key] = new \classes\imageArea();
				$this->displayData['images'][$key]->thumbnail = $img;
				$this->displayData['images'][$key]->imgLink = str_replace('tn_', '', $img);	// "tn_" aus bildnamen entfernt
			}
		} else{
			$this->displayData['error'] = true;
		}
	}
}// class
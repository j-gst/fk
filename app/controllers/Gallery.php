<?php
namespace controllers;
/**
 * Gallery Controller
 * Anzeige der Galerien
 * Download der ZIP-Dateien von Galerien
 * @author: Beate Gericke (Nebenautor), Gerrit Storm (Hauptautor)
 */
class Gallery extends Controller
{
    /**
     * implementierung der Funktion run
     */
    public function run(){

//download des ZIP Archives       
        if(isset($_REQUEST['download'])
        && isset($_REQUEST['id'])
        && is_numeric($_REQUEST['id'])){
            $this->downloadGallery($_REQUEST['id']);
        }
// Anzeige Gallerie
        else if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])){
             $this->displayData['error'] = false;
            $this->showGallery($_REQUEST['id']);
        }else{
            $this->displayData['error'] = true;
        }

        // Auswahl des korrekten Templates - Gallery
        $this->display("gallery");
    }// run()
  
     /**
      * Funktion zum Anzeigen des Gallery-Objekts mit der gewaehlten id
      * @param int $id Gallerie-ID
      */
    private function showGallery($id){

        //ruft auf dem Objekt die Funktion getImages mit der uebergebenen id auf 
        // $images ist ein array mit Bildinfos aus der DB
        $images = $this->getImages($id);
         
         //es wird ein neues Objekt der Klasse imageArea erstellt    
         //die Variablen des imageArea-Objekts werden mit einem leeren String instanziert
        $this->displayData['archive'] = new \classes\imageArea();
        $this->displayData['archive']->titel = "";
        $this->displayData['archive']->date = "";
        $this->displayData['archive']->user = "";
        $this->displayData['archive']->desc = "";
         
        //wenn zu diesem Archiv Bilder in der DB gefunden wurden:
        if(is_array($images) &&  count($images) > 1){
            $this->displayData['id'] = $id;
            $this->displayData['archive']->titel = htmlentities($images[0]['Name']);
            $date = new \DateTime($images[0]['CreaDateTime']);
            $this->displayData['archive']->date = $date->format('d.m.Y H:i:s');
            $this->displayData['archive']->user = $images[0]['UserName'] ? $images[0]['UserName'] : 'Gast';
            $this->displayData['archive']->desc = nl2br(htmlentities($images[0]['Description']));

            // Speicherung der Bilddaten fuer die Anzeige im Template
            foreach($images as $key => $img){
                $this->displayData['images'][$key] = new \classes\imageArea();
                $this->displayData['images'][$key]->id = $img['Id'];
                $this->displayData['images'][$key]->titel = htmlentities($img['Name']);
                $this->displayData['images'][$key]->thumbnail = IMG_DIR."tn_image".$img['Id'].".jpg";
                $this->displayData['images'][$key]->imgLink = IMG_DIR."image".$img['Id'].".jpg";
            }//foreach

        } //if()
        // es wurden keine Bilder in der DB gefunden:
        else{ 
            $this->displayData['error'] = true;
        }
    }//showGallery()


    /**
     * Bildinformationen aus DB laden
     * @param int $id Bild-ID
     */
    private function getImages($id){
        // query String
        $q = sprintf('SELECT FK_Picture.Id, Name, CreaDateTime, Description, UserName, ArchiveId , OriginalName
			FROM FK_Picture LEFT JOIN FK_User ON FK_User.Id = FK_Picture.UserId
			WHERE ArchiveId = %d',  $id);
         
        // alle Bildinformationen aus der DB laden
        return  $this->db->query_array($q);
    }//getImages()

    /**
     * Download Gallery als ZIP
     * @param int $id Gallerie-ID
     */
    private function downloadGallery($id){
        $images = $this->getImages($id);
        // wenn images kein array ist oder weniger als ein Element enthalten ist, wird false zurueckgegeben
        if (! is_array($images) || count($images) < 1){
            return false;
        }

        // das kann etwas dauern
        set_time_limit(0);

       
        $zip = new \ZipArchive();
        $downloadName = "Gallery_".$id.".zip";
        $zipFileName = sys_get_temp_dir().$downloadName;

        if ($zip->open($zipFileName, \ZIPARCHIVE::CREATE)!==TRUE) {
            return false;
        }

        // fuer jedes Element im images-Array wird dem zip-Archiv eine Datei zugefuegt
        foreach($images as $img){
            $zip->addFile(IMG_DIR."image".$img['Id'].".jpg", $img["OriginalName"]);
            // addFile legt die Dateien doppelt ins Archiv (mit komplettem Pfad und OriginalName) daher:
            $zip->deleteName(IMG_DIR."image".$img['Id'].".jpg");
        }//foreach

        $zip->close(); //der Datenstrom wird geschlossen               

        //header senden
        header('Content-type: application/octetstream');
        header('Content-Disposition: attachment; filename="' . $downloadName . '"');
        // Datei ausgeben
        readfile($zipFileName);

    }//downloadGallery()


}// class
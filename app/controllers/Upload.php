<?php
namespace controllers;
/**
 * Upload Controller
 * Datei uploads (Bild- und ZIP-Dateien)
 * Speicherung der Bilder im Dateisystem und der Bildinformationen in der DB
 * @author: Beate Gericke , Gerrit Storm
 */
class Upload extends Controller
{
    private $uploaderror = "";

    /**
     * implementierung der Funktion run
     * wenn die Aktion speichern/upload ausgeloest wird, wird die Funktion saveImageToDB aufgerufen
     * wenn, die daten in die Datenbank geschrieben wurden und die Dateien im Dateisystem erzeugt wurden,
     * wird wieder auf die Startseite geleitet
     */
    public function run(){

        $this->displayData['uploaderror'] = "";
         
        if (isset($_REQUEST['action'])){
            switch($_REQUEST['action']){
                // Upload
                case 'save':
                    $rVal = $this->handleUpload();
                    // Upload OK?
                    if($rVal === true){
                        // Umleitung Hauptseite
                        header( 'Location: index.php' ) ;
                    }
                    // Hier gab es einen Fehler beim Upload
                    $this->displayData['uploaderror'] = $this->uploaderror;
                    break;
            }//switch
        }// if
         
        // Auswahl des korrekten Templates - Uploadformular
        $this->display("upload");
    }//run()
     

    /**
     * Funktion handelUpload
     * hier werden die eingegeben Daten in die Datenbank geschrieben
     */
    private function handleUpload(){

         
        // Ist eine Datei ohne Fehler hochgeladen worden?
        if(isset($_FILES['Durchsuchen']) && $_FILES['Durchsuchen']['error'] === 0){

            // ist MIME gueltig (Bild oder Zip)?
            $mimeType = $this->checkMime($_FILES['Durchsuchen']['tmp_name'], true);
            if(  $mimeType === false ){
                $this->uploaderror = "Falsches Dateiformat!";
                return false;
            }


            //Titelinformation wird gespeichert
            $titel = "[kein Titel]"; // Default-Titel
            if(isset($_REQUEST['Bildtitel'])
            && trim($_REQUEST['Bildtitel'] !== "")) {
                $titel = $_REQUEST['Bildtitel'];
            }

            // Infos fuer die DB
            $dbInsertArray = array(
			'OriginalName' => $_FILES['Durchsuchen']['name'],
			'Name' => $titel,
			'UserId' => $this->user->getId(),
			'Format' => $_FILES['Durchsuchen']['type'],
			'CreaDateTime' => date("Y-m-d H:i:s"),
			'Description' => $_REQUEST['Bildbeschreibung'],
			'PictureState' => '1',
			'ArchiveId' => null,
            );


            // Ist MIME ZIP?
            if ($mimeType == 'application/zip'){
                 
                // ZIP entpacken
                $zip = new \ZipArchive;
                if ($zip->open($_FILES['Durchsuchen']['tmp_name']) === true) {
                    $tmpDir = $_FILES['Durchsuchen']['tmp_name'].'TMP_ZIP';
                    $ex = $zip->extractTo($tmpDir);
                     
                    $zip->close();
                } else {
                    $this->uploaderror = "ZIP konnte nicht entpackt werden!";
                    return false;
                }
                 
                // TMP Verzeichnis oeffnen und Dateien einlesen
                $dirHandle = opendir($tmpDir);
                 
                 
                $counter = 1; // counter fuer tmp filename
                 
                // DB insert Archiv Information
                $archiveInfo = array('ZipName' => $_FILES['Durchsuchen']['name']);
                $aInsertId = $this->db->insert('FK_Archive',$archiveInfo,'s' );
                var_dump( $this->db->getErrorList() );
                if($aInsertId !== false){
                    $dbInsertArray['ArchiveId'] = $aInsertId;
                }

                // Dateien einlesen
                while (false !== ($fileName = readdir($dirHandle))) {
                    // skip . und ..
                    if($fileName === "." || $fileName === "..") continue;

                    $fName =  $tmpDir."/".$fileName ;

                    // versuche Umbenennen der Datei (sonst z.T. Probleme wg. Sonderzeichen ...)
                    $newName = $tmpDir."/".$counter.".tmp_file";
                    $reName = rename($fName , $newName);
                    if($reName === true){
                        $fName = $newName;
                    }

                    // skip wenn MIME nicht ok - hier nur Bilddateien
                    if(! $this->checkMime($fName)) continue;

                    // Bild Speichern (DB und FS)
                    $dbInsertArray['OriginalName'] = $fileName;
                    $this->savePicture($dbInsertArray, $fName);

                    $counter++;
                }// while
                 
                // Reste in tmp beseitigen
                $this->remove($tmpDir);

                 
            } else {
                // kein ZIP - also eine Bilddatei
                if( !$this->savePicture($dbInsertArray, $_FILES['Durchsuchen']['tmp_name'])){
                    $this->uploaderror = "Fehler beim Speichern der Datei!";
                    return false;
                }
            } //else
            return true;
        // Fehlerbehandlung	- Datei Upload fehlgeschlagen
        } else {
            $errMsg = "Unbekannter Fehler - Timeout?";
            if(isset($_FILES['Durchsuchen']['error'])){
                if($_FILES['Durchsuchen']['error'] == 1){
                    $errMsg = "Datei ist zu groß";
                }
                elseif($_FILES['Durchsuchen']['error'] == 4){
                    $errMsg = "Keine Datei angegeben";
                }
                else{
                    $errMsg = "Fehlercode - ".$_FILES['Durchsuchen']['error'];
                }
            }
            $this->uploaderror = "Fehler beim upload: ".$errMsg;
            return false;
        }
    }// saveImageToDB()

    /**
     * Speichern des Bildes in DB und Dateisystem
     * @param array $dbInsertArray
     * @param String $fileName
     */
    private function savePicture($dbInsertArray, $fileName){

        $insertId = $this->db->insert('FK_Picture',$dbInsertArray,'ssdsssdd' );
        if($insertId !== false){
            // der Bildname wird erzeugt
            $newFileName = "image".$insertId.".jpg" ; 
            $move = rename($fileName,IMG_DIR.$newFileName);

            $this->makeThumbnail($newFileName); 
        }else{
            /**
             * @TODO log oder Mail?
             */
            // var_dump( $this->db->getErrorList() );
            return false;
        }
        return true;
    } // private function savePicture()

    /**
     * Check ob korrekter DateiTyp
     * @param String $fName Dateiname
     * @param boolean $zip ist zip auch ok?
     * @return boolean
     */
    private function checkMime($fName, $zip = false){

        // gueltige Bilddateien
        $validMime = array(
	   'image/jpeg',
        );

        // ist zip auch ok?
        if($zip === true){
            $validMime[] = 'application/zip';
        }

        $return = false;

        // ermitteln des MIME TYPE
        $fileType = "";
        //zuerst fileinfo probieren
        if(class_exists("\\finfo")){
            $fileInfo = new \finfo(FILEINFO_MIME);
            $fileType = @$fileInfo->file($fName);
        // wenn das nicht verfuegbar ist alternativ mime_content_type (deprecated):
        }else if(function_exists('mime_content_type')){
            $fileType = @mime_content_type($fName);
        }
 
        // skip Datei wenn MIME TYPE nicht ermittelbar
        if($fileType !== false ){
            foreach($validMime as $mime){
                $mimeType = strstr($fileType, $mime);
                if(false !== $mimeType ){
                    $return = $mime;
                }
            }

    }
    return $return;
}


/**
 * Funktion makeThumbnail
 * hier weden von den hochladenden Bildern Bilder in kleinerem Format (Thumbnails) erstellt
 * @param String $filename
 */
private function makeThumbnail($filename){
    
    //von dem Bild wird eine image Resource erzeugt
    $image = imagecreatefromjpeg(IMG_DIR.$filename); 
    
    //Breite und Hoehe des Bildes werden abgefragt
    $w = imagesx($image); 
    $h = imagesy($image);
    
    // eine neue Breite wird festgelegt
    $new_w = 250; 
    // anhand der bekannten Masse und der festgelegten neuen Breite wird die neue Hoehe berechnet
    $new_h = floor( $h * ($new_w / $w) ); 
    // es wird ein neues Bild mit neuer Hoehe und Breite erstellt, dass noch leer ist
    $tmpImg = imagecreatetruecolor($new_w, $new_h); 
    $dst_image = IMG_DIR."tn_".$filename;
    // das Bild wird mit der neuen Groesse erstellt
    $r = imagecopyresized($tmpImg, $image, 0, 0, 0, 0, $new_w, $new_h, $w, $h); 
    // Das Bild wird als jpeg gespeichert
    $r = imagejpeg($tmpImg, $dst_image); 
     
}//makeThumbnail()

/**
 * Rekursives Loeschen einer Datei / eines Verzeichnisses
 * benoetigt fuer das temp. Entpacken der zip Dateien
 * @param String $path
 */
private function remove($path) {
    if (!file_exists($path) ) return;
    if (is_dir($path)) {
        $subDirs = scandir($path);

        foreach ($subDirs as $subDir) {
            if ($subDir == "." || $subDir == "..") continue;
            $this->remove($path."/".$subDir);
        }
        rmdir($path);
        return;
    } else{
        unlink($path);
    }
} // private function remove()
} //class ?>
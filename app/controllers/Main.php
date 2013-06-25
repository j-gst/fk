<?php
namespace controllers;
/**
 * Controller Main
 * - Anzeige der Bildseite mit Beschreibung und Kommentaren
 * - Einzelbild anzeigen
 * - Thumbnail anzeigen
 * - Kommentar speichern 
 * @author Gerrit Storm, Beate Gericke
 */
class Main extends Controller
{
    
    /**
    * die Methode run() wird implementiert
	* Einstiegspunkt in den Controller
    */
    public function run(){
         

        
// Filter gesetzt? - Dann in SESSION uebernehmen
    if (  isset($_REQUEST['ufilter']) ){
           $_SESSION['ufilter'] = $_REQUEST['ufilter'];      
    }
        
         
//Einzelbild anzeigen 
        if (  isset($_REQUEST['image']) ){
            $this->showImage($_REQUEST['image']);
//Thumbnail anzeigen
        }elseif ( isset($_REQUEST['tn']) && isset($_REQUEST['id']) ){
              $this->showImageTN($_REQUEST['id']);
//Kommentar speichern              
        }elseif ( isset($_REQUEST['save_comment']) && isset($_REQUEST['id']) ){
            // Fehlerseite zeigen wenn Recht save_comment nicht vorhanden
            $this->redirectOnInsufficientRights(array('comment_make'));
            $this->saveCommentToDB();
			
			// wenn der Nutzer von der Detailseite kommt
			// wird er da auch wieder landen
			if( isset($_REQUEST['detailpage']) && $_REQUEST['detailpage'] == 1){
			   header( 'Location: ?page=imgdetails&imgid='.$_REQUEST['id'] ) ;
			}
        } //elseif
        

        $this->displayData['images'] = $this->getImagesFromDB();
        $this->displayData['pagination'] = $this->getPagination();
        $this->display("main");

        

    }
    
    /**
     *  alle Daten zu den Bildern aus der DB laden
     */
    private function getImagesFromDB(){
        $offset = 0;
        $this->displayData['p'] = 1;
        
         //Wenn die Variable offset, die mit 0 initialisiert wurde exisitiert und > 0 ist,
        if(isset($_REQUEST['p']) && (int)$_REQUEST['p'] > 0){
            $offset = ((int)$_REQUEST['p'] - 1) * $this->conf->showImgNum;
            $this->displayData['p'] = (int)$_REQUEST['p'];
        }
         
        // Filter gesetzt?
        $filter = ""; 
        if(isset($_SESSION['ufilter']) && $_SESSION['ufilter'] !== "0"){
            if($_SESSION['ufilter'] == 1)   $filter = " AND UserName IS NULL "; 
            else $filter = ' AND UserName = "'.$_SESSION['ufilter'].'"';
        }
        
        // query String
        $q = sprintf('SELECT FK_Picture.Id, Name, CreaDateTime, Description, UserName, ArchiveId
                  FROM FK_Picture LEFT JOIN FK_User ON FK_User.Id = FK_Picture.UserId WHERE  PictureState != -1 %s
			      ORDER BY CreaDateTime DESC LIMIT %d, %d',$filter, $offset, $this->conf->showImgNum);
        
        
        // alle Bildinformationen aus der DB laden
        $images = $this->db->query_array($q);
        $displayImages = array();
        
        // Bildinformationen fuer die Anzeige im Template speichern
        if($images !== false) foreach($images as $key => $img){
            $displayImages[$key] = new \classes\imageArea();
            $displayImages[$key]->id = $img['Id'];
            $displayImages[$key]->titel = htmlentities($img['Name']);
            $displayImages[$key]->archive = $img['ArchiveId'];
            $date = new \DateTime($img['CreaDateTime']);
            $displayImages[$key]->date = $date->format('d.m.Y H:i:s');
            $displayImages[$key]->user = $img['UserName'] ? $img['UserName'] : 'Gast';
            $displayImages[$key]->desc = nl2br(htmlentities($img['Description']));

            // zu jedem Bild noch die Kommentare laden
            $q = sprintf('SELECT UserName,Comment,CreaDateTime
				FROM FK_Comments LEFT JOIN FK_User ON UserId = FK_User.Id
				WHERE CommentState != -1 AND PictureId = %d ORDER BY CreaDateTime ',$img['Id']);

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
         

        return $displayImages;
    }//getImagesFromDB()

    /**
     * Es wird geprueft, wieviele Bilder in der Datenbank liegen
     * Je nachdem wird errechnet, wieviele Seiten benoetigt werden
     */
    private function getPagination(){
        
        // Filter gesetzt?
        $filter = ""; 
        if(isset($_SESSION['ufilter']) && $_SESSION['ufilter'] !== "0"){
            if($_SESSION['ufilter'] == 1)   $filter = " AND UserName IS NULL "; 
            else $filter = ' AND UserName = "'.$_SESSION['ufilter'].'"';
        }

        // Anzahl der Bilder in der DB
        $q = sprintf("SELECT COUNT(FK_Picture.Id) AS COUNT FROM FK_Picture 
                      LEFT JOIN FK_User ON FK_User.Id = FK_Picture.UserId 
                      WHERE PictureState != -1 %s",$filter);
        $count = $this->db->query_array($q); 
        
        // die Anzahl der anzuzeigenden Seiten wird ermittelt
        return ceil((float)$count[0]['COUNT'] / $this->conf->showImgNum);

    }//getPagination()


    /**
     * 
     * Anzeige eines Bildes
     * @param unknown_type $id
     */
    private function showImage($id){
        $downloadName = "image".(int)$id.".jpg";
         
        $q = sprintf('SELECT OriginalName FROM FK_Picture WHERE Id = %d ', $id );
         
        // alle Bildinformationen aus der DB laden
        $origName = $this->db->query_array($q);
        if(isset($origName[0]['OriginalName'])) $downloadName = $origName[0]['OriginalName'];
         

        $imgPath = IMG_DIR."image".(int)$id.".jpg";
        $image = false;

        if(is_readable($imgPath )){
            $image = imagecreatefromjpeg($imgPath);
        }

        if (! $image ) {
            die("Dieses Bild gibt es nicht.");
        }
	    // Anzeige des Bildes
        header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
        header('Pragma: no-cache');
        header( "Content-type: image/jpeg" );
        header("Content-Disposition: inline; filename=".$downloadName );

        @imagejpeg( $image );

        if ( $image ) {
            imagedestroy( $image );
        }
        exit(0);
    }// showImage()

    
    /**
     * Anzeige eines Thumnails
     * @param int $id
     */
    private function showImageTN($id){
       
        $path = IMG_DIR . "tn_image".$_GET['id'].".jpg";
        $image = imagecreatefromjpeg($path); //von dem Bild wird ein Bild gemacht
        if (! $image  ) {
        	exit;
        }
        header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
        header('Pragma: no-cache');
        header( "Content-type: image/jpeg" );
        
        // Bild anzeigen und loeschen
        @imagejpeg($image);
        if ($image) {
            imagedestroy($image);
        }
        // es soll nur das Thumnail angezeigt werden
        exit;

    }//showImageTN()

    /**
     * Kommentar in DB speichern
     */
    private function saveCommentToDB(){


        $insertArgs = array(
			'PictureId' => $_REQUEST['id'],
			'Comment' => htmlentities($_REQUEST['comment_text']),
			'UserId' => $this->user->getId(),
			'CreaDateTime' => date("Y-m-d H:i:s"),
			'CommentState' => 1,
        );
        $this->db->insert('FK_Comments',$insertArgs,'ssdsd' ); // ??
    }//saveCommentToDB()
    
} ?>
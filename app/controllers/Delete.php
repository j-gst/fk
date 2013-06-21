<?php
namespace controllers;

/**
 * Delete Controller
 * zustaendig fuer das Loeschen von Kommentaren und Bildern
 * @author: Gerrit Storm
 */
class Delete extends Controller
{

  /**
    * die Methode run() wird implementiert
	* Einstiegspunkt in den Controller
    */
    public function run(){

//Kommentar loeschen
		 if (isset($_REQUEST['comment'])){
		 	//den Kommentar laden
			$q = sprintf('SELECT Id,UserId,PictureId FROM FK_Comments WHERE Id = %d ',$_REQUEST['comment']);
			$comment = array();
			$comment = $this->db->query_array($q);
			// Kommentar gefunden
			if (isset($comment[0]["Id"])){
				if($this->user->isAllowedToDeleteComment($comment[0]["UserId"])){		
					// query String
					$q = sprintf('UPDATE FK_Comments set CommentState = -1 WHERE Id = %d', $_REQUEST['comment']);
					$images = $this->db->query_array($q);
				}
			}
			// umleiten auf Detailseite
		    header( 'Location: index.php?page=imgdetails&imgid='.$comment[0]["PictureId"] ) ;
			exit;
		}
//Bild loeschen
	    elseif (isset($_REQUEST['image'])){
           //das Bild laden
			$q = sprintf('SELECT Id,UserId FROM FK_Picture WHERE Id = %d ',$_REQUEST['image']);
			$img = array();
			$img = $this->db->query_array($q);
			// Bild gefunden
			if (isset($img[0]["Id"])){
				if($this->user->isAllowedToDeletePicture($img[0]["UserId"])){		
					// query String
					$q = sprintf('UPDATE FK_Picture set PictureState = -1 WHERE Id = %d', $_REQUEST['image']);
					$images = $this->db->query_array($q);
				}
			}
		     // umleiten auf Detailseite
		    header( 'Location: index.php' ) ;
			exit;
		}//elseif
		 
		 // umleiten auf Detailseite
		header( 'Location: index.php') ;
		
    }//run()




}//class ?>
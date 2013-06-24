<?php namespace classes;
/**
 * Container fuer Bild-Informationen
 * @autor: Gerrit Storm (Kommentare Beate Gericke)
 */
class imageArea
{
    // Titel des Bildes
    public $titel;
    
    // DB id
	public $id;
	
	// userID des Besitzers
	public $userId;
	
	// Datum des Uploads
	public $date;
	
	// Besitzer des Bildes
	public $user;
	
	// Beschreibung zum Bild
	public $desc;
	
	// Kommentarzaehler
	public $commentsCount = 0; 
	
	// alle Kommentare und Kommentarinformationen zu dem Bild
	public $comments = array();
	
}
?>
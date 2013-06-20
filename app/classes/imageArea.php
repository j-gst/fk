<?php namespace classes;
/**
 * Container fuer Bild-Informationen
 * @autor: Gerrit Storm (Kommentare Beate Gericke)
 */
class imageArea
{
    public $titel;
	public $id;
	public $date;
	public $user;
	public $desc;
	public $thumbnail;
	public $imgLink;
	
	// Kommentarzaehler
	public $commentsCount = 0; 
	
	// alle Kommentare und Kommentarinformationen zu dem Bild
	public $comments = array();
	
}
?>
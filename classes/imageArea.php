<?php namespace classes;
/* 
 * @autor: Gerrit Storm (Kommentare Beate Gericke)
 * 
 * Erstellung der Klasse imageArea von der Ojekte erzeugt werden koennen
 * Initialisierung von Variablen für die imageArea.
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
	
	public $commentsCount = 0;  // Kommentarzaehler
	
	// alle Kommentare und Kommentarinformationen zu dem Bild
	public $comments = array();
	

}
?>
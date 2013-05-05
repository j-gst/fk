<?php namespace classes;
/* ?? warum hier gesondert?
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
	
	public $commentsCount = 0;
	
	// alle Kommentare und Kommentarinformationnen zu dem Bild
	public $comments = array();
	

}
?>
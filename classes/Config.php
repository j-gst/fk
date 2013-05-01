<?php namespace classes;
/*
 * Hier sind Konfigurationsdaten gepeichert
 */
class Config{

/*
 * datenbankspezifische Daten
 */
public $DB_host 		= '127.0.0.1';
public $DB_user 		= 'root';
public $DB_pw 			= '';
public $DB_db 			= 'FotoKommentare';
public $DB_showErrors  =  true;



public $showImgNum = 2; //legt die Anzahl der pro Seite gezeigten Bilder fest

	
	
	
	
	
	
	/*
	 * ?? gibt die aktuelle Konfigration als Objekt zurück ??
	 */
	public static function getInstance(){
		return new \classes\Config();
	}



}
?>
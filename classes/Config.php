<?php namespace classes;
/*
 * @autor Gerrit Storm (Kommetare Beate Gericke)
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
public $DB_showErrors  =  false;



public $showImgNum = 5; //legt die Anzahl der pro Seite gezeigten Bilder fest

public $debug = false;
public $imgDir = "images/";	
	
	
	
	
	
	/*
	 * ?? gibt die aktuelle Konfigration als Objekt zurück ??
	 */
	public static function getInstance(){
		return new \classes\Config();
	}



}
?>
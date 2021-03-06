<?php namespace classes;
/**
 * @author Gerrit Storm, Beate Gericke
 * @author Thies Schillhorn: Erweiterungen zum Laden einiger Konfigurationsdaten aus einer XML-Datei
 * Hier sind Konfigurationsdaten gepeichert bzw. werden aus einer Datei geladen
 */
class Config{

    /*
     * datenbankspezifische Daten
     */
    public $DB_host = '127.0.0.1';
    public $DB_user = 'root';
    public $DB_port = '';
    public $DB_pw = '';
    public $DB_db = 'FotoKommentare';
    public $DB_showErrors = false;



    public $showImgNum = 5; //legt die Anzahl der pro Seite gezeigten Bilder fest
    public $debug = false; // Debug-Output?
    public $title = "Foto-Kommentare";
    public $categories = array();
    public $impressum = "...";
    public $mymail = "beispiel@beispielfirma.de";

    public $userList = array();


    /**
     * Lade eine existierende xml-Konfigurationsdatei.
     * Verwende ggflls Standardwerte, wenn nichts angegeben ist.
     */
    public function __construct(){
        /**
         * Den Pfad zu der XML Datei mit einschliesslich den Dateinamen
         */
        $confFile=APP_DIR.'config/config.xml';
       
       
        
        if (@file_exists($confFile)!== false) {
            /**
             * Einlesen der XML-Konfigurationsdatei und speichern
             * als Objekt in der Variable $confXml.
             */
            $confXml = simplexml_load_file($confFile);
          
            /**
             * Pruefen ob in der Variable $confXml ein Titel vorhanden ist.
             */
            if (isset($confXml->titel)==true){
                $this->title = $confXml->titel;
            }
            /**
             * Verwendung evtl vordefinierter Kategorien
             */
            $myCats = $confXml->kategorien;
            foreach($myCats as $kateg){
                $myCat = array();
                foreach ($kateg as $key =>$value){
                    $myCat['id'] = $value->id;
                    $myCat['name'] = $value->name;
                    $myCat['comment'] = $value->comment;
                    $id = $myCat['id'];
                    $this->categories[(string)$id] = $myCat;
                }
            }
            /**
             * Datenbankverbindungsparameter
             */
            if (isset($confXml->dbserver)==true){
                if (isset($confCml->dbserver->host)){
                    $DB_host = $confCml->dbserver->host;
                }
                if (isset($confCml->dbserver->user)){
                    $DB_user = $confCml->dbserver->user;
                }
                if (isset($confCml->dbserver->port)){
                    $DB_port = $confCml->dbserver->port;
                }
                if (isset($confCml->dbserver->password)){
                    $DB_pw = $confCml->dbserver->password;
                }
                if (isset($confCml->dbserver->dbname)){
                    $DB_db = $confCml->dbserver->dbname;
                }
            }
            if (isset($confXml->impressum)){
                $this->impressum = $confXml->impressum;
            }
            if (isset($confXml->kontakt)){
                $this->mymail = $confXml->kontakt;
            }

        } else {
            /**
             * Falls die Konfigurationsdatei nicht geoeffnet werden konnte,
             * muessen standardwerte verwendet werden.
             */
            echo '<br>'. "Es konnte keine Konfigurationsdatei gefunden werden!".'<br>';
        }
         $this->getUserList();
    }//construct
    
    /**
     * Benutzer Liste fuer die Filterung im Hauptmenue
     */
    private function getUserList(){
        
        $db = new \classes\MysqlDB( $this->DB_host, $this->DB_user,
                                   $this->DB_pw, $this->DB_db,$this->DB_showErrors);
        $q = 'SELECT UserName, Id FROM FK_User ORDER BY UserName';

        $users = $db->query_array($q);
        foreach($users as $user){
            $this->userList[]= $user['UserName'];
        }
        
        //var_dump($users);
    }//getUserList
}
?>

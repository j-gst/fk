<?php namespace controllers;
/**
 * Abstrakte Klasse Controller, von der die einzelnen Seiten-Controller erben.
 * statische Methode getInstance()
 * istanziert einen Controller anhand des REQUEST Parameters "page"
 *
 * @author: Gerrit Storm
 */
abstract class Controller
{



//Attribute eines Controllers

    // anzeige variabler Inhalte in den Templates
    protected $displayData = array();
    // Datenbank Objekt
    protected $db = null;
    // Konfigurations Objekt
    protected $conf = null;
    // User Objekt
    protected $user = null;
    //Fehlermeldung
    protected $errorMsg = "";

//Konstruktor

    /**
     * Bei der Instanzierung werden die benoetigten Objekte uebergeben und zugewiesen
     * @param unknown_type $user
     * @param unknown_type $db
     * @param unknown_type $config
     * @param unknown_type $errorMsg
     */
    public function  __construct($user, $db , $config, $errorMsg = ""){
        $this->user = $user;
        $this->db = $db;
        $this->conf = $config;
        $this->errorMsg = $errorMsg;
    }

    
//getInstance()

    /**
     * Es wird eine Instanz des angeforderten Conrollers ($_REQUEST['page']) zurueckgegeben
     * Wird kein Conroller angefordert, wird der Main Controller zurueckgegeben
     * Bei einem Fehler, oder fehlenden Rechten wird der Error-Controller zurueckgegeben
     */
    static public function getInstance()
    {
        $returnController = null;
        $controller = '';
        $target = "";


//Instanzierung der benoetigten Objekte
        $conf = new \classes\Config();
        $db = new \classes\MysqlDB($conf->DB_host, $conf->DB_user,
                                   $conf->DB_pw, $conf->DB_db,$conf->DB_showErrors);
        
        $user = new \classes\User($db);
         
// Pruefen auf Fehler bei der Verbindung zur Datenbank
        // Bei einem Fehler -> return Error-Controller
        $dbErrors = $db->getErrorList();
        if (isset($dbErrors[0]["no"]) && $dbErrors[0]["no"] > 0) {
            return new \controllers\Error($user, null, null, "Datenbankfehler!");
        }


// Ist eine Seite angegeben? - Sonst Hauptseite.
        if(isset($_REQUEST['page'])){
            $target = $_REQUEST['page'];
        }else{
            // Hauptseite
            $target = "main";
        }


//Auswahl des Seiten-Controllers
        $errorMsg = "";
        $map = new \classes\ControllerMap();
        
        // Pruefen auf Existenz des Controllers und Rechte des Benutzers
        if($c = $map->check($user, $target, $errorMsg)){
            $controller = "\controllers\\".$c;
        }else{
            $controller = "\controllers\Error";
        }


        // Pruefen ob die Datei existiert und Instanzieren des Controllers
        if ( class_exists($controller , true) ){
            $returnController = new $controller($user, $db, $conf,  $errorMsg);
        }
        else {
            $errorMsg = "Diese Seite existiert nicht!";
            $returnController = new \controllers\Error($user, null, null, $errorMsg);
        }
        return $returnController;

    }// getInstance()

    /**
     * Abstrakte Funktion, die in jedem Controller implementiert werden muss.
     * Sie dient als Einstiegspunkt.
     * @abstract
     */
    public abstract function run();


    
    /**
    * Zuweisen der in den Tempates benoetigten Variablen
    * Anzeige des Templates
    * @param String $contentTpl Template Name
    */
    protected function display($contentTpl){
        $displayData = $this->displayData;
        $user = $this->user;
        include_once APP_DIR."templates/main.php";
    } //display()

    /**
     * Pruefen ob die in $rights uebergebenen Rechte alle beim User vorhanden sind
     * Wenn nicht: Anzeige Fehlerseite und Beenden
     * @param array $rights Rechte, die geprueft werden
     */
    protected function redirectOnInsufficientRights(array $rights){
        foreach ($rights as $right){
            if( ! $this->user->checkRight($right) ){
                $this->display("insufficient_rights");
                exit;
            }
        }
    }// redirectOnInsufficientRights()

} ?>
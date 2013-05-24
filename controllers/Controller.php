<?php namespace controllers;
abstract class Controller //Abstrakte Klasse Controller, von der die einzelnen Controller erben. die Controller beinhalten die dynamischen Funktionalitäten.
{
  

	/*
	 * Initialisierung der benötigten Variablen.
	 */
	protected $displayData = array();
	protected $db = null;
	protected $conf = null;
	protected $user = null;	
    protected  $errorMsg = "Fehler";
  
/*
 * ?? Pfeile verwirren mich. Hier geht es um die DB, was passiert hier?
 */
 public function  __construct($user, $db , $config, $errorMsg = "")
  {
	$this->user = $user;	
	$this->db = $db;
    $this->conf = $config;
	$this->errorMsg = $errorMsg;
  }


/*
 * ?? Die Methode ist mir noch nicht ganz klar
 * Sie erscahfft ein Objekt von Controller?
 */
  static function getInstance($target = null)
  {
		$errorMsg = "";
		$conf = new \classes\Config(); // ?? Variable conf wird belegt. new/classes/Config ist ein Pfas, aber Config() eine Methode?? ruft es die Klasse auf??
		
        $db = new \classes\MysqlDB($conf->DB_host, $conf->DB_user, // ?? da ich die Variable conf schon nicht ganz verstehe, hier auch nur Bahnhof ??
		                           $conf->DB_pw, $conf->DB_db, $conf->DB_showErrors); // ??  Die Pfeile verwirren mich generell noch ??
 	  
	    // gibt es einen Datenbankfehler?
		$dbErrors = $db->getErrorList();
		if (isset($dbErrors[0]["no"]) && $dbErrors[0]["no"] > 0) {
			// Fehlerseite
			$target = "error";
			// Fehlermeldung
			$errorMsg = "Datenbankfehler!";
		}
		
		$user = new \classes\User($db); // User Objekt
		
		
		
		
		$returnObj = null;
		
		
		if($target === null && isset($_REQUEST['page'])){ // ?? drei === ??
			$target = $_REQUEST['page'];
		}
		
		/*
		 * Je nach Angabe wird der Parameter mitgegeben und die Variable controller belegt
		 * wenn eine Klasse mit dem entsprechenden anmen existiert, wird von dieser ein Objekt erzeugt und zurückgeliefert
		 */
		$contrBase = '\\controllers\\';
		$controller = '';
		if(isset($target)){
			switch($target){
				case ('main'):
					$controller = 'Main';
					break;			
				case ('upload'):
					$controller = 'Upload';
					break;
				case ('register'):
					$controller = 'Register';
					break;
				case ('imgdetails'):
					$controller = 'Imgdetails';
					break;
				case ('search'):
					$controller = 'Search';
					break;
				case ('login'):
					$controller = 'Login';
					break;
				case ('gallery'):
					$controller = 'Gallery';
					break;
				case ('error'):
					$controller = 'Error';
					break;					
				default:
				    $errorMsg = "Diese Seite existiert leider nicht!";
					$controller = 'Error';
				break;

			}
		}else{
			$controller = 'Main';
		}
		$controller = $contrBase . $controller;
    if ( class_exists($controller , true) )
    {
      $returnObj = new $controller($user, $db, $conf,  $errorMsg);
    } 
    else 
    { 
	  $errorMsg = "Diese Seite existiert leider nicht!";
      $returnObj = new $contrBase . Error($user, null, null, $errorMsg);
    }
	return $returnObj;
 
  }
  
  /*
   * Abstrakte Funktion, die in jedem Controller implementiert werden muss.
   */
  public abstract function run();
  
  /*
   * Funktion, die einen Parameter erwartet. 
   * die Variable displaydata vird mit einem Array belegt
   * Die main.php wird eingebunden, dort werden weitere Angaben gemacht
   */
  protected function display($contentTpl){
    $displayData = $this->displayData;
    $user = $this->user;	
	include "../templates/main.php";
  
  }
  
/*
 * Funktion erhält einen Parameter, über den eine Instanz eines Controllers erstellt und die Run Methode dieses Controllers aufgerufen werden kann
 */
 protected function redirectOnInsufficientRights(array $rights){
	foreach ($rights as $right){
		if( ! $this->user->checkRight($right) ){
			$this->display("insufficient_rights"); 
			exit;
		}
	}
 } 
 
 
  

} ?>





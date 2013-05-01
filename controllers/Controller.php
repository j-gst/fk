<?php namespace controllers;
abstract class Controller //Abstrakte Klasse Controller, von der die einzelnen Controller erben. die Controller beinhalten die dynamischen Funktionalitäten.
{
  

	/*
	 * Initialisierung der benötigten Variablen.
	 */
	protected $displayData = array();
	protected $db = null;
	protected $conf = null;
    protected static $errorMsg = "Fehler";
  
/*
 * ?? Pfeile verwirren mich. Hier geht es um die DB, was passiert hier?
 */
 public function  __construct($db , $config)
  {
	$this->db = $db;
    $this->conf = $config;
  }


/*
 * ?? Die Methode ist mir noch nicht ganz klar
 * Sie erscahfft ein Objekt von Controller?
 */
  static function getInstance($target = null)
  {
		
		$conf = new \classes\Config(); // ?? Variable conf wird belegt. new/classes/Config ist ein Pfas, aber Config() eine Methode?? ruft es die Klasse auf??
		
        $db = new \classes\MysqlDB($conf->DB_host, $conf->DB_user, // ?? da ich die Variable conf schon nicht ganz verstehe, hier auch nur Bahnhof ??
		                           $conf->DB_pw, $conf->DB_db, $conf->DB_showErrors); // ??  Die Pfeile verwirren mich generell noch ??
 	  
		
		$returnObj = null;
		if($target === null && isset($_REQUEST['page'])){ // ?? drei === ??
			$target = $_REQUEST['page'];
		}
		
		/*
		 * Je nach Angabe wird der Parameter mitgegeben und die Variable controller belegt
		 * wenn eine Klasse mit dem entsprechenden anmen existiert, wird von dieser ein Objekt erzeugt und zurückgeliefert
		 */
		$controller = '';
		if(isset($target)){
			switch($target){
				case ('main'):
					$controller = '\controllers\Main';
					break;			
				case ('upload'):
					$controller = '\controllers\Upload';
					break;
				case ('register'):
					$controller = '\controllers\Register';
					break;
				default:
				    //$self::errorMsg = "Diese Seite existiert leider nicht!";
					$controller = '\controllers\Error';
				break;

			}
		}else{
			$controller = '\controllers\Main';
		}
		
    if ( class_exists($controller , true) )
    {
      $returnObj = new $controller($db, $conf);
    } 
    else 
    { 
	  $this->errorMsg = "Diese Seite existiert leider nicht!";
      $returnObj = new \controllers\Error();
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
	include "../templates/main.php";
  
  }
  
/*
 * Funktion erhält einen Parameter, über den eine Instanz eines Controllers erstellt und die Run Methode dieses Controllers aufgerufen werden kann
 */
 protected function redirect($target){
	\controllers\Controller::getInstance($target)->run();
	exit;
 } 

   
  
  

} ?>





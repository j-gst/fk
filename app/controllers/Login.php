<?php
namespace controllers;

/**
 * Login Controller
 * zustaendig fuer login und logout
 * @author: Gerrit Storm
 */
class Login extends Controller
{                   
    /**
     * die Methode run() wird implementiert
     * Einstiegspunkt in den Controller
     */
	public function run(){
	    
//login
		if( isset($_REQUEST['action']) && $_REQUEST['action'] === 'login' ){
			if($this->login()){
				$_SESSION['loginMsg'] = "";
			}else{
				$_SESSION['loginMsg'] = "Login fehlgeschlagen!";
			}
//logout
		}elseif ( isset($_REQUEST['action']) && $_REQUEST['action'] === 'logout' ){
			$this->logout();
		}
		header( 'Location: index.php' ) ;

	} // run()

	/**
	 * Laed User Daten aus der DB
	 * Prueft Username/Passwort
	 * Schreibt Userdaten in die Session
	 * @return: boolean
	 */
	public function login(){
	    
	     /**
	      * neu Instanzierung von Config Und DB Objekt, da
	      * auf zwei MAC-Rechnern ein noch ungelaerter Fehler auftrat, der so nicht auftritt
	      * @todo Fehlerursache klaeren.
	      */
	     $conf = new \classes\Config();
         $db = new \classes\MysqlDB($conf->DB_host, $conf->DB_user,
                                   $conf->DB_pw, $conf->DB_db,$conf->DB_showErrors);
	    
	    
	    
		if( isset($_REQUEST['username']) && isset($_REQUEST['password'])){
			$q = sprintf('SELECT Id, UserName, EMailAdress, FirstName, LastName, Password, UserState, Role
		                 FROM FK_User WHERE UserName = "%s" LIMIT 1', $_REQUEST['username']);

			// User aus der DB laden
			$user = $db->query_array($q);
			// User gefunden ?
			if($user && count($user) > 0){
				// PW korrekt ?
				if($this->checkPassword($user[0]['Password'] , $_REQUEST['password'])){
				$_SESSION['username'] = $user[0]['UserName'] ;
				$_SESSION['id'] = $user[0]['Id'] ;
				$_SESSION['role'] = $user[0]['Role'] ;
				$_SESSION['email'] = $user[0]['EMailAdress'] ;
				$_SESSION['firstname'] = $user[0]['FirstName'] ;
				$_SESSION['lastname'] = $user[0]['LastName'] ;
				$_SESSION['isLoggedIn'] = true;
				// erfolgreich
				return true;
				}
			}
		}
		// nicht erfolgreich
		return false;
	} // login()

	/**
	 * Session wird geloescht
	 */
	public function logout(){
		unset($_SESSION);
		session_destroy();
	}

	/**
	 * Test ob das Passwort korrekt ist
	 * @return: boolean
	 * @todo implement salt
	 */
	public function checkPassword($hash , $pass){
		return (sha1($pass) === $hash);
	}
} ?>
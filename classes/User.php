<?php namespace classes;
/*
 * Das User Objekt speichert alle relevanten Daten zum User
 * @Author: Gerrit Storm
 */
class User{

/*
 userspezifische Daten
 */
private $username = '';
private $firstname = '';
private $lastname = '';
private $id = '';
private $email = '';
private $role = '';
private $isLoggedIn = false;
private $rights = array();

	
	/*
	* Prueft ob in der Session alle Userdaten vorhanden sind
	* wenn ja, werden diese uebernommen
	* sonst ist der User ein Gast 
	*/
	public function __construct(){
	
	if(isset($_SESSION['username']) 
			&& isset($_SESSION['id']) 
			&& isset($_SESSION['role']) 
			&& isset($_SESSION['email']) 
			&& isset($_SESSION['firstname']) 		
			&& isset($_SESSION['lastname']) 	
            && isset($_SESSION['lastname']) 			
		){
		$this->username = $_SESSION['username'];
		$this->id = $_SESSION['id'];
		$this->role = $_SESSION['role'];
		$this->email = $_SESSION['email'];
		$this->firstname = $_SESSION['firstname'];
		$this->lastname = $_SESSION['lastname'];	
		$this->isLoggedIn = $_SESSION['isLoggedIn']?$_SESSION['isLoggedIn']:false;	
	} else {
		$this->username = 'gast';
		
		// wichtig fuer die DB
		$this->id = NULL;
		
		$this->role = 'guest';
	}
	
	

	$this->getRights();
	
	}// __construct()
	
	
	
    /*
	* der $rights Array wird befuellt mit allen Rechten des Users
	*/
	private  function getRights(){
		
	}
	

	/*
	* getter Methoden
	*/
	public function getUsername() {
		return $this->username;
    }
	
	public function getId() {
		return $this->id;
    }
	
	public function isLoggedIn() {
		return $this->isLoggedIn;
    }
	




}
?>
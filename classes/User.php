<?php namespace classes;
/*
 * Hier sind Konfigurationsdaten gepeichert
 */
class User{

/*
 * datenbankspezifische Daten
 */
private $username = '';
private $firstname = '';
private $lastname = '';
private $id = '';
private $email = '';
private $role = '';
private $isLoggedIn = false;
private $rights = array();

	
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
		$this->id = NULL;
		$this->role = 'guest';
	}
	
	$this->getRights();
	
	}// __construct()
	
	
	// magic get methode:
	// gibt z.B. mit $user->get('username') den Wert des private Atributes $user->username
	public function getUsername() {
		return $this->username;
    }
	public function getId() {
		return $this->id;
    }
	public function isLoggedIn() {
		return $this->isLoggedIn;
    }
	private  function getRights(){
		
	}



}
?>
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
private $db;
	
	/*
	* Prueft ob in der Session alle Userdaten vorhanden sind
	* wenn ja, werden diese uebernommen
	* sonst ist der User ein Gast 
	*/
	public function __construct($db){
	$this->db = $db;
	
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
	
	}//__construct()

	
	
	
/**
* der $rights Array wird befuellt mit allen Rechten des Users
*/
private  function getRights(){
	$q = sprintf(
		'SELECT FK_Right.Name    
		FROM FK_Right 
			JOIN FK_Right_Role ON FK_Right.Id = FK_Right_Role.RightId 
			JOIN FK_Role ON FK_Role.Id = FK_Right_Role.RoleId 
		WHERE FK_Role.Id = %d', 
		$this->role);

	
$rights = $this->db->query_array($q);

foreach($rights as $right){
	$this->rights[] = $right['Name'];
}

}// getRights()
	
public function checkRight($right){
   return in_array($right,$this->rights);
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
	




}//class
?>
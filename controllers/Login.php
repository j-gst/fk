<?php
namespace controllers;
/*
 * Klasse Error, erbt von der Klasse Controller
 */
class Login extends Controller
{
  

   /*
    * die Funktion run wird implementiert
    */
   public function run(){
	if( isset($_REQUEST['action']) && $_REQUEST['action'] === 'login' ){
		if($this->login()){
			$_SESSION['loginMsg'] = "";
		}else{
			$_SESSION['loginMsg'] = "Login fehlgeschlagen!";
		}
	}elseif ( isset($_REQUEST['action']) && $_REQUEST['action'] === 'logout' ){
        $this->logout();
    }
	header( 'Location: index.php' ) ;
  
  } // run()
  
  public function login(){  
		if( isset($_REQUEST['username']) && isset($_REQUEST['password'])){
		   $q = sprintf('SELECT Id, UserName, EMailAdress, FirstName, LastName, Password, UserState, Role 
		                 FROM FK_User WHERE UserName = "%s" LIMIT 1', $_REQUEST['username']);

	       // alle Bildinformationen aus der DB laden
			$user = $this->db->query_array($q); 
			if($user && count($user) > 0){ 	
				if($this->checkPassword($user[0]['Password'] , $_REQUEST['password'])){
					$_SESSION['username'] = $user[0]['UserName'] ;
					$_SESSION['id'] = $user[0]['Id'] ;
					$_SESSION['role'] = $user[0]['Role'] ;
					$_SESSION['email'] = $user[0]['EMailAdress'] ;
					$_SESSION['firstname'] = $user[0]['FirstName'] ;
					$_SESSION['lastname'] = $user[0]['LastName'] ;	
			    	$_SESSION['isLoggedIn'] = true;
				return true;
					
				}
			}
			
		}
  
    return false;
  } // login()
  
  public function logout(){
		unset($_SESSION);
		session_destroy();
  } 
  
  public function checkPassword($hash , $pass){

		return sha1($pass) === $hash;
  } 
  
  
  
} ?>
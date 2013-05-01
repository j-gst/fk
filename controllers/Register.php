<?php
namespace controllers;
/*]
 * Klasse Register
 */
class Register extends Controller
{
	
	/*
	 * die Funktion run wird implementiert
	 */
	public function run(){
	if(isset($_REQUEST['action'])){
		switch ($_REQUEST['action']) {
			case 'save':
				$reg = $this->saveUserToDB();
				/*if($reg === true){
					//hier eine Weiterleitung, die dem Nutzer sagt, dass die Registrierung erfolgreich war
					// wenn Bestätigung per mail implementiert wird, auch der Hinweis auf die Mail.
				}*/
				
				break;
			
			default:
				
				break;
		}//switch
	}//if
	
	
			$this->display("register");
	}//run
	
	/*
	 * Die eingegebenen Daten wedern gespeichert
	 * ?? wo ist das Passwort??
	 */
	private function saveUserToDB(){
		if(isset($_REQUEST)){
			$insertArr = array( 
			'NamePart1' => $_REQUEST['lastname'],
			'NamePart2' => $_REQUEST['firstname'],
			'UserName' => $_REQUEST['username'],
			'UserState' => '1',
			 );
			 $insertID = $this->db->insert('FK_User', $insertArr, '???');
		}
	}//saveUserToDB
	
}	
?>
	
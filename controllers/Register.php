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
	//var_dump($_REQUEST);
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
			/*
			 * Pr�fen, ob alle Felder ausgef�llt wurden
			 * hierf�r ein Fehler-Array anlegen um gefundene Fehler speichern zu k�nnen
			 * 
			 */
			$errors=array();
			if(!isset($_REQUEST['lastname'],
					$_REQUEST['firstname'],
					$_REQUEST['username'],
					$_REQUEST['email'],
					$_REQUEST['password'],
					$_REQUEST['confirmpw']
					)){
				//$errors[]="Bitte f�llen Sie alle Felder aus.";
			}else{
				/*
				 * Pr�fung der Felder auf ihre G�ltigkeit
				 * Keine Garantie, dass alle m�glichen F�lle der Fehlerhaften Eingabe gepr�ft werden
				 * vorerst implementiert, was mir einfiel und was meine Internetrecherche zu typischen Registrieren-Formularenhergab
				 */
				/*
				 * Die n�tigen Daten aus der Datenbank auslesen, 
				 * die zu Vergleichen herangezogen werden m�ssen
				 * in unserem Fall, Usernamen, die einmalig sein m�ssen
				 * Mailadressen, die einmalig sein m�ssen
				 */
				$userAlias=array();
				$mailAddys=array();
				$dbQ="SELECT UserName, EMailAddress FROM FK_USER";
				// alle Bildinformationen aus der DB laden
				  $dbO = $this->db->query_array($dbQ); 

				  //Eintragen der aus der DB ausgelesenen Daten in daf�r vorbereitete Arrays
				 
				 
###  mysql_fetch_assoc() brauchst du hier nicht -> $db0 kommt schon als array - jedes Element
### ist wieder ein array mit zwei Elementen -> ['UserName'] und ['EMailAddress']
				// while ($row=mysql_fetch_assoc($dbO)){
				
### daher hier foreach
				if($dbO !== false) foreach($dbO as $row){
					$userAlias[]=$row['UserName'];
					$mailAddys[]=$row['EMailAddress'];
				}//end while
				//wurde ein Nachname eingegeben?
				if(trim($_REQUEST['lastname'])==''){
					$errors[]="Bitte geben Sie Ihren Nachnamen ein.";
				}
###  firstname nicht vorname
				if(trim($_REQUEST['firstname'])==''){
					$errors[]="Bitte geben Sie Ihren Vornamen ein.";
				}
				if(trim($_REQUEST['username'])==''){
					$errors[]="Bitte geben Sie einen Usernamen ein.";
				}
				elseif (!preg_match('/^\w+$/', trim($_REQUEST['username']))){
					$errors[]="Benutzen Sie bitte nur Buchstaben, Zahlen und Unterstrich.";
				}
				//pr�fen, ob der gew�nschte Username bereits vergeben wurde
				elseif(in_array(trim($_REQUEST['username']), $userAlias)){
					$errors[]="Der gew�hlte Username ist bereits vergeben.";
				}
				//pr�fen, ob ein Passwort gew�hlt wurde
				if(trim($_REQUEST['password'])==''){
					$errors[]="Bitte geben Sie ein Passwort ein.";
				}
				//elseif(strlen(trim($_REQUEST['password']))<6)
				//	$errors[]="Das von Ihnen gew�hlte Passwort ist zu kurz. Bitte mindestens 6 Zeichen.";
				
				//pr�fen, ob das Passwort wiederholt wurde
				if(trim($_REQUEST['confirmpw'])==''){
					$errors[]="Bitte wiederholen Sie Ihr gew�nschtes Passwort.";
				}
				//pr�fen, ob das Passwort und die Wiederholung voneinander abweichen
				elseif (trim($_REQUEST['password'])!=trim($_REQUEST['confirmpw'])){
					$errors[]="Passwort und Passwortwiederholung stimmen nicht �berein. Bitte versuchen Sie es erneut.";
				}
					//pr�fen, ob eine Mail-Adresse eingegeben wurdetrim
				if(trim($_REQUEST['email'])==''){
					$errors[]="Bitte geben Sie eine E-Maiadresse ein.";
				}elseif(!preg_match('�^[\w\.-]+@[\w\.-]+\.[\w]{2,4}$�', trim($_REQUEST['email']))){
### [] vergessen
					$errors[]="Die eingegebene Email hat ein ung�ltiges Format.";
				}elseif(in_array(trim($_REQUEST['email']), $mailAddys)){
					$errors[]="Die von Ihnene angegebene E-Mailadresse wird bereits genutzt.";
				}
		}//end else
			
			if(count($errors) > 0){
				echo "Leider konnte Ihr Account nicht erstellt werden. <br />
					 Bitte lesen Sie die folgenden Anmerkungen und versuchen Sie es erenut.<br />";
###  �errors statt $errors das ist ohne Syntaxhighlighting mal echt schwer zu sehen
					foreach($errors as $error)
						echo $error."<br />";
			}else{
	

###	 ok die Variavble $password gab es noch nicht. Die muessen wir erst machen:
### erstmal ganz einfach per sha1()
	$passwordHash = sha1($_REQUEST['password']);
			$insertArr = array( 
			'LastName' => $_REQUEST['lastname'],
			'FirstName' => $_REQUEST['firstname'],
			'UserName' => $_REQUEST['username'],
			'EMailAdress' => $_REQUEST['email'],
			'Password' => $passwordHash,
			'Role' => 'user',			
			'UserState' => 0,
			 );

	$insertID = $this->db->insert('FK_User', $insertArr, 'ssssssd');	
### so bekommst du die MYSQL Fehlermeldungen zu sehen
	if($insertID === false){
		var_dump( $this->db->getErrorList() );
	}

### hier haben wir das Problem, das wir schon gesehen hatten das feld in der DB ist EMailAdress  mit einem d
### sollten wir mit anpassen, wen wir die DB das naechste mal updaten
 		
		}// else
	
	
	// weiter kam ich bisher nicht
	
############################################
/*
         ich wuerde $errors als assoziatives  array machen etwa so:
		$errors['password']="Bitte geben Sie ein Passwort ein.";
		$errors['confirmpw']="Bitte wiederholen Sie Ihr gew�nschtes Passwort.";
		
		alle werte werden am Anfang mit leerstring initialisiert: $errors['password'] = '';
	
	         dann wird $errors in $this->displayData gespeichert, damit man im template darauf zugreifen kann
			z.B.  $this->displayData ['formerrors'] = $errors;  
	         
			 
		$this->displayData ['formerrors']  wird dann im template immer ( daher wichtig erst mit leerstring initialisieren	)  mit angezeigt
		etwa so:
	       <input id="username" type="text" name="username" />  <p class="formerror"> <?php echo  $displayData ['formerrors'] ['username'] ; ?> </p>
		  ist ein Fehler vorhanden, wird er neben dem richtigen Feld angezeigt
		  dann noch ne css klasse f�r formerror - irgendwie rot zB
	
	
	und dann umleiten auf Hauptseite??? , 
	
	
	
*/	
#############################################
	
	} //	if(isset($_REQUEST))

	}//saveUserToDB

/**
 * Quellen
 * (Hauptquelle bislang) http://www.mywebsolution.de/workshops/2/page_2/show_PHP-Loginsystem---User-registrieren.html
 * http://webmasterparadies.de/scripting/php/170-registrierung-mit-aktivierungs-mail.html
 * http://www.tutorials.de/php/288386-php-login-mit-registrierung.html
 */
} ?>
<?php
namespace controllers;
/**
 * Controller Register
 * Steuerung des Registieren Vorganges
 * Fehlerpruefung und Anlegen des neuen Benutzers in der DB
 * @author Beate Gericke (Hauptautorin), Gerrit Storm
 */
class Register extends Controller
{

    /**
     * die Methode run() wird implementiert
     * Einstiegspunkt in den Controller
     */
    public function run(){
        $this->displayData['registered'] = false;
        //Initialisierung des Errors-Arrays als assoziatives Array
        $errors=array("lastname" => "", "firstname" => "", "username" => "", "usernameCharacters" => "", "usernameUsed" => "", "password" => "",
					"passwordConfirm" => "", "passwordConfirmFalse" => "", "email" => "", "emailFalse" => "", "emailUsed" => "");
        //Speicherung des Fehler-Arrays zum Zugriff des aktuellen Objektes, so kann das Template es nutzen
        $this->displayData['formerrors'] = $errors;

        if(isset($_REQUEST['action'])){
            switch ($_REQUEST['action']) {
                case 'save':
                    $reg = $this->saveUserToDB();
                    if($reg !== false){
                        //hier eine Weiterleitung, die dem Nutzer sagt, dass die Registrierung erfolgreich war
                        // wenn Bestaetigung per mail implementiert wird, auch der Hinweis auf die Mail.
                        $this->displayData['registered'] = true;
                    }
                    break;
                default:
                    // do nothing
                    break;
            }//switch
        }//if
        $this->display("register");
    }//run

    /**
     * Die eingegebenen Daten wedern gespeichert
     */
    private function saveUserToDB(){
        if(isset($_REQUEST)){
            /*
             * Pruefen, ob alle Felder ausgefuellt wurden
             * hierfuer ein Fehler-Array anlegen um gefundene Fehler speichern zu koennen
             *
             */
             
            //Initialisierung des Errors-Arrays als assoziatives Array
            $errors=array("lastname" => "", "firstname" => "", "username" => "", "usernameCharacters" => "", "usernameUsed" => "", "password" => "",
					"passwordConfirm" => "", "passwordConfirmFalse" => "", "email" => "", "emailFalse" => "", "emailUsed" => "");
            //Speicherung des Fehler-Arrays zum Zugriff des aktuellen Objektes, so kann das Template es nutzen
            $this->displayData ['formerrors'] = $errors;

             
            if(isset($_REQUEST['lastname'],
            $_REQUEST['firstname'],
            $_REQUEST['username'],
            $_REQUEST['email'],
            $_REQUEST['password'],
            $_REQUEST['confirmpw']
            ))
            {
                 
                /*
                 * Pruefung der Felder auf ihre Gueltigkeit
                 * Keine Garantie, dass alle moeglichen Faelle der Fehlerhaften Eingabe geprueft werden
                 * vorerst implementiert, was mir einfiel und was meine Internetrecherche zu typischen Registrieren-Formularenhergab
                 * Die noetigen Daten aus der Datenbank auslesen,
                 * die zu Vergleichen herangezogen werden muessen
                 * in unserem Fall, Usernamen, die einmalig sein muessen
                 * Mailadressen, die einmalig sein muessen
                 */
                $userAlias=array();
                $mailAddys=array();
                $dbQ="SELECT UserName, EMailAdress FROM FK_USER";
                // alle Bildinformationen aus der DB laden
                $dbO = $this->db->query_array($dbQ);

                //Eintragen der aus der DB ausgelesenen Daten in dafuer vorbereitete Arrays
                 

                if($dbO !== false) foreach($dbO as $row){
                    $userAlias[]=$row['UserName'];
                    $mailAddys[]=$row['EMailAdress'];
                }//end while
                //wurden die geforderten Angaben gemacht?

                $errorCount = 0;

                if(trim($_REQUEST['lastname'])==''){
                    $errors['lastname']="Bitte geben Sie Ihren Nachnamen ein.";
                    $errorCount++;
                }
                 
                if(trim($_REQUEST['firstname'])==''){
                    $errors['firstname']="Bitte geben Sie Ihren Vornamen ein.";
                    $errorCount++;
                }

                if(trim($_REQUEST['username'])==''){
                    $errors['username']="Bitte geben Sie einen Usernamen ein.";
                    $errorCount++;
                }

                elseif (!preg_match('/^\w+$/', trim($_REQUEST['username']))){
                    $errors['usernameCharacters']="Benutzen Sie bitte nur Buchstaben, Zahlen und Unterstrich.";
                    $errorCount++;
                }

                //pruefen, ob der gewuenschte Username bereits vergeben wurde
                elseif(in_array(trim($_REQUEST['username']), $userAlias)){
                    $errors['usernameUsed']="Der gew&auml;hlte Username ist bereits vergeben.";
                    $errorCount++;
                }
                //pruefen, ob ein Passwort gewaehlt wurde
                if(trim($_REQUEST['password'])==''){
                    $errors['password']="Bitte geben Sie ein Passwort ein.";
                    $errorCount++;
                }
                //elseif(strlen(trim($_REQUEST['password']))<6)
                //	$errors[]="Das von Ihnen gewaehlte Passwort ist zu kurz. Bitte mindestens 6 Zeichen."; // wenn das gewarhlte Passwort eine bestimmte Laenge haben muss, Pruefung

                //pruefen, ob das Passwort wiederholt wurde
                if(trim($_REQUEST['confirmpw'])==''){
                    $errors['passwordConfirm']="Bitte wiederholen Sie Ihr gew&uuml;nschtes Passwort.";
                    $errorCount++;
                }

                //pruefen, ob das Passwort und die Wiederholung voneinander abweichen
                elseif (trim($_REQUEST['password'])!=trim($_REQUEST['confirmpw'])){
                    $errors['passwordConfirmFalse']="Passwort und Passwortwiederholung stimmen nicht &uuml;berein. Bitte versuchen Sie es erneut.";
                    $errorCount++;
                }

                //pruefen, ob eine Mail-Adresse eingegeben wurde, trim zum beschneiden der Zeichenkette
                if(trim($_REQUEST['email'])==''){
                    $errors['email']="Bitte geben Sie eine E-Maiadresse ein.";
                    $errorCount++;
                }elseif(!preg_match('#^[\w\.-]+@[\w\.-]+\.[\w]{2,4}$#', trim($_REQUEST['email']))){
                    $errors['emailFalse']="Die eingegebene Email hat ein ung&uuml;ltiges Format.";
                    $errorCount++;
                }elseif(in_array(trim($_REQUEST['email']), $mailAddys)){
                    $errors['emailUsed']="Die von Ihnene angegebene E-Mailadresse wird bereits genutzt.";
                    $errorCount++;
                }
            }//end if $_REQUEST

            if($errorCount > 0){
                $this->displayData['formerrors'] = $errors;
                return false;
            }else{

                // hash Wert aus Passwort
                $passwordHash = sha1($_REQUEST['password']);
                $insertArr = array(
							'LastName' => $_REQUEST['lastname'],
							'FirstName' => $_REQUEST['firstname'],
							'UserName' => $_REQUEST['username'],
							'EMailAdress' => $_REQUEST['email'],
							'Password' => $passwordHash,
							'Role' => 2,			
							'UserState' => 0,
                );

                $insertID = $this->db->insert('FK_User', $insertArr, 'sssssdd');

                if($insertID === false){
                    var_dump( $this->db->getErrorList() );
                }
                return $insertID;
            }// else
        } //	if(isset($_REQUEST))
    }//saveUserToDB
} ?>
<?php
namespace controllers;
/**
 * Klasse Admin
 * Rechteverwaltung
 * Benutzerverwaltung
 * @author: Gerrit Storm
 */
class Admin extends Controller
{

    // mapping von POST Parametern auf Kombinationen Rollen-Rechte
    private $toCheck = array(
	  'guest_view' => array('guest',array('view')),
	  'guest_upload' => array('guest',array('picture_upload')),
	  'guest_comment' => array('guest',array('comment_make')),
	  'user_upload' => array('user',array('picture_upload')),
	  'user_comment' => array('user',array('comment_make')),
	  'user_delete' => array('user',array('comment_delete_own', 'picture_delete_own')), 
	  'tuser_deleteall'=> array('trusted_user',array('comment_delete_all','picture_delete_all')),
    );


    /**
     * die Funktion run wird implementiert
     */
    public function run(){
        $saveMsg = false;
        $subpage = "";

// save Benutzer 
        if(isset($_REQUEST["save_user"])){
            if($this->saveUserToDB()){
                $saveMsg = true;
            }
            $this->getUserListFromDB();
            $subpage = 'user';
        }
//Rechte speichern
        elseif(isset($_REQUEST["save_rights"])){
            $this->saveRightsToDB();
            $this->getRightsValues();
            $subpage = 'rights';
            $saveMsg = true;
        }
// edit Benutzer
        elseif(isset($_REQUEST['id'])){
            if($this->getUserFromDB($_REQUEST['id'])){
                $subpage = 'user_edit';
            }
//Anzeige Rechte
        }elseif( !isset($_REQUEST['m']) || (isset($_REQUEST['m']) && $_REQUEST['m'] == 'r')){
            $this->getRightsValues();
            $subpage = 'rights';
//Anzeige Benutzer            
        } elseif(isset($_REQUEST['m']) && $_REQUEST['m'] == 'u'){
            $subpage = 'user';
            $this->getUserListFromDB();
        }

   
        $this->displayData['saveMsg'] = $saveMsg;
        $this->displayData['subpage'] = $subpage;
        $this->display("admin");
    }// run()



    /**
     * alle User aus DB laden
     */
    private function getUserListFromDB(){
        $q = 'SELECT FK_User.Id, UserName, EMailAdress, FirstName, LastName, UserState, FK_Role.Name AS Role
	FROM FK_User JOIN FK_Role on FK_Role.Id = Role order by FK_User.Id';
        $this->displayData['userlist'] = $this->db->query_array($q);
        $q = 'SELECT Name FROM FK_Role';
        $this->displayData['rolelist'] = $this->db->query_array($q);
    }//getUserListFromDB

    
    /**
     * speziellen Benutzer aus DB laden
     * @param int $id Benutzer-ID
     */
    private function getUserFromDB($id){
        $q = 'SELECT Name FROM FK_Role';
        $this->displayData['rolelist'] = $this->db->query_array($q);
        $q = sprintf('SELECT FK_User.Id, UserName, EMailAdress, FirstName, LastName, UserState, FK_Role.Name AS Role
	FROM FK_User JOIN FK_Role on FK_Role.Id = Role 
	WHERE FK_User.Id = %d LIMIT 1',$id);
        $user = $this->db->query_array($q);

        if($user !== false &&
        (is_array($user) && count($user) != 0) ){
            $this->displayData['user'] = $user[0];
            return true;
        }else{
            return false;
        }

    }//getUserListFromDB()

    /**
     * Rechte zu einer Rolle aus DB laden
     */
    private function getRightsValues(){
        $q = sprintf(
			'SELECT FK_Right_Role.Id,FK_Right.Name AS RightName,FK_Role.Name AS RoleName  
			FROM FK_Right 
				JOIN FK_Right_Role ON FK_Right.Id = FK_Right_Role.RightId 
				JOIN FK_Role ON FK_Role.Id = FK_Right_Role.RoleId WHERE FK_Role.Name != "admin"');
        $roleRights = $this->db->query_array($q);
        foreach($this->toCheck as $key => $check){
            $this->displayData[$key] = '';
            foreach($check[1] as $right){
                foreach($roleRights as $roleRight){
                    //nur wenn der Rollenname passt
                    if($check[0] == $roleRight['RoleName']){
                        $found = false;
                        //alle Rechte durchgehen
                        if($right == $roleRight['RightName']){
                            $this->displayData[$key] = 'checked="checked"';
                        }
                    }
                }
            }//foreach
        }//foreach($this->toCheck as $key => $check) 	
    }//getRightsValues()


    /**
     * Benutzerdaten in DB speichern
     */
    private function saveUserToDB(){
        if(!$_REQUEST['state'] && !$_REQUEST['role'] && !$_REQUEST['id'] ) {
            return false;
        }
        $state = 0;
        if($_REQUEST['state'] == "aktiv"){
            $state = 1;
        }
        $q = sprintf('UPDATE FK_User SET UserState = %d, Role = (SELECT FK_Role.Id FROM FK_Role WHERE Name = "%s")
				WHERE Id = %d',$state, $_REQUEST['role'] , $_REQUEST['id']) ;
        return $this->db->query($q);

    }//saveUserToDB()
    
    
    /**
	 * Rechte zu einer Rolle in DB speichern
     */
    private function saveRightsToDB(){

        // alle zu setzenden Rechte/Rollen
        $toAdd = array();
        // alle zu loeschenden Rechte/Rollen
        $toDelete = array();


        $q = sprintf(
		'SELECT FK_Right_Role.Id,FK_Right.Name AS RightName,FK_Role.Name AS RoleName  
		FROM FK_Right 
			JOIN FK_Right_Role ON FK_Right.Id = FK_Right_Role.RightId 
			JOIN FK_Role ON FK_Role.Id = FK_Right_Role.RoleId WHERE FK_Role.Name != "admin"');
        $roleRights = $this->db->query_array($q);

        	
        //alle Aenderungsoptionen durchgehen
        foreach($this->toCheck as $key => $check){
            //muss ein Recht hinzugefuegt werden?
            if(isset($_REQUEST[$key])){
                //$toAdd[rolle]  als array initialisieren wenn noch nicht existent
                if(!isset($toAdd[$check[0]])){ $toAdd[$check[0]] = array();}
                //alle Rechte fuer diesen Parameter hinzufuegen zu $toAdd[rolle]
                foreach($check[1] as $el){
                    $toAdd[$check[0]][] = $el;
                }
                //alle Kombinationen Rolle-Recht durchgehen
                foreach($roleRights as $roleRight){
                    //nur wenn der Rollenname passt
                    if($check[0] == $roleRight['RoleName']){
                        //alle Rechte durchgehen, die eventuell geaendert werden muessen
                        foreach($check[1] as $k =>  $right){
                            if($right == $roleRight['RightName']){
                                //das Recht mit der entsprechenden Rolle gibt es schon - aus $toAdd loeschen
                                $delKey = array_search($right,$toAdd[$check[0]]);
                                if($delKey !== false) unset($toAdd[$check[0]][$delKey]);
                            }
                        }
                    }
                }
            //muss ein Recht geloescht werden?
            }else{
                if(!isset($toDelete[$check[0]])){ $toDelete[$check[0]]=array();}
                //alle Kombinationen Rolle-Recht durchgehen
                foreach($roleRights as $roleRight){
                    //nur wenn der Rollenname passt
                    if($check[0] == $roleRight['RoleName']){
                        //alle Rechte durchgehen, die eventuell geaendert werden muessen
                        foreach($check[1] as $k =>  $right){
                            if($right == $roleRight['RightName']){
                                $toDelete[$check[0]][] = $right;
                            }
                        }
                    }
                }//foreach($roleRights as $roleRight)
            }//else
        }//foreach($toCheck as $key => $check)

        // add to DB
        foreach($toAdd as $key => $add){
            foreach($add as $right){
                $q = sprintf('INSERT INTO FK_Right_Role (RightId, RoleId)
			      VALUES(
				         (SELECT FK_Right.Id FROM FK_Right WHERE Name = "%s"),
						 (SELECT FK_Role.Id FROM FK_Role WHERE Name = "%s")
						 )',$right, $key) ;
                $this->db->query($q);
            }
        }//foreach
        //delete from DB
        foreach($toDelete as $key => $del){
            foreach($del as $right){
                $q = sprintf('DELETE FROM FK_Right_Role
					WHERE RightId = (SELECT FK_Right.Id FROM FK_Right WHERE Name = "%s") 
					AND RoleId = (SELECT FK_Role.Id FROM FK_Role WHERE Name = "%s")',$right, $key) ;
                $this->db->query($q);
            }
        }//foreach
    }//saveRightsToDB()



}//class 
?>
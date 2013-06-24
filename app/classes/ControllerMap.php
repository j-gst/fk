<?php namespace classes;
/**
 * Mapping zwischen Seitenaufruf und Controller
 * und Pruefen der Benutzerrechte
 * @author Gerrit Storm
 */
class ControllerMap{

    // hier werden die Informationen aus der XML-Datei gespeichert
    // $map['parametername'] [controllername]
    //                                           [rechte] [0]
    //                                           [rechte] [1]
    private $map = array();

    /**
     * Einlesen der controller_map XML
     */
    public function __construct(){

        $xmlStr = file_get_contents(APP_DIR."/config/controller_map.xml");

        // SimpleXMLElement instanzieren
        // Bei einem Fehler - Abbruch
        try {
            $sXml = @new \SimpleXMLElement($xmlStr);
        } catch (\Exception $e) {
            die("Fehlerhaftes XML: controller_map.xml");
        }

        // Informationen Einlesen
        foreach ($sXml->xpath('//controllers/controller') as $controller) {
            $this->map[(string)$controller->parameter]['controller'] = (string)$controller->c_name;
            foreach($controller->rights as $right){
                $this->map[(string)$controller->parameter]['rights'][] = (String)$right->right;
            }
        }
    }//__construct()

    /**
     * Pruefen auf Existenz des Controllers und Rechte des Benutzers
     * Bei Fehler: setzen der Fehlermeldung und return false
     * @param User $user
     * @param String $target
     * @param String $msg
     * @return: boolean
     */
    public function check($user, $target, &$msg){
        // existiert der Controller
        if(isset($this->map[$target])){
            $return = $this->map[$target]['controller'];
            // Pruefen der Rechte
            if(isset($this->map[$target]['rights']))foreach($this->map[$target]['rights'] as $right){
                if( ! $user->checkRight($right)){
                    $return = false;
                    if($user->isLoggedIn()){
                        $msg = "Fehlende Berechtigung!";
                    }else{
                        $msg = "Sie m&uuml;ssen sich anmelden, um diese Seite nutzen zu k&ouml;nnen!";
                    }
                }
            }
        }else{
            $return = false;
            $msg = "Diese Seite existiert nicht!";
        }
        return $return;
    }//check()

}// class
?>
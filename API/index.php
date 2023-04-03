<?php
    require_once("json.php");
    require_once("donnee.php");

    $request_method = $_SERVER["REQUEST_METHOD"];  // GET / POST / DELETE / PUT
	switch($_SERVER["REQUEST_METHOD"]) {

        case "GET":
            if (!empty($_GET['demande'])) {
                $url = explode("/", filter_var($_GET['demande'],FILTER_SANITIZE_URL));
                switch($url[0]) {
                    case "affichage":
                        if(isset($url[1])){
                            getHumeursUtilisateur($url[1]);
                        }else{
                            affichageDonne();
                        }
                        
                        break;
                    case "emotion":
                        affichageEmotion();
                        break;
                    case "utilisateur":
                        affichageUtilisateur();
                        break;
                    default : 
						$infos['Statut']="KO";
						$infos['message']=$url[0]." inexistant";
						sendJSON($infos, 404) ;
                }
            }
            break;
        case "POST":
            if (!empty($_GET['demande'])) {
                $url = explode("/", filter_var($_GET['demande'],FILTER_SANITIZE_URL));
                switch($url[0]) {
                    case "saisieHumeur":
                        $id = $url[1];
                        $humeur = $url[2];
                        $desc = $url[3];
                        saisieHumeur($id, $humeur, $desc);

                        break;
                    default : 
						$infos['Statut']="KO";
						$infos['message']=$url[0]." inexistant";
						sendJSON($infos, 404);
                }
            }
            break;
        default:
            $infos['Statut']="KO";
            $infos['message']="URL non valide";
            sendJSON($infos, 404) ;
    }
?>
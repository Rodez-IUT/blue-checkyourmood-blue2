<?php
    require_once("json.php");
    require_once("donnee.php");

    $request_method = $_SERVER["REQUEST_METHOD"];  // GET / POST / DELETE / PUT
	switch($_SERVER["REQUEST_METHOD"]) {

        case "GET":
            if (!empty($_GET['demande'])) {
                $url = explode("/", filter_var($_GET['demande'],FILTER_SANITIZE_URL));
                if ($url[1] == "okjEi8TnWcRvYx2sLz1Qb3uHmAfDpXG"){
                    switch($url[0]) {
                        case "affichage":
                            if(isset($url[2])){
                                getHumeursUtilisateur($url[2]);
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
                }else{
                    $infos['Statut']="KO";
                    $infos['message']="Cle api invalide";
                    sendJSON($infos,401);
                }
            }
            break;
        case "POST":
            if (!empty($_GET['demande'])) {
                $url = explode("/", filter_var($_GET['demande'],FILTER_SANITIZE_URL));
                if ($url[1] == "okjEi8TnWcRvYx2sLz1Qb3uHmAfDpXG"){
                    switch($url[0]) {
                        case "saisieHumeur":
                            $id = $url[2];
                            $humeur = $url[3];
                            $desc = $url[4];
                            saisieHumeur($id, $humeur, $desc);

                            break;
                        default : 
                            $infos['Statut']="KO";
                            $infos['message']=$url[0]." inexistant";
                            sendJSON($infos, 404);
                    }
                }else{
                    $infos['Statut']="KO";
                    $infos['message']="Cle api invalide";
                    sendJSON($infos,401);
                }

            }
            break;
        default:
            $infos['Statut']="KO";
            $infos['message']="URL non valide";
            sendJSON($infos, 404) ;
    }
?>

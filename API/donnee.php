<?php 
    function getPDO() {
        // Retourne un objet connexion à la BD
		$host='localhost';	// Serveur de BD
		$db='checkyourmood';		// Nom de la BD
		$user='root';		// User 
		$pass='root';		// Mot de passe
		$charset='utf8mb4';	// charset utilisé
		
		// Constitution variable DSN
		$dsn="mysql:host=$host;dbname=$db;charset=$charset";
		
		// Réglage des options
		$options=[																				 
			PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES=>false];
		
		try{	// Bloc try bd injoignable ou si erreur SQL
			$pdo=new PDO($dsn,$user,$pass,$options);
			return $pdo ;			
		} catch(PDOException $e){
			//Il y a eu une erreur de connexion
			$infos['Statut']="KO";
			$infos['message']="Problème connexion base de données";
			sendJSON($infos, 500) ;
			die();
		}
    }

    function affichageDonne() {
        try {
            $pdo=getPDO();
            $maRequete = "SELECT * FROM utilisateur join humeur on ID_UTILISATEUR = CODE_UTILISATEUR join emotion on humeur.CODE_EMOTION = emotion.ID_EMOTION";
            
            $stmt = $pdo->prepare($maRequete);										// Préparation de la requête
			$stmt->execute();	
				
			$clients=$stmt->fetchALL();
			$stmt->closeCursor();
			$stmt=null;
			$pdo=null;

			sendJSON($clients, 200) ;
        } catch(PDOException $e){
			$infos['Statut']="KO";
			$infos['message']=$e->getMessage();
			sendJSON($infos, 500) ;
		}
    }

	function affichageUtilisateur() {
        try {
            $pdo=getPDO();
            $maRequete = "SELECT * FROM utilisateur ";
            
            $stmt = $pdo->prepare($maRequete);										// Préparation de la requête
			$stmt->execute();	
				
			$clients=$stmt->fetchALL();
			$stmt->closeCursor();
			$stmt=null;
			$pdo=null;

			sendJSON($clients, 200) ;
        } catch(PDOException $e){
			$infos['Statut']="KO";
			$infos['message']=$e->getMessage();
			sendJSON($infos, 500) ;
		}
    }
	 function getHumeursUtilisateur( $codeUtilisateur)
    {
        try {
			$pdo=getPDO();
            $sql = "SELECT *
                    FROM `humeur`
                    JOIN `emotion` ON humeur.CODE_EMOTION = emotion.ID_EMOTION
                    WHERE humeur.CODE_UTILISATEUR = :id 
                    ORDER BY `DATE_HEURE` DESC
                    ";

            $stmt = $pdo->prepare($sql);
            $stmt->BindParam('id',$codeUtilisateur);
            $stmt->execute();

			$clients=$stmt->fetchALL();
			$stmt->closeCursor();
			$stmt=null;
			$pdo=null;

			sendJSON($clients, 200) ;
        } catch(PDOException $e){
			$infos['Statut']="KO";
			$infos['message']=$e->getMessage();
			sendJSON($infos, 500) ;
		}
    }
	function affichageEmotion() {
        try {
            $pdo=getPDO();
            $maRequete = "SELECT * FROM emotion ";
            
            $stmt = $pdo->prepare($maRequete);										// Préparation de la requête
			$stmt->execute();	
				
			$clients=$stmt->fetchALL();
			$stmt->closeCursor();
			$stmt=null;
			$pdo=null;

			sendJSON($clients, 200) ;
        } catch(PDOException $e){
			$infos['Statut']="KO";
			$infos['message']=$e->getMessage();
			sendJSON($infos, 500) ;
		}
    }

	/* permet de saisir une humeur */
    function saisieHumeur($id, $humeur, $desc) {
      try {
        $pdo=getPDO();
            $maRequete = "INSERT INTO humeur (`DESCRIPTION`, `DATE_HEURE`, `CODE_UTILISATEUR`, `CODE_EMOTION`)
            VALUE (:description, CURRENT_TIMESTAMP, :id, :humeur)";
        $stmt = $pdo->prepare($maRequete);
        $stmt->BindParam("description", $desc);
        $stmt->BindParam("id", $id);
        $stmt->BindParam("humeur", $humeur);
        $stmt->execute();
        $stmt->closeCursor();
        $stmt=null;
        $pdo=null;
      } catch(PDOException $e){
        $infos['Statut']="KO";
        $infos['message']=$e->getMessage();
        sendJSON($infos, 500) ;
      }
    }
?>

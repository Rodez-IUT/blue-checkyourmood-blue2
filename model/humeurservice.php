<?php

namespace model;

class humeurservice
{

    /* Recupération des humeurs selon un utilisateur et selon l'émotion voulue et selon une date */
    public static function getHumeursUtilisateur($pdo, $codeUtilisateur, $codeEmotion, $dateHeure)
    {
        try{
            $sql = "SELECT *
                    FROM `humeur`
                    JOIN `emotion` ON humeur.CODE_EMOTION = emotion.ID_EMOTION
                    WHERE humeur.CODE_UTILISATEUR = ?"
            if($codeEmotion == null){ 
                $sql = $sql."AND humeur.CODE_EMOTION = ?"
            }
            if($dateHeure == null){ 
                $sql = $sql."AND humeur.DATE_HEURE LIKE ? "
            }
            $sql = $sql."ORDER BY `DATE_HEURE` DESC";

            $stmt = $pdo->prepare($sql);
            if($codeEmotion == null && $dateHeure != null){

                $stmt->execute([$codeUtilisateur,$dateHeure."%"]); //recherche humeur avec date

            }else if($codeEmotion != null && $dateHeure == null){

                $stmt->execute([$codeUtilisateur,$codeEmotion]); //recherche humeur avec emotion

            }else if($codeEmotion == null && $dateHeure == null){

                $stmt->execute([$codeUtilisateur]); // recherche toute les humeurs
            }else{

                $stmt->execute([$codeUtilisateur,$codeEmotion,$dateHeure."%"]);  //recherche humeur avec emotion et date
            }
            $tabHumeurs = array();
            while ($row = $stmt->fetch()) {
                $tabHumeurs[] = array(
                    'DATE_HEURE' => $row['DATE_HEURE'], 'EMOJI' => $row['EMOJI'], 'NOM' => $row['NOM'], 'DESCRIPTION' => $row['DESCRIPTION'],
                    'CODE_EMOTION' => $row['CODE_EMOTION'], 'ID_HUMEUR' => $row['ID_HUMEUR']
                );
            }
            return $tabHumeurs;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            exit();
        }
    }

    /* Ajout d'une humeur */
    public static function ajoutHumeur($pdo, $description, $dateHeure, $codeUtilisateur, $codeEmotion)
    {

        $sql = "INSERT INTO `humeur` (`DESCRIPTION`, `DATE_HEURE`, `CODE_UTILISATEUR`, `CODE_EMOTION`) 
                VALUES (?, ?, ?, ?)";

        $pdo->beginTransaction();  

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$description, $dateHeure, $codeUtilisateur, $codeEmotion]);
            $_GET['humeursaisie'] = true;
            $pdo->commit();

        } catch (\PDOException $e) {
            $code = $e -> getCode();
            if ($code == 78945) {
                $_GET['dateHeureOK'] = false;

            } else if ($code == 22001) {
                $_GET['descriptionOK'] = false;
                
            } else {
                $e->getMessage();
                $_GET['exception'] = $e;
            }
            $pdo->rollBack();
        } catch (\Exception $e) {
            $_GET['exception'] = $e->getMessage();
        }

    }

    /* Suppression d'une humeur */
    public static function suppHumeursUtilisateur($pdo, $codeUtilisateur, $idHumeur)
    {
        $pdo->beginTransaction(); 

        try {

            $stmt = $pdo->prepare("DELETE FROM humeur WHERE CODE_UTILISATEUR = ? AND ID_HUMEUR = ?");

            $stmt->execute([$codeUtilisateur, $idHumeur]);
            $pdo->commit(); 
            $_GET['humeurSupp'] = true;

        } catch (\Exception $e) {
            $pdo->rollBack();
            $e -> getMessage();
            $_GET['humeurSupp'] = false;
        }
    }

    /* Suppression d'une humeur */
    public static function suppToutesHumeursUtilisateur($pdo, $codeUtilisateur)
    {
        $pdo->beginTransaction(); 
        
        try {
            $stmt = $pdo->prepare("DELETE FROM humeur WHERE CODE_UTILISATEUR = ?");

            $stmt->execute([$codeUtilisateur]);
            $pdo->commit();

        } catch (\Exception $e) {
            $pdo->rollBack();
            $e -> getMessage();
        }
    }
}

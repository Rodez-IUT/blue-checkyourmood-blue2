<?php

namespace model;

class humeurservice
{

    /* Recupération des humeurs selon un utilisateur et selon l'émotion voulue et selon une date */
    public static function getHumeursUtilisateurFiltres($pdo, $codeUtilisateur, $codeEmotion, $dateHeure, $pagination)
    {
        try{
            $sql = "SELECT *
                    FROM `humeur`
                    JOIN `emotion` ON humeur.CODE_EMOTION = emotion.ID_EMOTION
                    WHERE humeur.CODE_UTILISATEUR = :id AND humeur.CODE_EMOTION = :code_emo AND humeur.DATE_HEURE LIKE :date 
                    ORDER BY `DATE_HEURE` DESC
                    LIMIT 15 OFFSET :pagination
                    ";

            $stmt = $pdo->prepare($sql);
            var_dump($pagination);
            $pagination = (($pagination - 1) * 15);
            $stmt->bindParam(':pagination', $pagination, $pdo::PARAM_INT);
            $stmt->bindParam('id',$codeUtilisateur);
            $stmt->bindParam('code_emo', $codeEmotion);
            $dateHeure = $dateHeure."%";
            $stmt->bindParam('date', $dateHeure);
            $stmt->execute();

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

    /* Recupération des humeurs selon un utilisateur et selon une date */
    public static function getHumeursUtilisateurDate($pdo, $codeUtilisateur, $dateHeure, $pagination)
    {
        try {
            $sql = "SELECT *
                    FROM `humeur`
                    JOIN `emotion` ON humeur.CODE_EMOTION = emotion.ID_EMOTION
                    WHERE humeur.CODE_UTILISATEUR = :id AND humeur.DATE_HEURE LIKE :date 
                    ORDER BY `DATE_HEURE` DESC
                    LIMIT 15 OFFSET :pagination
                    ";

            $stmt = $pdo->prepare($sql);
            $pagination = (($pagination - 1) * 15);
            $stmt->bindParam(':pagination', $pagination, $pdo::PARAM_INT);
            $stmt->BindParam('id',$codeUtilisateur);
            $dateHeure = $dateHeure."%";
            $stmt->BindParam('date', $dateHeure);
            $stmt->execute();

            $tabHumeurs = array();
            while ($row = $stmt->fetch()) {
                $tabHumeurs[] = array(
                    'DATE_HEURE' => $row['DATE_HEURE'], 'EMOJI' => $row['EMOJI'], 'NOM' => $row['NOM'], 'DESCRIPTION' => $row['DESCRIPTION'], 'CODE_EMOTION' => $row['CODE_EMOTION'], 'ID_HUMEUR' => $row['ID_HUMEUR']
                );
            }
            return $tabHumeurs;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            exit();
        }
    }

    /* Recupération des humeurs selon un utilisateur et selon l'émotion voulue */
    public static function getHumeursUtilisateurEmotion($pdo, $codeUtilisateur, $codeEmotion, $pagination)
    {
        try {
            $sql = "SELECT *
                    FROM `humeur`
                    JOIN `emotion` ON humeur.CODE_EMOTION = emotion.ID_EMOTION
                    WHERE humeur.CODE_UTILISATEUR = :id AND humeur.CODE_EMOTION = :code_emo 
                    ORDER BY `DATE_HEURE` DESC
                    LIMIT 15 OFFSET :pagination
                    ";

            $stmt = $pdo->prepare($sql);
            $stmt->BindParam('id',$codeUtilisateur);
            $stmt->BindParam('code_emo', $codeEmotion);
            $pagination = (($pagination - 1) * 15);
            $stmt->bindParam(':pagination', $pagination, $pdo::PARAM_INT);
            $stmt->execute();

            $tabHumeurs = array();
            while ($row = $stmt->fetch()) {
                $tabHumeurs[] = array(
                    'DATE_HEURE' => $row['DATE_HEURE'], 'EMOJI' => $row['EMOJI'], 'NOM' => $row['NOM'], 'DESCRIPTION' => $row['DESCRIPTION'], 'CODE_EMOTION' => $row['CODE_EMOTION'], 'ID_HUMEUR' => $row['ID_HUMEUR']
                );
            }
            return $tabHumeurs;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            exit();
        }
    }

    /* Recupération des humeurs selon un utilisateur */
    public static function getHumeursUtilisateur($pdo, $codeUtilisateur, $pagination)
    {
        try {
            $sql = "SELECT *
                    FROM `humeur`
                    JOIN `emotion` ON humeur.CODE_EMOTION = emotion.ID_EMOTION
                    WHERE humeur.CODE_UTILISATEUR = :id 
                    ORDER BY `DATE_HEURE` DESC
                    LIMIT 15 OFFSET :pagination
                    ";

            $stmt = $pdo->prepare($sql);
            $pagination = (($pagination - 1) * 15);

            $stmt->bindParam(':pagination', $pagination, $pdo::PARAM_INT);
            $stmt->BindParam('id',$codeUtilisateur);
            $stmt->execute();

            $tabHumeurs = array();
            while ($row = $stmt->fetch()) {
                $tabHumeurs[] = array(
                    'DATE_HEURE' => $row['DATE_HEURE'], 'EMOJI' => $row['EMOJI'], 'NOM' => $row['NOM'], 'DESCRIPTION' => $row['DESCRIPTION'], 'CODE_EMOTION' => $row['CODE_EMOTION'], 'ID_HUMEUR' => $row['ID_HUMEUR']
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
    /**  Récupère le nombre d'humeur total d'un utilisateur 
    * @return Le nombre d'humeur total saisie par un utilisateur (ex: si l'utilisateur à saisie 50 humeurs alors return 50)
    */
    public static function nombreTotalHumeurPourUtilisateur($pdo, $codeUtilisateur) {
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM humeur join utilisateur on ID_UTILISATEUR = CODE_UTILISATEUR where ID_UTILISATEUR = :id");
        $stmt->BindParam('id', $codeUtilisateur);
        $stmt->execute();
        return $stmt->fetchColumn();
    } catch (\Exception $e) {
        var_dump($e->getMessage());
        exit();
    }
    }

    /**
     * @param $codeUtilisateur id de l'utilisateur
     * @param $codeEmotion humeur saisie
     * @param $dateHeure date saisie par l'utilisateur
     * @return Le nombre total d'humeur aprés les avoir filtré par émotions et par date
     */
    public static function nombreTotalHumeurPourUtilisateurAvecFiltres($pdo, $codeUtilisateur, $codeEmotion, $dateHeure) {
        try{
            $sql = "SELECT count(*)
                    FROM `humeur`
                    JOIN `emotion` ON humeur.CODE_EMOTION = emotion.ID_EMOTION
                    WHERE humeur.CODE_UTILISATEUR = :id AND humeur.CODE_EMOTION = :code_emo AND humeur.DATE_HEURE LIKE :date 
                    ORDER BY `DATE_HEURE` DESC
                    ";

            $stmt = $pdo->prepare($sql);
            $stmt->BindParam('id',$codeUtilisateur);
            $stmt->BindParam('code_emo', $codeEmotion);
            $dateHeure = $dateHeure."%";
            $stmt->BindParam('date', $dateHeure);
            $stmt->execute();

            $tabHumeurs = array();
            
            return $stmt->fetchColumn();
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            exit();
        }
    }

    /**
     * @param $codeUtilisateur id de l'utilisateur
     * @param $codeEmotion humeur saisie
     * @return Le nombre total d'humeur aprés les avoir filtré par émotions 
     */
    public static function nombreTotalHumeurPourUtilisateurEmotion($pdo, $codeUtilisateur, $codeEmotion) {
        try {
            $sql = "SELECT count(*)
                    FROM `humeur`
                    JOIN `emotion` ON humeur.CODE_EMOTION = emotion.ID_EMOTION
                    WHERE humeur.CODE_UTILISATEUR = :id AND humeur.CODE_EMOTION = :code_emo 
                    ORDER BY `DATE_HEURE` DESC
                    ";

            $stmt = $pdo->prepare($sql);
            $stmt->BindParam('id',$codeUtilisateur);
            $stmt->BindParam('code_emo', $codeEmotion);
            $stmt->execute();

            return $stmt->fetchColumn();
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            exit();
        }
    }

    /**
     * @param $codeUtilisateur id de l'utilisateur
     * @param $dateHeure date saisie par l'utilisateur
     * @return Le nombre total d'humeur aprés les avoir filtré par dates
     */
    public static function nombreTotalHumeurPourUtilisateurDate($pdo, $codeUtilisateur, $dateHeure) {
        try {
            $sql = "SELECT count(*)
                    FROM `humeur`
                    JOIN `emotion` ON humeur.CODE_EMOTION = emotion.ID_EMOTION
                    WHERE humeur.CODE_UTILISATEUR = :id AND humeur.DATE_HEURE LIKE :date 
                    ORDER BY `DATE_HEURE` DESC
                    ";

            $stmt = $pdo->prepare($sql);
            $stmt->BindParam('id',$codeUtilisateur);
            $dateHeure = $dateHeure."%";
            $stmt->BindParam('date', $dateHeure);
            $stmt->execute();

            return $stmt->fetchColumn();
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            exit();
        }
    }
    
}
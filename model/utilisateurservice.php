<?php

namespace model;
use PDO;

class utilisateurservice
{

    /**
     * Ajoute un utilisateur a la base de donnée
     * @return true si trouver sinon false
     */
    public static function ajouterUtilisateur($pdo, $nom, $prenom, $mail, $nomUtilisateur, $genre, $dateNaissance, $motDePasse)
    {
        //Cryptage du mot de passe
        $mdp  = hash('sha1', htmlspecialchars($motDePasse));

        $sql ="INSERT INTO `utilisateur` (`NOM`, `PRENOM`, `NOM_UTILISATEUR`, `MOT_DE_PASSE`, `MAIL`, `GENRE`, `DATE_DE_NAISSANCE`) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";

        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nom, $prenom, $nomUtilisateur, $mdp, $mail,  $genre, $dateNaissance]);
            $_GET['creation'] = true;
            $pdo->commit();

        } catch (\PDOException $e) {
            $code = $e -> getCode();
            if ($code == 23000) {
                $_GET['identifiantDejaUtilise'] = true;
            } else {
                $e->getMessage();
                $_GET['exception'] = $e;
            }
            $pdo->rollBack();

        } catch (\Exception $e) {
            $_GET['creation'] = false;
            $e->getMessage();
            $_GET['exception'] = $e;
            $pdo->rollBack();
        }
       
    }

    /* Supprimer un utilisateur */
    public static function suppUtilisateur($pdo, $codeUtilisateur)
    {
        $sql = "DELETE FROM `utilisateur` WHERE ID_UTILISATEUR = ?";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$codeUtilisateur]);

        } catch (Exception $e) {
            $e -> getMessage();
        }
    }

    /* Modifier profil */
    public static function modifierProfil($pdo, $nom, $prenom, $nomUtilisateur, $mail, $genre, $dateNaissance, $codeUtilisateur)
    {

        $sql = "UPDATE utilisateur
        SET NOM = ?,
        PRENOM = ?,
        NOM_UTILISATEUR = ?,
        MAIL = ?,
        GENRE = ?,
        DATE_DE_NAISSANCE = ?	 
        WHERE ID_UTILISATEUR = ?";

        $pdo->beginTransaction();        

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nom, $prenom, $nomUtilisateur, $mail, $genre, $dateNaissance, $codeUtilisateur]);
            $_GET['modification'] = true;
            $pdo->commit();

        } catch (\PDOException $e) {
            $code = $e -> getCode();
            if ($code == 23000) {
                $_GET['identifiantDejaUtilise'] = true;
            } else {
                $e->getMessage();
                $_GET['exception'] = $e;
            }
            $pdo->rollBack();

        } catch (\Exception $e) {
            $pdo->rollBack();
            $e->getMessage();
            $_GET['modification'] = false;
        }
        
    }

    /* Modifier profil */
    public static function modifierMotDePasse($pdo, $motDePasse, $codeUtilisateur)
    {
        $motDePasse = sha1($motDePasse);

        $sql = "UPDATE utilisateur
        SET MOT_DE_PASSE = ?
        WHERE ID_UTILISATEUR = ?";

        $pdo->beginTransaction();     

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$motDePasse, $codeUtilisateur]);
            $_GET['modification'] = true;
            $pdo->commit();
            
        } catch (\Exception $e) {
            $pdo->rollBack();
            $e->getMessage();
            print $e;
            $_GET['modification'] = false;
        }
    }
}

<?php

namespace model;

class connexionservice
{

    /**
     * Chercher si l'identifiant existe dans la bd
     * @return true si trouver sinon false
     */
    public static function identifiantExiste($pdo, $identifiant)
    {
        try {
            $test = false;
            $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE NOM_UTILISATEUR = ?");
            $stmt->execute([$identifiant]);
            $user = $stmt->fetch();

            if ($user != null) {
                $test = true;
            }
            return $test;

        } catch (\Exception $e) {
            var_dump($e->getMessage());
            exit();
        }
    }

    /**
     * Verifie que le MDP correspond a l'identifiant
     */
    public static function motDePasseValide($pdo, $identifiant, $motDePasse)
    {
        try {
            $test = false;
            $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE NOM_UTILISATEUR = ? AND MOT_DE_PASSE = ?");
            $stmt->execute([$identifiant, sha1($motDePasse)]);
            $user = $stmt->fetch();
            if ($user != null) {
                $test = true;
            }
            return $test;

        } catch (\Exception $e) {
            var_dump($e->getMessage());
            exit();
        }
               
    }

    /**
     * Donne les infos de l'utilisateur sélectionné
     * retourne les informations de l'utilisateur si il est trouvé par le nom_utilisateur
     */
    public static function getUtilisateur($pdo, $identifiant)
    {
        try {
            $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE NOM_UTILISATEUR = ?");
            $stmt->execute([$identifiant]);
            $user = $stmt->fetch();
            return $user;

        } catch (\Exception $e) {
            var_dump($e->getMessage());
            exit();
        }
    }

    /**
     * Donne les infos de l'utilisateur sélectionné
     * retourne les informations de l'utilisateur si il est trouvé par l'id_utilisateur
     */
    public static function getUtilisateurById($pdo, $id)
    {
        try {
            $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE ID_UTILISATEUR = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch();
            return $user;

        } catch (\Exception $e) {
            var_dump($e->getMessage());
            exit();
        }
    }
}

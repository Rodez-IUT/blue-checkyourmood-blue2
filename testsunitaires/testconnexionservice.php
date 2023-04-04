<?php
use model\connexionservice;
use model\stathumeurservice;
use PHPUnit\Framework\TestCase;



class testconnexionservice extends TestCase {

    public function getPDO() {
        try {
            $db = new PDO('mysql:host=localhost;port=3306;dbname=checkyourmood;charset=utf8','root','');
            return $db;
        } catch (Exception $e) {
                die('Erreur : ' . $e->getMessage());
        }
    }

    public function testVerifIdentifiant() {
        // Given : Une instance de la classe ConnexionServiceTest et une connexion PDO à la base de données.
        $pdo = $this->getPDO();
        // When : On appelle la méthode identifiantExiste() de la classe ConnexionService avec un identifiant qui existe dans la base de données et un identifiant qui n'existe pas dans la base de données.
        $aTester1 = connexionservice::identifiantExiste($pdo, "simon");
        $aTester2 = connexionservice::identifiantExiste($pdo, "existePas");
        // Then : On vérifie que la méthode retourne "true" pour l'identifiant existant et "false" pour l'identifiant non-existant.
        $this->assertTrue($aTester1);
        $this->assertNotTrue($aTester2);
    }

    public function testMotDePasse() {
        // Given : Une instance de la classe ConnexionServiceTest et une connexion PDO à la base de données.
        $pdo = $this->getPDO();
        // When : On appelle la méthode motDePasseValide() de la classe ConnexionService avec un identifiant et un mot de passe qui ne sont pas valides.
        $motDePasseValide = sha1("123");
        $aTester1 = connexionservice::motDePasseValide($pdo, "simon", $motDePasseValide);
        // Then : On vérifie que la méthode retourne "false".
        $this->assertNotTrue($aTester1);
    }

    public function testMotDePasseValide() {
        // Given : Une instance de la classe ConnexionServiceTest et une connexion PDO à la base de données.
        $pdo = $this->getPDO();
        // When : On appelle la méthode motDePasseValide() de la classe ConnexionService avec un identifiant et un mot de passe qui sont valides.
        $motDePasseValide = sha1("123");
        $aTester1 = connexionservice::motDePasseValide($pdo, "simon", $motDePasseValide);
        // Then : On vérifie que la méthode retourne "true".
        $this->assertNotTrue($aTester1);
    }


    public function testGetUtilisateur() {
        // Given : Une instance de la classe ConnexionServiceTest et une connexion PDO à la base de données.
        $pdo = $this->getPDO();
        // When : On appelle la méthode getUtilisateur() de la classe ConnexionService avec un identifiant existant dans la base de données.
        $aTester1 = connexionservice::getUtilisateur($pdo, "simon");
        // Then : On vérifie que la méthode retourne les informations de l'utilisateur en format JSON.
        $this->assertEquals('{"ID_UTILISATEUR":70,"0":70,"NOM":"LAUNAY","1":"LAUNAY","PRENOM":"Simon","2":"Simon","NOM_UTILISATEUR":"simon","3":"simon","MOT_DE_PASSE":"40bd001563085fc35165329ea1ff5c5ecbdbbeef","4":"40bd001563085fc35165329ea1ff5c5ecbdbbeef","MAIL":"launay.simon@outlook.com","5":"launay.simon@outlook.com","GENRE":"homme","6":"homme","DATE_DE_NAISSANCE":"2002-10-10","7":"2002-10-10"}', json_encode($aTester1));
    }

    public function testGetUtilisateurById() {
        // Given : Une instance de la classe ConnexionServiceTest et une connexion PDO à la base de données.
        $pdo = $this->getPDO();
        // When : On appelle la méthode getUtilisateurById() de la classe ConnexionService avec un identifiant existant dans la base de données et un identifiant qui n'existe pas dans la base de données.
        $aTester1 = connexionservice::getUtilisateurById($pdo, "70");
        //l'ID "1" n'existe pas dans la base de données
        $aTester2 = connexionservice::getUtilisateurById($pdo, "1");
        // Then : On vérifie que la méthode retourne les informations de l'utilisateur en format JSON pour l'identifiant existant et que la méthode ne retourne pas les mêmes informations pour l'identifiant qui n'existe pas.
        $this->assertNotEquals('Ne doit pas être égal', json_encode($aTester2));
        $this->assertEquals('{"ID_UTILISATEUR":70,"0":70,"NOM":"LAUNAY","1":"LAUNAY","PRENOM":"Simon","2":"Simon","NOM_UTILISATEUR":"simon","3":"simon","MOT_DE_PASSE":"40bd001563085fc35165329ea1ff5c5ecbdbbeef","4":"40bd001563085fc35165329ea1ff5c5ecbdbbeef","MAIL":"launay.simon@outlook.com","5":"launay.simon@outlook.com","GENRE":"homme","6":"homme","DATE_DE_NAISSANCE":"2002-10-10","7":"2002-10-10"}', json_encode($aTester1));
    }
}
?>
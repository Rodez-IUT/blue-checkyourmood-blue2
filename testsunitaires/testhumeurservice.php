<?php
use model\connexionservice;
use model\humeurservice;
use model\stathumeurservice;
use PHPUnit\Framework\TestCase;



class testhumeurservice extends TestCase {

    public function getPDO() {
        try {
            $db = new PDO('mysql:host=localhost;port=3306;dbname=checkyourmood;charset=utf8','root','');
            return $db;
        } catch (Exception $e) {
                die('Erreur : ' . $e->getMessage());
        }
    }

    public function testGetHumeursUtilisateurFiltres() {
        // Given : une base de données PDO, un id d'utilisateur, un nombre de résultats maximum et une date
        $pdo = $this->getPDO();
        // When : on appelle la fonction getHumeursUtilisateurFiltres avec un id valide et un id invalide
        $aTester1 = humeurservice::getHumeursUtilisateurFiltres($pdo, 70, 1, '2023-01-01',1);
        $aTester2 = humeurservice::getHumeursUtilisateurFiltres($pdo, 1, 30, 2023-01-01,1);
        // Then : on s'attend à ce que la fonction renvoie un tableau encodé en JSON avec les résultats correspondants pour l'id valide et un tableau vide pout l'id invalide
        $this->assertEquals('[{"DATE_HEURE":"2023-01-01 02:05:28","EMOJI":"&#129321","NOM":"Admiration","DESCRIPTION":"erat vestibulum sed magna at nunc commodo placerat praesent blandit nam nulla integer pede justo lacinia eget tincidunt eget tempus vel pede morbi porttitor lorem id ligula suspendisse ornare consequat lectus in est risus auctor sed tristique in tempus sit amet sem fusce consequat nulla","CODE_EMOTION":1,"ID_HUMEUR":1884}]', json_encode($aTester1));
        $this->assertEquals('[]', json_encode($aTester2));

    }

    public function testGetHumeursUtilisateurDate() {
        // Given : une base de données PDO, un id d'utilisateur et une date
        $pdo = $this->getPDO();
        // When : on appelle la fonction getHumeursUtilisateurDate avec un id valide et un id invalide
        $aTester1 = humeurservice::getHumeursUtilisateurDate($pdo, 70, '2023-01-01',1);
        $aTester2 = humeurservice::getHumeursUtilisateurDate($pdo, 2, '2023-01-01',1);
        // Then : on s'attend à ce que la fonction renvoie un tableau encodé en JSON avec les résultats correspondants pour l'id valide et un tableau vide pout l'id invalide
        $this->assertEquals('[{"DATE_HEURE":"2023-01-01 09:56:16","EMOJI":"&#128535","NOM":"\u00c9merveillement","DESCRIPTION":"non ligula pellentesque ultrices phasellus id sapien in sapien iaculis congue vivamus metus arcu adipiscing molestie hendrerit at vulputate vitae nisl aenean lectus pellentesque eget nunc donec quis orci eget orci vehicula condimentum curabitur in libero ut massa volutpat convallis morbi odio odio elementum","CODE_EMOTION":7,"ID_HUMEUR":1775},{"DATE_HEURE":"2023-01-01 02:05:28","EMOJI":"&#129321","NOM":"Admiration","DESCRIPTION":"erat vestibulum sed magna at nunc commodo placerat praesent blandit nam nulla integer pede justo lacinia eget tincidunt eget tempus vel pede morbi porttitor lorem id ligula suspendisse ornare consequat lectus in est risus auctor sed tristique in tempus sit amet sem fusce consequat nulla","CODE_EMOTION":1,"ID_HUMEUR":1884},{"DATE_HEURE":"2023-01-01 00:40:29","EMOJI":"&#128562","NOM":"Int\u00e9r\u00eat \u00e9tonn\u00e9, intrigu\u00e9","DESCRIPTION":"libero nullam sit amet turpis elementum ligula vehicula consequat morbi a","CODE_EMOTION":15,"ID_HUMEUR":1700}]', json_encode($aTester1));
        $this->assertEquals('[]', json_encode($aTester2));
        
    }

    public function testGetHumeursUtilisateurEmotion() {
        // Given un objet PDO initialisé pour la base de données de test.
        $pdo = $this->getPDO();
        // When on appelle la fonction getHumeursUtilisateurEmotion avec un id valide et un id invalide
        $aTester1 = humeurservice::getHumeursUtilisateurEmotion($pdo, 70, 27,1);
        $aTester2 = humeurservice::getHumeursUtilisateurEmotion($pdo, 2, '2023-01-01',1);
        // Then la méthode doit retourner un tableau de trois objets JSON, chacun représentant une humeur enregistrée dans la base de données pour l'id valide et un tableau vide pout l'id invalide
        $this->assertEquals('[{"DATE_HEURE":"2023-04-02 18:06:00","EMOJI":"&#128558","NOM":"Surprise","DESCRIPTION":"je suis surpris !","CODE_EMOTION":27,"ID_HUMEUR":2040}]', json_encode($aTester1));
        $this->assertEquals('[]', json_encode($aTester2));
        
    }

    public function testGetHumeursUtilisateur() {
        // Given un objet PDO initialisé pour la base de données de test.
        $pdo = $this->getPDO();
        // When on appelle la fonction getHumeursUtilisateur avec un id valide et un id invalide
        $aTester1 = humeurservice::getHumeursUtilisateur($pdo, 71,1);
        $aTester2 = humeurservice::getHumeursUtilisateur($pdo, 1,1);
        // Then la méthode doit retourner un tableau de trois objets JSON, chacun représentant une humeur enregistrée dans la base de données pour l'id valide et un tableau vide pout l'id invalide
        $this->assertEquals('[{"DATE_HEURE":"2023-04-02 18:09:00","EMOJI":"&#128560","NOM":"Anxi\u00e9t\u00e9","DESCRIPTION":"","CODE_EMOTION":6,"ID_HUMEUR":2041},{"DATE_HEURE":"2023-04-02 18:09:00","EMOJI":"&#128535","NOM":"\u00c9merveillement","DESCRIPTION":"","CODE_EMOTION":7,"ID_HUMEUR":2042}]', json_encode($aTester1));
        $this->assertEquals('[]', json_encode($aTester2));
    }

    public function testAjoutHumeur() {
        // Given un objet PDO initialisé pour la base de données de test.
        $pdo = $this->getPDO();
        // When on appelle la fonction getHumeursUtilisateur avec un id valide et un id invalide
        // Then la méthode renvois null si l'id est valide et renvois false si l'id n'est pas valide
        
        $this->assertNotTrue(humeurservice::ajoutHumeur($pdo, "test", "2023-01-13 19:15:11", 72, 1));
        $this->assertNull(humeurservice::ajoutHumeur($pdo, "test", "2023-01-13 19:15:11", 71, 1));
    }

    public function testSuppHumeursUtilisateur() {
        // Given un objet PDO initialisé pour la base de données de test.
        $pdo = $this->getPDO();
        // When on appelle la fonction getHumeursUtilisateur avec un id valide et un id invalide
        // Then la méthode renvois null si l'id est valide et renvois false si l'id n'est pas valide
        $this->assertNotTrue(humeurservice::suppHumeursUtilisateur($pdo, 72, 1, "2023-01-13 19:15:11"));
        $this->assertNull(humeurservice::suppHumeursUtilisateur($pdo, 72, 1, "2023-01-13 19:15:11"));
    }

    public function testSuppToutesHumeursutilisateur() {
        // Given un objet PDO initialisé pour la base de données de test.
        $pdo = $this->getPDO();
        // When on appelle la fonction getHumeursUtilisateur avec un id valide et un id invalide
        // Then la méthode renvois null si l'id est valide et renvois false si l'id n'est pas valide
        $this->assertNotTrue(humeurservice::suppToutesHumeursUtilisateur($pdo, 72));
        $this->assertNull(humeurservice::suppToutesHumeursUtilisateur($pdo, 72));
    }

}
?>
<?php
require_once("controllers/modificationprofilcontroller.php");
use yasmf\config;
use PHPUnit\Framework\TestCase;
use controllers\ModificationProfilController;
use model\emotionsservice;
use model\humeurservice;
use yasmf\httphelper;

use PDO;
use PDOStatement;

class testmodificationprofilcontroller extends TestCase
{
    private modificationprofilcontroller $modificationprofilcontroller;
    private PDO $pdo;
    private PDOStatement $pdoStatement;

    public function setUp(): void
    {
        parent::setUp();
        // given a index controller
        $this->modificationprofilcontroller = new modificationprofilcontroller();
        // create stubs for PDO and PDOStatement
        $this->pdo = $this->createStub(PDO::class);
        $this->pdoStatement = $this->createStub(PDOStatement::class);
        // set the return value for the execute method of the PDOStatement stub
        $this->pdoStatement->method('execute')->willReturn(true);
        // set the return value for the prepare method of the PDO stub
        $this->pdo->method('prepare')->willReturn($this->pdoStatement);
    }

    public function testindex() {

        //GIVEN toute les variable d'inscription Bonne
        $_GET['newNom'] = 'Bois';
        $_GET['newPrenom'] = 'Axel';
        $_GET['newMail'] = 'axel.bois@mail.fr';
        $_GET['newNomUtilisateur'] = "Axel";
        $_GET['newGenre'] = "Homme";
        $_GET['newDateNaissance'] = "2003-12-09";
        $_GET['newMotDePasse1'] = "test";
        $_GET['newMotDePasse2'] = "test";

        $_GET['nomOK'] = true;
        $_GET['prenomOK'] = true;
        $_GET['mailOK'] = true;
        $_GET['nomUtilisateurOK'] = true;
        $_GET['genreOK'] = true;
        $_GET['dateNaissanceOK'] = true;
        $_GET['motDePasse1OK'] = true;
        $_GET['motDePasse2OK'] = true;
        $_GET['dateNaissanceOK'] = true;
        $_GET['creation'] = true;
        $_GET['identifiantDejaUtilise'] = false;
        $_GET['affichage'] = true;

        //WHEN on lance modificationprofilcontroller
        $view = $this->modificationprofilcontroller->index($this->pdo);

        //THEN tout les varible doivent êtres assignée
        self::assertEquals("Bois", $view->getVar('nom'));
        self::assertEquals("Axel", $view->getVar('prenom'));
        self::assertEquals("axel.bois@mail.fr", $view->getVar('mail'));
        self::assertEquals("Axel", $view->getVar('nomUtilisateur'));
        self::assertEquals("Homme", $view->getVar('genre'));
        self::assertEquals("2003-12-09", $view->getVar('dateNaissance'));
        self::assertEquals("test", $view->getVar('motDePasse1'));
        self::assertEquals("test", $view->getVar('motDePasse2'));
        self::assertTrue($view->getVar('nomOK'));
        self::assertTrue($view->getVar('prenomOK'));
        self::assertTrue($view->getVar('mailOK'));
        self::assertTrue($view->getVar('nomUtilisateurOK'));
        self::assertTrue($view->getVar('genreOK'));
        self::assertTrue($view->getVar('dateNaissanceOK'));
        self::assertTrue($view->getVar('motDePasse1OK'));
        self::assertTrue($view->getVar('motDePasse2OK'));
        self::assertTrue($view->getVar('creation'));
        self::assertFalse($view->getVar('identifiantDejaUtilise'));
        self::assertTrue($view->getVar('affichage'));
    }

    public function testModifProfil() {

        //given des parametre complet pour la modificatio d'un profile
        $_GET['newNom'] = 'Bois';
        $_GET['newPrenom'] = 'Axel';
        $_GET['newMail'] = 'axel.bois@mail.fr';
        $_GET['newNomUtilisateur'] = "Axel";
        $_GET['newGenre'] = "Homme";
        $_GET['newDateNaissance'] = "2003-12-09";
        $_GET['idUtilisateur'] = 70;

        $connexionserviceMock = $this->getMockBuilder('model\connexionservice')
                                        ->disableOriginalConstructor()
                                        ->onlyMethods(['getUtilisateurById'])
                                        ->getMock();
        $connexionserviceMock->method('getUtilisateurById')->willReturn([
        'NOM' => 'Bois',
        'PRENOM' => 'Axel',
        'NOM_UTILISATEUR' => 'Axel',
        'MAIL' => 'axel.bois@mail.fr',
        'GENRE' => 'Homme',
        'DATE_DE_NAISSANCE' => '2003-12-09'
        ]);
        //when la méthode de modifiaction est appeler
        $view = $this->modificationprofilcontroller->modifierProfil($this->pdo);
        //then le profil est modifier avec les nouvelles données
        
        self::assertEquals("Bois", $view->getVar('nom'));
        self::assertEquals("Axel", $view->getVar('prenom'));
        self::assertEquals("axel.bois@mail.fr", $view->getVar('mail'));
        self::assertEquals("Axel", $view->getVar('nomUtilisateur'));
        self::assertEquals("Homme", $view->getVar('genre'));
        self::assertEquals("2003-12-09", $view->getVar('dateNaissance'));
    }
}
?>

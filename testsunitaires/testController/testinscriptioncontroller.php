<?php
require_once('controllers\inscriptioncontroller.php');

use PHPUnit\Framework\TestCase;
use controllers\inscriptionController;
use model\utilisateurservice;


class testinscriptionController extends TestCase
{

    private inscriptionController $inscriptionController;
    private PDO $pdo;
    private PDOStatement $pdoStatement;

    public function setUp(): void
    {
        parent::setUp();
        // given a index controller
        $this->inscriptionController = new inscriptionController();
        // create stubs for PDO and PDOStatement
        $this->pdo = $this->createStub(PDO::class);
        $this->pdoStatement = $this->createStub(PDOStatement::class);
        // set the return value for the execute method of the PDOStatement stub
        $this->pdoStatement->method('execute')->willReturn(true);
        // set the return value for the prepare method of the PDO stub
        $this->pdo->method('prepare')->willReturn($this->pdoStatement);
    }

    public function testindex(): void 
    {
        //GIVEN toute les variable d'inscription Bonne
        $_GET['newNom'] = 'bribach';
        $_GET['newPrenom'] = 'ahmed';
        $_GET['newMail'] = 'briahmed@gmail.com';
        $_GET['newNomUtilisateur'] = 'ahmed2.0';
        $_GET['newGenre'] = 'Homme';
        $_GET['newDateNaissance'] = '10/03/2002';
        $_GET['newMotDePasse1'] = 'oui';
        $_GET['newMotDePasse2'] =  'oui';

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

        //WHEN on lance inscriptioncontroller
        $view = $this->inscriptionController->index($this->pdo);

        //THEN tout les varible doivent êtres assignée
        self::assertEquals("bribach", $view->getVar('nom'));
        self::assertEquals("ahmed", $view->getVar('prenom'));
        self::assertEquals("briahmed@gmail.com", $view->getVar('mail'));
        self::assertEquals("ahmed2.0", $view->getVar('nomUtilisateur'));
        self::assertEquals("Homme", $view->getVar('genre'));
        self::assertEquals("10/03/2002", $view->getVar('dateNaissance'));
        self::assertEquals("oui", $view->getVar('motDePasse1'));
        self::assertEquals("oui", $view->getVar('motDePasse2'));
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


        //GIVEN toute les variable bonne et mauvaise
        $_GET['newNom'] = 'bribach';
        $_GET['newPrenom'] = '';
        $_GET['newMail'] = 'briahmed';
        $_GET['newNomUtilisateur'] = 'ahmed2.0';
        $_GET['newGenre'] = 'Homme';
        $_GET['newDateNaissance'] = '10/03/2002';
        $_GET['newMotDePasse1'] = 'oui';
        $_GET['newMotDePasse2'] =  'oui';

        $_GET['nomOK'] = true;
        $_GET['prenomOK'] = false;
        $_GET['mailOK'] = false;
        $_GET['nomUtilisateurOK'] = true;
        $_GET['genreOK'] = true;
        $_GET['dateNaissanceOK'] = true;
        $_GET['motDePasse1OK'] = true;
        $_GET['motDePasse2OK'] = true;
        $_GET['dateNaissanceOK'] = true;
        $_GET['creation'] = true;
        $_GET['identifiantDejaUtilise'] = false;
        $_GET['affichage'] = true;

        //WHEN on lance inscriptioncontroller
        $view = $this->inscriptionController->index($this->pdo);

        //THEN tout les varible doivent êtres assignée
        self::assertEquals("bribach", $view->getVar('nom'));
        self::assertEquals("", $view->getVar('prenom'));
        self::assertEquals("briahmed", $view->getVar('mail'));
        self::assertEquals("ahmed2.0", $view->getVar('nomUtilisateur'));
        self::assertEquals("Homme", $view->getVar('genre'));
        self::assertEquals("10/03/2002", $view->getVar('dateNaissance'));
        self::assertEquals("oui", $view->getVar('motDePasse1'));
        self::assertEquals("oui", $view->getVar('motDePasse2'));
        self::assertTrue($view->getVar('nomOK'));
        self::assertFalse($view->getVar('prenomOK'));
        self::assertFalse($view->getVar('mailOK'));
        self::assertTrue($view->getVar('nomUtilisateurOK'));
        self::assertTrue($view->getVar('genreOK'));
        self::assertTrue($view->getVar('dateNaissanceOK'));
        self::assertTrue($view->getVar('motDePasse1OK'));
        self::assertTrue($view->getVar('motDePasse2OK'));
        self::assertTrue($view->getVar('creation'));
        self::assertFalse($view->getVar('identifiantDejaUtilise'));
        self::assertTrue($view->getVar('affichage'));
                
    }

    public function testinscriptionValide(): void
    {
        // given a PDO mock object and all valide parameter

        foreach($_GET as $key => $value) {
            unset($_GET[$key]);
        }

        $_GET['affichage'] = true;
        $_GET['newNom'] = 'bribach';
        $_GET['newPrenom'] = 'ahmed';
        $_GET['newMail'] = 'briahmed@gmail.com';
        $_GET['newNomUtilisateur'] = 'ahmed2.0';
        $_GET['newGenre'] = 'Homme';
        $_GET['newDateNaissance'] = '10/03/2002';
        $_GET['newMotDePasse1'] = 'oui';
        $_GET['newMotDePasse2'] =  'oui';


        $utilisateurserviceMock = $this->getMockBuilder('model\utilisateurservice')
                                        ->disableOriginalConstructor()
                                        ->onlyMethods(['ajouterUtilisateur'])
                                        ->getMock();
        $utilisateurserviceMock->method('ajouterUtilisateur')->willReturn(null);
        $this->inscriptionController->utilisateurservice = $utilisateurserviceMock;

        // when call to inscription method
        $view = $this->inscriptionController->creation($this->pdo);
    
        // then the utilisator as sign in
        self::assertTrue($view->getVar('nomOK'));
        self::assertTrue($view->getVar('prenomOK'));
        self::assertTrue($view->getVar('mailOK'));
        self::assertTrue($view->getVar('nomUtilisateurOK'));
        self::assertTrue($view->getVar('genreOK'));
        self::assertTrue($view->getVar('dateNaissanceOK'));
        self::assertTrue($view->getVar('motDePasse1OK'));
        self::assertTrue($view->getVar('motDePasse2OK'));
        self::assertEquals("views/vue_inscription", $view->getRelativePath());

    }

    public function testinscriptionInvalide(): void
    {
        // given a PDO mock object and all invalide parameter

        foreach($_GET as $key => $value) {
            unset($_GET[$key]);
        }

        $_GET['affichage'] = true;
        $_GET['newNom'] = '';
        $_GET['newPrenom'] = 'ahmedttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt';
        $_GET['newMail'] = '';
        $_GET['newNomUtilisateur'] = '';
        $_GET['newGenre'] = 'Homme';
        $_GET['newDateNaissance'] = '10/03/2002';
        $_GET['newMotDePasse1'] = 'oui';
        $_GET['newMotDePasse2'] =  'oui';

        // when call to inscription method
        $view = $this->inscriptionController->creation($this->pdo);
    
        // then the utilisator as sign in
        self::assertFalse($view->getVar('nomOK'));
        self::assertFalse($view->getVar('prenomOK'));
        self::assertFalse($view->getVar('mailOK'));
        self::assertFalse($view->getVar('nomUtilisateurOK'));
        self::assertTrue($view->getVar('genreOK'));
        self::assertTrue($view->getVar('dateNaissanceOK'));
        self::assertTrue($view->getVar('motDePasse1OK'));
        self::assertTrue($view->getVar('motDePasse2OK'));
        self::assertEquals("views/vue_inscription", $view->getRelativePath());

    }
   
}

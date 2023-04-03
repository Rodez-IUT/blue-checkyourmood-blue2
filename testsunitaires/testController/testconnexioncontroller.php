<?php
require_once('model/connexionservice.php');
require_once("controllers/connexioncontroller.php");
use yasmf\config;
use PHPUnit\Framework\TestCase;
use controllers\connexioncontroller;
use model\connexionservice;
use yasmf\httphelper;

use PDO;
use PDOStatement;

class testconnexionController extends TestCase
{

    private connexioncontroller $connexioncontroller;
    private PDO $pdo;
    private PDOStatement $pdoStatement;

    public function setUp(): void
    {
        parent::setUp();
        // given a index controller
        $this->connexioncontroller = new connexioncontroller();
        // create stubs for PDO and PDOStatement
        $this->pdo = $this->createStub(PDO::class);
        $this->pdoStatement = $this->createStub(PDOStatement::class);
        // set the return value for the execute method of the PDOStatement stub
        $this->pdoStatement->method('execute')->willReturn(true);
        // set the return value for the prepare method of the PDO stub
        $this->pdo->method('prepare')->willReturn($this->pdoStatement);
    }

    public function testIndex()
    {
        // when call to index with mocked PDO connection
        $view = $this->connexioncontroller->index($this->pdo);
        // then the view point to the expected view file
        self::assertEquals("views/index", $view->getRelativePath());
    }

    public function testIndexWithRacine()
    {
        // given a PDO mock object
        $pdo = $this->createStub(PDO::class);

        // when call to index
        $view = $this->connexioncontroller->index($pdo);

        // then the view contains the expected variable
        self::assertEquals(config::getRacine(), $view->getVar('RACINE'));
    }

    public function testconnexionValide()
    {
        // given a PDO mock object and 2 valide identifier and password
        $pdo = $this->createStub(PDO::class);
        $identifiant = 'identifiantValide';
        $motDePasse = 'motDePasseValide';
        //creation connexion service mock
        $connexionserviceMock = $this->getMockBuilder('model\connexionservice')
                                    ->disableOriginalConstructor()
                                    ->onlyMethods(['identifiantExiste', 'motDePasseValide', 'getUtilisateur'])
                                    ->getMock();
        //And with in connexion services method identifiantExiste that return true
        $connexionserviceMock->method('identifiantExiste')->willReturn(true);
        //And with in connexion services method motDePasseValide that return true
        $connexionserviceMock->method('motDePasseValide')->willReturn(true);
        //And with in connexion services method getUtilisateur that the utilisator
        $connexionserviceMock->method('getUtilisateur')->willReturn([
        'ID_UTILISATEUR' => 1,
        'NOM' => 'Simon',
        'PRENOM' => 'douziech',
        'NOM_UTILISATEUR' => 'Simondouziech',
        'MAIL' => 'Simondouziech@example.com',
        'GENRE' => 'M',
        'DATE_DE_NAISSANCE' => '2003-30-12'
        ]);
        $_GET['id'] = "1";
        $_GET['nom'] = "Simon";
        $_GET['prenom'] = "douziech";
        $_GET['nom_utilisateur'] = "Simondouziech";
        $_GET['mail'] = "Simondouziech@example.com";
        $_GET['gender'] = "M";
        $_GET['date_naissance'] = "2003-30-12";
        $this->connexioncontroller->connexionservice = $connexionserviceMock;
        // when the method connexion is call with the right identifiant and mot de passe   
        $view = $this->connexioncontroller->connexion($pdo);
        // then the view is change with the  header("Location: /?controller=accueil");
        // $this->assertEquals('/?controller=accueil', $_SERVER['REQUEST_URI']);
        // and that the session variables are correctly set
        self::assertEquals(session_id(), httphelper::getParam('numeroSession'));
        self::assertEquals(1, httphelper::getParam('id'));
        self::assertEquals('Simon', httphelper::getParam('nom'));
        self::assertEquals('douziech', httphelper::getParam('prenom'));
        self::assertEquals('Simondouziech', httphelper::getParam('nom_utilisateur'));
        self::assertEquals('Simondouziech@example.com', httphelper::getParam('mail'));
        self::assertEquals('M', httphelper::getParam('gender'));
        self::assertEquals('2003-30-12', httphelper::getParam('date_naissance'));
    }

    public function testconnexionInvalide()
    {
        
        // given a PDO mock object and 2 valide identifier and password
        $pdo = $this->createStub(PDO::class);
        $identifiant = 'identifiantInValide';
        $motDePasse = 'motDePasseInValide';

        //And with in connexion services method identifiantExiste that return true
        $connexionserviceMock = $this->getMockBuilder('model\connexionservice')
                                    ->disableOriginalConstructor()
                                    ->onlyMethods(['identifiantExiste', 'motDePasseValide', 'getUtilisateur'])
                                    ->getMock();
        //And with in connexion services method identifiantExiste that return false
        $connexionserviceMock->method('identifiantExiste')->willReturn(false);
        //And with in connexion services method motDePasseValide that return false
        $connexionserviceMock->method('motDePasseValide')->willReturn(false);

        $this->connexioncontroller->connexionservice = $connexionserviceMock;

        // when the method connexion is call with the right identifiant and mot de passe
        $_GET['identifiant'] = 'identifiant';
        $_GET['motDePasse'] = 'motDePasse';
        $view = $this->connexioncontroller->connexion($this->pdo);

        // then the view don't change and we have only the $_GET['err'] that change
        $this->assertEquals('identifiantmdp', $_GET['err']);
    }
}
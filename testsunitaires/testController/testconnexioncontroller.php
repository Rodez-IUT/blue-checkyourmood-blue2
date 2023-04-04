<?php
require_once('model/connexionservice.php');
require_once("controllers/connexioncontroller.php");
use yasmf\config;
use PHPUnit\Framework\TestCase;
use controllers\connexionController;
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

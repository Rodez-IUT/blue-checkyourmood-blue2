<?php
require_once('model/humeurservice.php');
require_once('model/utilisateurservice.php');
use PHPUnit\Framework\TestCase;
use controllers\profilController;
use yasmf\config;

class testprofilController extends TestCase
{

    private profilController $profilController;
    private PDO $pdo;
    private PDOStatement $pdoStatement;

    public function setUp(): void
    {
        parent::setUp();
        // given a index controller
        $this->profilController = new profilController();
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
        $view = $this->profilController->index($this->pdo);
        // then the view point to the expected view file
        self::assertEquals("views/vue_profil", $view->getRelativePath());
    }

    public function testIndexWithRacine()
    {
        // given a PDO mock object
        $pdo = $this->createStub(PDO::class);

        // when call to index
        $view = $this->profilController->index($pdo);

        // then the view contains the expected variable
        self::assertEquals(config::getRacine(), $view->getVar('RACINE'));
        self::assertFalse($view->getVar('edition'));
    }

    public function testsupprimerProfil()
    {
        // given a PDO mock object and a utilisator code
        $pdo = $this->createStub(PDO::class);
        $codeUtilisateur = '1234';

        //And with in profil services method identifiantExiste that return true
        $humeurserviceMock = $this->getMockBuilder('humeurservice')
                                    ->disableOriginalConstructor()
                                    ->onlyMethods(['suppToutesHumeursUtilisateur'])
                                    ->getMock();
        $utilisateurserviceMock = $this->getMockBuilder('utilisateurservice')
                                    ->disableOriginalConstructor()
                                    ->onlyMethods(['suppUtilisateur'])
                                    ->getMock();
     
        $this->profilController->humeurservice = $humeurserviceMock;
        $this->profilController->utilisateurservice = $utilisateurserviceMock;

        // when the method of humeur service is call 
        $view = $this->profilController->supprimerProfil($pdo);

        // then the view is change with the  header("Location: /?controller=index");
        self::assertStringContainsString('?controller=index', $_SERVER['HTTP_REFERER']);
       
    }
}
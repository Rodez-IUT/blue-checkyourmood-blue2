<?php
require_once('model/connexionservice.php');
require_once('model/utilisateurservice.php');
use PHPUnit\Framework\TestCase;
use controllers\modificationMotDePasseController;
use yasmf\config;
use model\connexionservice;
use model\utilisateurservice;

class testmodificationmotdepassecontroller extends TestCase
{

    private modificationmotdepassecontroller $modificationmotdepassecontroller;
    private connexionservice $connexionservice;
    private utilisateurservice $utilisateurservice;
    private PDO $pdo;
    private PDOStatement $pdoStatement;

    public function setUp(): void
    {
        parent::setUp();
        // given a index controller
        $this->modificationmotdepassecontroller = new modificationmotdepassecontroller();
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
        $this->modificationmotdepassecontroller = new modificationmotdepassecontroller();
        // when call to index with mocked PDO connection
        $view = $this->modificationmotdepassecontroller->index($this->pdo);
        // then the view point to the expected view file
        self::assertEquals("views/vue_modificationmotdepasse", $view->getRelativePath());
    }
    public function testMotdePasseValide()
    {        
        
        $_SESSION['nom_utilisateur'] = 'simon';
        $_GET['motDePasseActuel'] = '1234';
        $_GET['nouveauMotDePasse'] = '12345';
        $_GET['confirmerMdp'] = '12345';
        $_GET['idUtilisateur'] = '3';
        //$this->utilisateurservice = $this->createStub(utilisateurservice::class);
        
        //$this->modificationmotdepassecontroller = new modificationmotdepassecontroller($this->utilisateurservice);
        // when call to modificationmotdepasse with mocked PDO connection
        $view = $this->modificationmotdepassecontroller->modifierMotDePasse($this->pdo);

        // then the view point to the expected view file
        self::assertEquals("vide", $view->getVar('err'));
        self::assertEquals("views/vue_modificationmotdepasse", $view->getRelativePath());
    }
}

<?php
require_once('model/connexionservice.php');
require_once('model/utilisateurservice.php');
use PHPUnit\Framework\TestCase;
use controllers\modificationmotdepassecontroller;
use yasmf\config;
use model\connexionservice;
use model\utilisateurservice;

class testmodificationmotdepassecontroller extends TestCase
{

    private modificationmotdepassecontroller $modificationmotdepassecontroller;
    private connexionservice $connexionservice;
    private utilisateurservice $utilisateurservice;

    public function setUp(): void
    {
        parent::setUp();
        // given a index controller
        // create stubs for PDO 
        $this->pdo = $this->createStub(PDO::class);
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
        var_dump($_GET);
        $this->utilisateurservice = $this->createStub(utilisateurservice::class);
        
        $this->modificationmotdepassecontroller = new modificationmotdepassecontroller($this->utilisateurservice);
        // when call to modificationmotdepasse with mocked PDO connection
        $view = $this->modificationmotdepassecontroller->modifierMotDePasse($this->pdo);

        // then the view point to the expected view file
        self::assertEquals("vide", $view->getVar('err'));
        self::assertEquals("views/vue_modificationmotdepasse", $view->getRelativePath());
    }
}

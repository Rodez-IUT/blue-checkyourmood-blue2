<?php
require_once("controllers/consultationhumeurscontroller.php");
use yasmf\config;
use PHPUnit\Framework\TestCase;
use controllers\consultationHumeursController;
use model\emotionsservice;
use model\humeurservice;
use yasmf\httphelper;

use PDO;
use PDOStatement;

class testconsultationhumeurController extends TestCase
{
    private consultationHumeursController $consultationHumeursController;
    private PDO $pdo;
    private PDOStatement $pdoStatement;

    public function setUp(): void
    {
        parent::setUp();
        // given a index controller
        $this->consultationHumeursController = new consultationHumeursController();
        // create stubs for PDO and PDOStatement
        $this->pdo = $this->createStub(PDO::class);
        $this->pdoStatement = $this->createStub(PDOStatement::class);
        // set the return value for the execute method of the PDOStatement stub
        $this->pdoStatement->method('execute')->willReturn(true);
        // set the return value for the prepare method of the PDO stub
        $this->pdo->method('prepare')->willReturn($this->pdoStatement);
    }

    public function testindex() {
        // when call to index
        $view = $this->consultationHumeursController->index($this->pdo);
        
        // then the view contains the expected variable
        self::assertEquals(httphelper::getParam('humeurs'), $view->getVar('humeurs'));

        //given une date et une emotion filtre
        $_GET['codeEmotion'] = 1;
        $_GET['dateSaisie'] = "20/03/2023";
        // when call to index
        // then the view contains the expected variable
        self::assertEquals(httphelper::getParam('humeurs'), $view->getVar('humeurs'));
        
    }

    public function testconsulter() {
        // when call to index
        $view = $this->consultationHumeursController->consulter($this->pdo);
        // then the view contains the expected variable
        self::assertEquals($this->consultationHumeursController->index($this->pdo) ,$view);
    }

    public function testsupprimer() {
        $_GET['codeUtilisateur'] = 70;
        $_GET['codeHumeur'] = 1;
        $view = $this->consultationHumeursController->supprimer($this->pdo);
        self::assertEquals($this->consultationHumeursController->index($this->pdo) ,$view);
    }
}
?>
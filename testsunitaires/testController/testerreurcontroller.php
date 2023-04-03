<?php
use PHPUnit\Framework\TestCase;
use controllers\erreurController;


class testerreurController extends TestCase
{

    private erreurController $erreurController;
    private PDO $pdo;
    private PDOStatement $pdoStatement;

    public function setUp(): void
    {
        parent::setUp();
        // given a erreur controller
        $this->erreurController = new erreurController();
        // create stubs for PDO and PDOStatement
        $this->pdo = $this->createStub(PDO::class);
        $this->pdoStatement = $this->createStub(PDOStatement::class);
        // set the return value for the execute method of the PDOStatement stub
        $this->pdoStatement->method('execute')->willReturn(true);
        // set the return value for the prepare method of the PDO stub
        $this->pdo->method('prepare')->willReturn($this->pdoStatement);
    }

    public function testerreur()
    {
        // when call to erreur with mocked PDO connection
        $view = $this->erreurController->index($this->pdo);
        // then the view point to the expected view file
        self::assertEquals("views/pageerreur", $view->getRelativePath());
    }
    public function testerreurVariable()
    {
        // given a PDO mock object
        $pdo = $this->createStub(PDO::class);
        //une erreur sur une page
        $_GET['err'] = 'Une erreur est survenue';
        // when call to erreur
        $view = $this->erreurController->index($pdo);

        // then the view contains the expected variable
        $this->assertEquals('Une erreur est survenue',$view->getVar('err'));
    }

}
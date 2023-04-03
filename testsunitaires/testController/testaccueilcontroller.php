<?php

use yasmf\config;
use PHPUnit\Framework\TestCase;
use controllers\accueilcontroller;

class testaccueilController extends TestCase
{
    
    private accueilController $accueilController;
    private PDO $pdo;
    private PDOStatement $pdoStatement;

    public function setUp(): void
    {
        parent::setUp();
        // given a index controller
        $this->accueilController = new accueilController();
        // create stubs for PDO and PDOStatement
        $this->pdo = $this->createStub(PDO::class);
        $this->pdoStatement = $this->createStub(PDOStatement::class);
        // set the return value for the execute method of the PDOStatement stub
        $this->pdoStatement->method('execute')->willReturn(true);
        // set the return value for the prepare method of the PDO stub
        $this->pdo->method('prepare')->willReturn($this->pdoStatement);
    }

    public function testIndex(): void
    {
        // when call to index with mocked PDO connection
        $view = $this->accueilController->index($this->pdo);
        // then the view point to the expected view file
        self::assertEquals("views/vue_accueil", $view->getRelativePath());
    }
    public function testIndexWithRacine(): void
    {
        // given a PDO mock object
        $pdo = $this->createStub(PDO::class);

        // when call to index
        $view = $this->accueilController->index($pdo);

        // then the view contains the expected variable
        self::assertEquals(config::getRacine(), $view->getVar('RACINE'));
    }

}
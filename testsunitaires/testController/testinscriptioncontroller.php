<?php
require_once( 'model/verificationservice.php');
use PHPUnit\Framework\TestCase;
use controllers\inscriptionController;


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

    public function testinscriptionValide()
    {
        // given a PDO mock object and all valide parameter
        $pdo = $this->createStub(PDO::class);
        $nom = 'newNomValide';
        $prenom = 'newPrenomValide';
        $mail = 'newMailValide';
        $nomUtilisateur ='newNomUtilisateurValide';
        $genre = 'newGenreValide';
        $dateNaissance = 'newDateNaissanceValide';
        $motDePasse1 = 'newMotDePasse1Valide';
        $motDePasse2 = 'newMotDePasse2Valide';

        //And with in verification services method e
        $verificationserviceMock = $this->getMockBuilder('verificationservice')
                                    ->disableOriginalConstructor()
                                    ->onlyMethods(['testNom','testPrenom','testMail','testNomUtilisateur','testGenre','testDateNaissance','testMotDePasse','testMdpCorrespond'])
                                    ->getMock();
        //And with in verification services method of test it will return true
        $verificationserviceMock->method('testNom')->willReturn(true);
        $verificationserviceMock->method('testPrenom')->willReturn(true);
        $verificationserviceMock->method('testMail')->willReturn(true);
        $verificationserviceMock->method('testNomUtilisateur')->willReturn(true);
        $verificationserviceMock->method('testGenre')->willReturn(true);
        $verificationserviceMock->method('testDateNaissance')->willReturn(true);
        $verificationserviceMock->method('testMotDePasse')->willReturn(true);
        $verificationserviceMock->method('testMdpCorrespond')->willReturn(true);
        
        $this->inscriptionController->verificationservice = $verificationserviceMock;

        //with 
        $_POST['newNom'] = $nom;
        $_POST['newPrenom'] = $prenom;
        $_POST['newMail'] = $mail;
        $_POST['newNomUtilisateur'] = $nomUtilisateur;
        $_POST['newGenre'] = $genre;
        $_POST['newDateNaissance'] = $dateNaissance;
        $_POST['newMotDePasse1'] = $motDePasse1;
        $_POST['newMotDePasse2'] = $motDePasse2;
        $_POST['affichage'] = 1;
        $_SERVER['REQUEST_METHOD'] = 'POST';
    
        // when call to inscription method
        $view = $this->inscriptionController->creation($pdo);
    
        // then the utilisator as sign in
        self::assertEquals("views/vue_inscription", $view->getRelativePath());

    }

    public function testinscriptionInvalide()
    {
        // given a PDO mock object and all invalide parameter
        $pdo = $this->createStub(PDO::class);
        $nom = '';
        $prenom = '';
        $mail = '';
        $nomUtilisateur ='';
        $genre = '';
        $dateNaissance = '';
        $motDePasse1 = '';
        $motDePasse2 = '';

        //And with in verification services method 
        $verificationserviceMock = $this->getMockBuilder('verificationservice')
                                    ->disableOriginalConstructor()
                                    ->onlyMethods(['testNom','testPrenom','testMail','testNomUtilisateur','testGenre','testDateNaissance','testMotDePasse','testMdpCorrespond'])
                                    ->getMock();
        //And with in verification services method of test it will return false
        $verificationserviceMock->method('testNom')->willReturn(false);
        $verificationserviceMock->method('testPrenom')->willReturn(false);
        $verificationserviceMock->method('testMail')->willReturn(false);
        $verificationserviceMock->method('testNomUtilisateur')->willReturn(false);
        $verificationserviceMock->method('testGenre')->willReturn(false);
        $verificationserviceMock->method('testDateNaissance')->willReturn(false);
        $verificationserviceMock->method('testMotDePasse')->willReturn(false);
        $verificationserviceMock->method('testMdpCorrespond')->willReturn(false);

        $this->inscriptionController->verificationservice = $verificationserviceMock;

        //with 
        $_POST['newNom'] = $nom;
        $_POST['newPrenom'] = $prenom;
        $_POST['newMail'] = $mail;
        $_POST['newNomUtilisateur'] = $nomUtilisateur;
        $_POST['newGenre'] = $genre;
        $_POST['newDateNaissance'] = $dateNaissance;
        $_POST['newMotDePasse1'] = $motDePasse1;
        $_POST['newMotDePasse2'] = $motDePasse2;
        $_POST['affichage'] = 1;
        $_SERVER['REQUEST_METHOD'] = 'POST';
    
        // when call to inscription method
        $view = $this->inscriptionController->creation($pdo);
    
        // then all the variable are false
        $this->assertFalse($view->getVar('nomOK'));
        $this->assertFalse($view->getVar('prenomOK'));
        $this->assertFalse($view->getVar('mailOK'));
        $this->assertFalse($view->getVar('nomUtilisateurOK'));
        $this->assertFalse($view->getVar('genreOK'));
        $this->assertFalse($view->getVar('dateNaissanceOK'));
        $this->assertFalse($view->getVar('motDePasse1OK'));
        $this->assertFalse($view->getVar('motDePasse2OK'));

    }
   
}
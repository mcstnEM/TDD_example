<?php
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\DataProvider;
use App\Calculator;

class CalculatorTest extends TestCase {

    // $calculator stock les instances de la classe Calculator recréé avant chaque tests dans setUp();
    protected Calculator $calculator;

    // setUp() s'exécute avant chaque test, son oposé, tearDown(), s'exécute après chaque tests. Ici on déclare une fois l'instance de Calculator, PHPUnit s'occupe de recréer l'instance avant chaque test (principe d'isolation des tests)
    public function setUp(): void 
    {
        $this->calculator = new Calculator;
    }

    #[TestDox('Make sum calculation')] // L'attribut TestDox() nous permet définir un intitulé à notre test
    #[DataProvider('additionProvider')] // L'attribut DataProvider() nous permet d'appliquer une série de tests sur une fonctionnalité via un "fournisseur de données" qui est une méthode static retournant un itérable (voir la méthode additionProvider() dans cette même classe)
    public function testAdd($a, $b, $expected) {
        $result = $this->calculator->add($a, $b);

        $this->assertSame($expected, $result);
    }

    public static function additionProvider() {
        return [ // Grace à l'option testdox="true" dans le fichier de configuration phpunit.xml, on pourra voir les clefs de cette itérable apparètre dans les tests.
            "expect 46" => [22, 24, 46],
            "expect 24" => [18, 6, 24],
            "expect 14" => [8, 6, 14],
        ];
    }

    // Si l'on ne souhait pas utiliser la convention "testTestName", on peut utiliser l'attribut  PHPUnit\Framwork\Attributes\Test pour indiquer à PHPUnit que cette méthode est bien un test.
    #[Test]
    public function multiply() {
        $calculator = $this->calculator;
        $result = $calculator->multiply(3, 7);
        $result1 = $calculator->multiply(4, 7);

        $this->assertSame(21, $result);
        $this->assertSame(28, $result1);
    }

    // Test si une exception DivisionByZeroError est bien lancé si l'on tente une division par zero.
    public function testGetDivideByZeroException() {
        // On doit d'abord déterminer l'erreur avant de la provoquer.
        $this->expectException(DivisionByZeroError::class);
        $this->calculator->divisor(12, 0);
    }
}
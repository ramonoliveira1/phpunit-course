<?php

namespace Service;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;
use PHPUnit\Framework\TestCase;

class AvaliadorTest extends TestCase
{
    private Avaliador $leiloeiro;

    protected function setUp(): void
    {
        $this->leiloeiro = new Avaliador();
    }

    /**
     * @dataProvider leilaoEmOrdemAleatoria
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     */
    public function testAvaliadorEncontraOMaiorValorDeLances(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);

        $maiorValor = $this->leiloeiro->getMaiorValor();

        static::assertEquals(15000, $maiorValor);
    }

    /**
     * @dataProvider leilaoEmOrdemAleatoria
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     */
    public function testAvaliadorEncontraOMenorValorDeLances(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);

        $menorValor = $this->leiloeiro->getMenorValor();

        self::assertEquals(5000, $menorValor);
    }

    /**
     * @dataProvider leilaoEmOrdemAleatoria
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     */
    public function testAvaliadorDeveBuscarOs3MaioresValores(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);

        $maioresLances = $this->leiloeiro->getMaioresLances();

        static::assertCount(3, $maioresLances);
        static::assertEquals(15000, $maioresLances[0]->getValor());
        static::assertEquals(9000, $maioresLances[1]->getValor());
        static::assertEquals(7000, $maioresLances[2]->getValor());
    }

    public static function leilaoEmOrdemCrescente(): array
    {
        $leilao = new Leilao('Fiat Uno');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');
        $cleber = new Usuario('Cleber');
        $paulo = new Usuario('Paulo');

        $leilao->recebeLance(new Lance($maria, 5000));
        $leilao->recebeLance(new Lance($joao, 7000));
        $leilao->recebeLance(new Lance($cleber, 9000));
        $leilao->recebeLance(new Lance($paulo, 15000));

        return [ 'ordem-crescente' => [$leilao]];
    }

    public static function leilaoEmOrdemDecrescente(): array
    {
        $leilao = new Leilao('Fiat Uno');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');
        $cleber = new Usuario('Cleber');
        $paulo = new Usuario('Paulo');

        $leilao->recebeLance(new Lance($paulo, 15000));
        $leilao->recebeLance(new Lance($cleber, 9000));
        $leilao->recebeLance(new Lance($joao, 7000));
        $leilao->recebeLance(new Lance($maria, 5000));

        return [ 'ordem-decrescente' => [$leilao]];
    }

    public static function leilaoEmOrdemAleatoria(): array
    {
        $leilao = new Leilao('Fiat Uno');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');
        $cleber = new Usuario('Cleber');
        $paulo = new Usuario('Paulo');

        $leilao->recebeLance(new Lance($maria, 5000));
        $leilao->recebeLance(new Lance($paulo, 15000));
        $leilao->recebeLance(new Lance($joao, 7000));
        $leilao->recebeLance(new Lance($cleber, 9000));

        return [ 'ordem-aleatoria' => [$leilao]];
    }
}
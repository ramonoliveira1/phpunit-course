<?php

namespace Model;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;
use PHPUnit\Framework\TestCase;

class LeilaoTest extends TestCase
{
    /**
     * @dataProvider geraLances
     */
    public function testLeilaoDeveReceberLances(int $qtde, Leilao $leilao, array $valores)
    {
        $lances = $leilao->getLances();
        static::assertCount($qtde, $lances);

        foreach ($valores as $i => $valorEsperado) {
            static::assertEquals($valorEsperado, $lances[$i]->getValor());
        }
    }

    public function testLeilaoNaoDeveReceberLancesRepetidos()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Usuário não pode propor 2 lances consecutivos');

        $leilao = new Leilao('Ford Ka');

        $ana = new Usuario('Ana');
        $leilao->recebeLance(new Lance($ana, 3000));
        $leilao->recebeLance(new Lance($ana, 8000));

        static::assertCount(1, $leilao->getLances());
        static::assertEquals(3000, $leilao->getLances()[0]->getValor());
    }

    public function testLeilaoNaoPodeReceberMaisDe5LancesPorUsuario()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Usuário não pode propor mais de 5 lances por leilão');

        $leilao = new Leilao('Honda Civic');

        for ($i = 0; $i < 6; $i++) {
            $leilao->recebeLance(new Lance(new Usuario('Alice'), $i * 1000));
            $leilao->recebeLance(new Lance(new Usuario('Breno'), $i * 1000));
        }

        static::assertCount(10, $leilao->getLances());
        static::assertEquals(4000, $leilao->getLances()[array_key_last($leilao->getLances())]->getValor());
    }

    public function testLeilaoVazioNaoPodeSerAvaliado()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Não é possível avaliar leilão vazio');
        $leilao = new Leilao('Volkswagen Fusca');

        $avaliador = new Avaliador();
        $avaliador->avalia($leilao);
    }

    public function testLeilaoFinalizadoNaoPodeSerAvaliado()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Leilão já finalizado');

        $leiloeiro = new Avaliador();

        $leilao = new Leilao('Fiat Uno');
        $leilao->finaliza();

        $leiloeiro->avalia($leilao);
    }

    public static function geraLances()
    {
        $joao = new Usuario('Jão');
        $maria = new Usuario('Maria');

        $leilaoCom2Lances = new Leilao('Fiat Palio');

        $leilaoCom2Lances->recebeLance(new Lance($joao, '5000'));
        $leilaoCom2Lances->recebeLance(new Lance($maria, '8000'));

        $leilaoCom1Lance = new Leilao('Fiat Toro');

        $leilaoCom1Lance->recebeLance(new Lance($joao, '40000'));

        return [
          'dois-lances' => [2, $leilaoCom2Lances, [5000, 8000]],
          'um-lance' => [1, $leilaoCom1Lance, [40000]]
        ];
    }
}